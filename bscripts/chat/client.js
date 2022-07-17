const socket = io('http://localhost:3005');

socket.on('chat-id', function(id) {
	var http = new XMLHttpRequest();
	http.onreadystatechange = function() {

		if(this.readyState==4 && this.status==200) {
		}

	}
	var formdata = new FormData();
	formdata.append('chatid', id);
	http.open('POST', './bscripts/actions/updatechat-id.php');
	http.send(formdata);
});

socket.on('private-chat-recv', function(data) {
	var uidf = data.uidf;
	var mess = data.mess;
	var date = data.date;
	var uidm = data.uidm; //sender uid

	var messhtml = "<li><div class='mess f_ ft_' data-time='"+date+"'><span>"+mess+"</span></div></li>";

	var changedata = parseInt(document.querySelector('.onlinefrnds #line').getAttribute('data-change'));
	var chatwindowcheck = document.querySelector('.chatwindow').getAttribute('data-uid');
	if(chatwindowcheck!=uidm) {
		document.querySelector('.right_more_ i').setAttribute('id', 'active');		
	}

	console.log(chatwindowcheck+" "+uidm);


	var message_noti = document.getElementById('message_noti');
	message_noti.play();	

	try {
		var prevdata = document.querySelector('.chatwindow[data-uid='+uidm+'] .mainchat .maindata ul').innerHTML;
		document.querySelector('.chatwindow[data-uid='+uidm+'] .mainchat .maindata ul').innerHTML = messhtml+prevdata;
	}
	catch(err) {
	}

});

function sendmess() {
	var mess = document.querySelector('#chat_mess_input').value;

	if(mess!="" && mess!=undefined) {

		var chatid = document.querySelector('.chatwindow').getAttribute('data-cid');
		var uidf = document.querySelector('.chatwindow').getAttribute('data-uid');

		var uidm = localStorage.getItem('uid');

		var d = new Date();
		var hours = d.getHours();
		var ampm = "am";
		if(hours>12) {
			hours -= 12;
			ampm = "pm";
		}
		if(hours==0) {
        	hours = 12;
        }
		var mins = d.getMinutes();
		var date = hours+":"+mins+" "+ampm;

		var messhtml = "<li><div class='mess m_ mt_' data-time='"+date+"'><span>"+mess+"</span></div></li>";
		var prevdata = document.querySelector('.chatwindow[data-uid='+uidf+'] .mainchat .maindata ul').innerHTML;
		document.querySelector('.chatwindow[data-uid='+uidf+'] .mainchat .maindata ul').innerHTML = messhtml+prevdata;

		var formdata_n = new FormData();
		
		socket.emit('private-chat-send', {chatid: chatid, mess: mess, uidf: uidf, date:date, uidm: uidm});

		var xml = new XMLHttpRequest();
		xml.onreadystatechange = function() {
			if(this.readyState==4 && this.status==200) {
				var resp = this.responseText;
				if(resp==1) {

				}	
				else {
				}		
			}
		}
		var formdata = new FormData();
		formdata.append('mess', mess);
		formdata.append('uidf', uidf);
		formdata.append('type', 'text');
		xml.open("POST", "bscripts/chat/sendmess.php");
		xml.send(formdata);

		var xml_last_mess = new XMLHttpRequest();
		xml_last_mess.onreadystatechange = function() {
			if(this.readyState==4 && this.status==200) {
				var resp = this.responseText;
			}
		}
		formdata_n.append('uidf', uidf);
		formdata_n.append('uidm', uidm);
		formdata_n.append('last_read_f', '0');
		formdata_n.append('message', mess);
		xml_last_mess.open("POST", "bscripts/chat/change_last_message.php");
		xml_last_mess.send(formdata_n);

	}

}
