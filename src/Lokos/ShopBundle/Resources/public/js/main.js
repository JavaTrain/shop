$('.navbar a.dropdown-toggle').on('click', function(e) {
    var $el = $(this);
    var $parent = $(this).offsetParent(".dropdown-menu");
    $(this).parent("li").toggleClass('open');

    if(!$parent.parent().hasClass('nav')) {
        $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
    }

    $('.nav li.open').not($(this).parents("li")).removeClass("open");

    return false;
});

//init choosen
$('.chosen-select').chosen();

//init editor
$.each($('textarea.editor:visible'), function(){
    CKEDITOR.disableAutoInline = true;
    var editor = CKEDITOR.inline(this);
    if ($(this).hasClass('full-editor')) {
        editor.on('configLoaded', function (data) {
            editor.config.toolbar = [
                { name: 'document', items: ['Sourcedialog', 'NewPage', '-', 'Undo', 'Redo'] },
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                '/',
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                { name: 'clipboard', groups: [ 'clipboard' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },
                '/',
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
            ];

            editor.config.removeButtons = 'Underline,Subscript,Superscript,Flash,Iframe,Templates,PageBreak,Styles';
        });
    }
    else {
        editor.on('configLoaded', function (data) {
            editor.config.toolbar = [
                {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']},
                {name: 'styles', items: ['Font', 'FontSize']},
                {name: 'links', items: ['Link']},
                {name: 'paragraph', items: ['NumberedList', 'BulletedList']},
                {name: 'clipboard', items: ['Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']}
            ];
        });
    }
    editor.on('blur', function () {
        $(this.element.$).val(this.getData()).blur();
        if (this.getData().length) {
            $(this.element.$).parents('.tm-block-release').addClass('tm-filled');
        }
        else {
            $(this.element.$).parents('.tm-block-release').removeClass('tm-filled');
        }
    });
});