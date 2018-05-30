/**
 * @author Melnaron
 */

var smiles = {
	
	pack: {},
	
	replace: function(str) {
		var pack = smiles.pack[chat.smiles];
		for (var i = 0; i < pack.length; i++) {
			var exp = pack[i][0]
				.replace(/\:/g, '\\:').replace(/\;/g, '\\;').replace(/\)/g, '\\)').replace(/\(/g, '\\(')
				.replace(/\|/g, '\\|').replace(/\//g, '\\/').replace(/\*/g, '\\*').replace(/\!/g, '\\!')
				.replace(/\?/g, '\\?').replace(/\"/g, '\\"').replace(/\./g, '\\.');
			var reg = new RegExp(exp, 'gi');
			var img = 'img/smiles/'+chat.smiles+'/'+pack[i][1];
			var rep = ' <span class="smile" style="background-image: url('+img+')">&nbsp;</span> ';
			str = str.replace(reg, rep);
		}
		return str;
	},
	
	fill: function() {
		var pack = smiles.pack[chat.smiles];
		$('#smiles').empty();
		for (var i = 0; i < pack.length; i++) {
			var sml = pack[i][0];
			var img = 'img/smiles/'+chat.smiles+'/'+pack[i][1];
			$('<div class="ctrl ctrl_smile" style="background-image: url('+img+')"></div>')
				.attr('smile', sml)
				.tip(pack[i][0])
				.click(function() {
					$('#inpMessage').val($('#inpMessage').val()+' '+$(this).attr('smile')+' ').focusto('end');
				})
				.appendTo('#smiles')
			;
		}
	}
	
};