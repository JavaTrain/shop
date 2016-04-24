<?php

namespace Lokos\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseController extends Controller
{
    /**
     * Translation file
     *
     * @var string
     */
    protected $translation = 'general';

    /**
     * Returns response array for ajax validation
     *
     * @param Form   $form    form with data
     * @param string $baseKey prefix for fields id
     *
     * @return array
     */
    protected function getErrorMessages(Form $form, $baseKey = '')
    {
        $result = array(
            'errors'    => array(),
            'hasErrors' => false,
        );
        if ((!$form->count() && ('file' !== $form->getConfig()->getType()->getName())) || empty($baseKey)) {
            $result['errors'][$baseKey.$form->getName()] = array();
            foreach ($form->getErrors() as $error) {
                $result['errors'][$baseKey.$form->getName()][] = $error->getMessage();
            }
        }
        if ($form->count()) {
            foreach ($form->all() as $child) {
                $childErrors      = $this->getErrorMessages($child, $baseKey.$form->getName().'_');
                $result['errors'] = array_merge($result['errors'], $childErrors['errors']);
            }
        }
        foreach ($result['errors'] as $error) {
            $result['hasErrors'] = $result['hasErrors'] || !empty($error);
        }

        return $result;
    }

    /**
     * Checks token when AJAX request has been sent
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws TokenException When token is invalid
     */
    public function checkToken(Request $request)
    {
        $request = $this->getRequestContent($request);
        if (empty($request->_token) ||
            !$this->container->get('form.csrf_provider')->isCsrfTokenValid('unknown', $request->_token)
        ) {
            throw $this->createTokenException(
                $this->get('translator')->trans('ERROR_TOKEN_INVALID', array(), 'validators')
            );
        }
    }

    /**
     * Returns AJAX request content
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    protected function getRequestContent(Request $request)
    {
        return json_decode($request->getContent());
    }

    /**
     * Returns translated message
     *
     * @param string $msg         Message to be translated
     * @param array  $params      Translation parameters
     * @param string $translation Translation domain
     *
     * @return string
     */
    protected function translate($msg, $params = array(), $translation = null)
    {
        if (!$translation) {
            $translation = $this->translation;
        }

        return $this->get('translator')->trans($msg, $params, $translation);
    }

    /**
     * Returns json response
     *
     * @param mixed   $data    The response data
     * @param integer $status  The response status code
     * @param array   $headers An array of response headers
     *
     * @return string
     */
    protected function jsonResponse($data, $status = JsonResponse::HTTP_OK, $headers = array())
    {
        //http://stackoverflow.com/questions/15394156/back-button-in-browser-not-working-properly-after-using-pushstate-in-chrome
        $headers = array_merge($headers, array('Vary' => 'Accept'));

        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Returns data from session or request
     *
     * @param Request $request  request object with data
     * @param string  $key
     * @param null    $default
     * @param string  $hesh
     *
     * @return mixed
     */
    public function getUserStateFromRequest(Request $request, $key, $default = null, $hesh = '') {
        $session = $this->get('session');

        $result =  $request->get($key, $session->get($hesh.$key, $default));
        $session->set($hesh.$key, $result);

        return $result;
    }

    /**
     * Returns data for show list
     *
     * @param Request $request        request object with data
     * @param string  $repositoryName repository name
     * @param array   $params         additional parameters for search
     * @param string  $orderBy        name of field for ordering. Default 'name'
     * @param string  $orderDir       sort direction. Default 'asc'
     * @param string  $hesh           unique hesh for save valee in the session
     *
     * @return array
     */
    protected function getListData(Request $request, $repositoryName, $params = array(), $orderBy = 'name', $orderDir = 'asc', $hesh = '')
    {
        $globals    = $this->get('twig')->getGlobals();
        $hesh       = strtr($repositoryName, array(':' => '')).$hesh;
        $page       = $this->getUserStateFromRequest($request, 'page', 1, $hesh);
        $limit      = $this->getUserStateFromRequest($request, 'limit', $globals['defaultLimit'], $hesh);
        $search     = $this->getUserStateFromRequest($request, 'search', '', $hesh);
        $orderDir   = $this->getUserStateFromRequest($request, 'order_dir', $orderDir, $hesh);
        $orderBy    = $this->getUserStateFromRequest($request, 'order_by', $orderBy, $hesh);
        $params     = array_merge(array('search' => $search), $params);
        $repository = $this->getDoctrine()
                           ->getRepository($repositoryName)
                           ->reset()
                           ->buildQuery($params);

        $total  = $repository->getCount();
        $offset = ($page - 1) * $limit;
        while (($offset >= $total) && ($page > 1)) {
            $page--;
            $offset = ($page - 1) * $limit;
        }
        if ($limit > 0) {
            $end = min(($offset + $limit), $total);
        } else {
            $end = $total;
        }

        $items = $repository->getList(
            $limit,
            $offset,
            $orderBy,
            $orderDir
        );

        if ($limit > 0) {
            $pagination = $this->get('knp_paginator')->paginate(
                array_fill(0, $total, null),
                $page,
                $limit
            );
        } else {
            $pagination = null;
        }

        return array(
            'items'      => $items,
            'total'      => $total,
            'start'      => $offset + 1,
            'end'        => $end,
            'page'       => $page,
            'limit'      => $limit,
            'limitList'  => $globals['limitList'],
            'search'     => $search,
            'orderBy'    => $orderBy,
            'orderDir'   => $orderDir,
            'pagination' => $pagination,
        );
    }

    /**
     * Execute batch action and return redirect response
     *
     * @param Request $request        request object with data
     * @param string  $repositoryName repository name
     * @param string  $redirectUrl    redirect route name
     * @param array   $redirectParams additional parameters for redirect
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeBatchAction(Request $request, $repositoryName, $redirectUrl, $redirectParams = array())
    {
        $action     = $request->get('action', '');
        $id         = $request->get('id', null);
        $repository = $this->getDoctrine()
                           ->getRepository($repositoryName);
        if(!method_exists($repository, $action)){
            throw new NotFoundHttpException('Method "'.$action.'" not found for '.get_class($repository));
        }
        $result = $repository
             ->$action(
                 $this->container,
                 $id
             );
        if($result){
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->translate(($action == 'remove')?'messages.successfully_removed':'messages.successfully_done')
            );
        }else{
            $this->get('session')->getFlashBag()->add('danger', $this->translate('messages.unsuccessfully_done'));
        }

        return $this->redirect($this->generateUrl($redirectUrl, $redirectParams));
    }
}
