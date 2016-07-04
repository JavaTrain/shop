<?php

namespace Lokos\ShopBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('lokos_shop');

        $rootNode
            ->children()
            ->arrayNode('main_menu')
            ->isRequired()
            ->children()
            ->scalarNode('translation_domain')
            ->defaultValue('menu')
            ->end()
            ->end()
            ->append($this->addMenuOptionConfig())
            ->append($this->addItemsConfig())
            ->end()
            ->arrayNode('dropdown_menu')
            ->isRequired()
            ->children()
            ->scalarNode('translation_domain')
            ->defaultValue('menu')
            ->end()
            ->end()
            ->append($this->addMenuOptionConfig())
            ->append($this->addItemsConfig())
            ->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * Configures items
     *
     * @param int $depth
     *
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    private function addItemsConfig($depth = 0)
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('items');
        if ($depth < 6) {
            $rootNode
                ->prototype('array')
                ->children()
                ->scalarNode('title')
                ->isRequired()
                ->cannotBeEmpty()
                ->end()
                ->scalarNode('icon')
                ->end()
                ->scalarNode('uri')
                ->end()
                ->scalarNode('route')
                ->end()
                ->scalarNode('permission')
                ->end()
                ->arrayNode('all_routes')
                ->prototype('scalar')
                ->end()
                ->end()
                ->append($this->addItemsConfig($depth + 1))
                ->end()
                ->end();
        }

        return $rootNode;
    }

    /**
     * Configures shortcut items
     *
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    private function addMenuOptionConfig()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('options');

        return $rootNode;
    }
}
