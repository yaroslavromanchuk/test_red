tinyMCEPopup.requireLangPack();

var PasteHTMLDialog = {
	init : function() {
		this.resize();
	},

	insert : function() {
		//var h = tinyMCEPopup.dom.encode(document.getElementById('content').value);
		var h = document.getElementById('content').value;

		tinyMCEPopup.editor.execCommand('mceInsertContent', false, h);
		tinyMCEPopup.close();
	},
	resize : function() {
		
		var vp = tinyMCEPopup.dom.getViewPort(window), el;

		el = document.getElementById('content');

		el.style.width  = (vp.w - 20) + 'px';
		el.style.height = (vp.h - 90) + 'px';
	}
};

tinyMCEPopup.onInit.add(PasteHTMLDialog.init, PasteHTMLDialog);