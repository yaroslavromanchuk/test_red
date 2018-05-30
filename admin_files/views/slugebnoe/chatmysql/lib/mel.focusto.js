/**
 * FocusTo: jQuery Plugin
 * 
 * @author  Melnaron
 * @version 1.0
 * 
 * Usage:
 * 
 * // Set caret position to start of field value and focus
 * $('input').focusto('start');
 * 
 * // Set caret position to end of field value and focus
 * $('input').focusto('end');
 * 
 * // Set caret position to 4 of field value and focus
 * $('input').focusto(4);
 */

jQuery.fn.focusto = function(pos) {
	for (var i = 0; i < this.length; i++) {
		var field = this[i];
		if (pos == 'start') {
			pos = 0;
		} else if (pos == 'end') {
			pos = field.value.length;
		} else {
			pos = (pos <= field.value.length) ? pos : field.value.length;
		}
		if (field.createTextRange) {
			var range = field.createTextRange();
			range.move('character', pos);
			range.select();
		} else if (field.setSelectionRange) {
			field.setSelectionRange(pos, pos);
		}
		field.focus();
	}
	return this;
};