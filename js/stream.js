$(function(){
	var ajax = $.ajaxSettings.xhr();
	ajax.open('post', 'stream.php', true);
	$('#tweet').html('Loadig...');
	ajax.send(null);

	ajax.onreadystatechange = function(){
		if(ajax.readyState == 2){
			$('#tweet').html('');
		}else if(ajax.readyState == 3){
			var data = ajax.responseText;
			var lines = data.split("\r\n");
			var line = lines[lines.length-2];

			if(line){
				$('<li>'+line+'</li>').prependTo('#tweet').hide().fadeIn('slow');
			}
		}else if(ajax.readyState == 4){
			$('#tweet').prepend('接続が切れました。リロードしてください。').css('color', 'red');
		}
	}
});
