//init choosen
$('.chosen-select').chosen();

//init editor
$.each($('textarea.editor:visible'), function(){
    CKEDITOR.disableAutoInline = true;
    var editor = CKEDITOR.inline(this);
    editor.on('blur', function () {
        $(this.element.$).val(this.getData()).blur();
    });
});