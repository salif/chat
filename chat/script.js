(function () {
	var chat_conf = { "name": "Chat", "server": "/chat/chat.php" };
	var curr_id = 0;
	var interval;

	function print_msg(msg) {
		var output = "";
		if (msg.username === "M") {
			output = "<li class='chat-d chat-me'>" + mescape(msg.text) + "</li>";
		} else if (msg.username === "E") {
			output = "<li class='chat-d chat-e'>Error: " + mescape(msg.text) + "</li>";
		} else {
			output = "<li class='chat-d chat-bot'>" + mescape(msg.username) + ": " + mescape(msg.text) + "</li>";
		}
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
			data: { command: "send", data: msg },
			dataType: "json"
		}).done(function (response) {
			if (response.iserr) {
				print_msg({ text: response.err, username: "E" });
			}
		}).fail(function () {
			print_msg({ text: "error!", username: "E" });
		});
	}

	// TODO: Add
	function load_chat() {
		$.ajax({
			type: "post",
			url: chat_conf["server"],
			data: { command: "load", data: curr_id },
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			dataType: "json",
		}).done(function (response) {
			if (response.iserr) {
				print_msg({ text: response.err, username: "E" });
			} else {
				response.msgs.forEach(msg => {
					console.log(JSON.stringify(msg))
					curr_id = msg.id;
					print_msg({ text: msg.text, username: msg.username });
				})
			}
		}).fail(function () {
			print_msg({ text: "error!", username: "E" });
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
