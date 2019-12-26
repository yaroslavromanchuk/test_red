/**
 * @author Melnaron
 */

var user = {
	
	id:       null,
	pass:     null,
	nickname: null,
	access:   null,
	status:   null,
	
	sock:     null,
	conn:     false,
	
	setNickname: function(n) {
		user.nickname = n;
		$('#inpNickname').val(n);
		$('#nickname span').text(n);
		$.cookie('nickname', n, {expires: 7});
	},
	
	setAccess: function(a) {
		user.access = a;
		$('#nickname').attr('class', 'l'+a);
	},
	
	setStatus: function(s) {
		user.status = s;
		$('#nickname span').attr('class', 'icon_status_'+s);
		$('#status span').text(chat.statuses[s]);
		if (user.conn) {
			$.ajax({
				data: 'action=SetStatus&pass='+user.pass+'&status='+s
			});
		}
		chat.hideStatuses();
	}
	
};