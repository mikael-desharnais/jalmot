
CKEDITOR.editorConfig = function( config )
{
	config.toolbar_Full =
[
    ['Source','-'],
    ['PasteFromWord','-', 'SpellChecker', 'Scayt'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
    ['Font','FontSize'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks','-','About']
];
	config.autoGrow_maxHeight = 300;
	customConfig : 'lib/CKEditor/config-htlmlanguagefieldME.js'
};
