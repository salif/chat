(function() {
var chat_conf = {"name": "Public Chat", "server": "/chat/chat.php"};
var curr_id = 0;
var interval;

function print_msg(msg) {
	var output = "";
	if(msg["text"].length === 0) return;
	if (msg["me"])
		output = "<li class='chat-d chat-me'>" + mescape(msg["text"]) + "</li>";
	else
		output = "<li class='chat-d chat-bot'>" + mescape(msg["username"]) + ": " + mescape(msg["text"]) + "</li>";
	$("#chat-ul").append(output);
	$("#chat-ul").scrollTop($("#chat-ul")[0].scrollHeight);
}

function mescape(text) {
	return text
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
}

function send_msg(msg) {
	$.ajax({
		type: "POST",
		url: chat_conf["server"],
		data: {q:"send", data: msg},
		dataType: "json"
	}).fail(function (err) {
		console.log(JSON.stringify(err));
		print_msg("error: message can't be sent!", "he");
	});
}

function load_chat() {
	$.ajax({
		type: "POST",
		url: chat_conf["server"],
		data: {q:"load", data:curr_id},
		success: function(retr){
			curr_id = retr.id;
			var msgs = retr.data;
			for(msg in msgs) {
				print_msg(msgs[msg], "he");
			}
		},
		dataType: "json"
	}).fail(function () {
		print_msg("error: chat can't be loaded!", "he");
	});
}

$(document).ready(function () {
	$("#chat-name").html(mescape(chat_conf["name"]));
	$("#chat-loader").hide();
	$("#chat-ul").show();
	$("#chat-input").keyup(function (e) {
		if (e.which == 13) {
			e.preventDefault();
			var say = $("#chat-input").val();
			if (say.length > 0) {
				$("#chat-input").val("");
				send_msg(say);
				load_chat();
			}
		}
	});
	$("#chat-button").click(function () {
		var say = $("#chat-input").val();
		if (say.length > 0) {
			$("#chat-input").val("");
			send_msg(say);
			load_chat();
		} else {
			$("#chat-input").focus();
		}
	});
	load_chat();
	interval = setInterval(load_chat, 5000);
});

})();
