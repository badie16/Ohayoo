$(".left-nav-bar .menu-main a").each((i,e)=>{
	$(e).removeClass("active");
	if (e.dataset.name === "chat"){
		$(e).addClass("active");
	}
})

/* start audio recorder area code*/
function handleBtnRecorder(){
	let intVlRecordTimin;
	const btnAudioRecorderChat = $(".audioOrSend .audio");
	const deleteAudioRecorderChat = $(".msg-audio-label .btnDelete");
	const pauseAudioRecorderChat = $(".msg-audio-label .btnPause");
	const playResumeAudioRecorderChat = $(".msg-audio-label .btnPlayResume");
	const recordTiming = $(".msg-audio-label .time .value")
	let streamRecorderAudio = null;
	let pushDataToForm = false;
	let mediaRecorder;
	let audioTracks = [];
	btnAudioRecorderChat.click(
		function (){
			if (streamRecorderAudio){
				$(".inputMsgAria .send").show().addClass("formOfSendAudio")
				$(".inputMsgAria .audio").hide()
				$(".chat-box .msg-audio-label").css("display","flex")
				mediaRecorder = new MediaRecorder(streamRecorderAudio);
				recordAudio();
			}
		}
	)
	$(document).on("click",".inputMsgAria .send.formOfSendAudio",function (e){
		e.preventDefault()
		window.typeOfSendMsg = 2;
		pushDataToForm = true;
		recordAudio()
	})
	deleteAudioRecorderChat.click(
		function (){
			pushDataToForm = false;
			recordAudio();
		}
	)
	pauseAudioRecorderChat.click(
		function (){
			mediaRecorder.pause()
			// $(".msg-audio-label .pauseAria .testAudioPause").show()
			// $(".msg-audio-label .time").hide()
			pauseAudioRecorderChat.hide();
			playResumeAudioRecorderChat.show();
		}
	)
	playResumeAudioRecorderChat.click(
		function (){
			mediaRecorder.resume();
			// $(".msg-audio-label .pauseAria .testAudioPause").hide()
			// $(".msg-audio-label .time").show()
			pauseAudioRecorderChat.show();
			playResumeAudioRecorderChat.hide();
		}
	)
	function initAudioRecording()
	{
		if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia){
			navigator.mediaDevices.getUserMedia({ audio: true })
				.then(function(stream){
					$(".chat-box").removeClass('disableRecording');
					streamRecorderAudio = stream;
				})
				.catch(function(err){
					$(".chat-box").addClass('disableRecording');
				});
		}else{
			btnAudioRecorderChat.attr('disabled', true);
		}
	}
	function recordAudio()
	{
		if(typeof(mediaRecorder) != 'undefined'){
			let action = btnAudioRecorderChat.hasClass('recording') ? 'stop' : 'record';
			if(action === 'record'){
				mediaRecorder.ondataavailable = function(e){
					audioTracks.push(e.data);
				};
				mediaRecorder.onstop = function(){
					window.audioBlob = new Blob(audioTracks, {type: 'audio/ogg; codecs=opus'});
					if (pushDataToForm){
						$(".chat-box form.inputMsgAria").submit();
					}
					pauseAudioRecorderChat.show();
					playResumeAudioRecorderChat.hide();
					clearInterval(intVlRecordTimin);

				};
				mediaRecorder.onpause = function (){
					clearInterval(intVlRecordTimin);
				}
				mediaRecorder.onresume = function (){
					intVlRecordTimin = setInterval(function(){
						runRecordTimer();
					}, 1000);
				}
				mediaRecorder.onstart = function(){
					intVlRecordTimin = setInterval(function(){
						runRecordTimer();
					}, 1000);
				};
				mediaRecorder.start();
				btnAudioRecorderChat.addClass('recording');
				recordTiming.show();
			}else{
				$(".inputMsgAria .send").hide().removeClass("formOfSendAudio")
				$(".inputMsgAria .audio").show()
				$(".chat-box .msg-audio-label").hide()
				clearInterval(intVlRecordTimin);
				recordTiming.html("00:00");
				mediaRecorder.stop();
				audioTracks =[];
				mediaRecorder= undefined;
				btnAudioRecorderChat.removeClass('recording');
			}
		}
	}
	function runRecordTimer()
	{
		var time = recordTiming.text();
		time = time.split(':');
		var min  = Number(time[0]);
		var sec  = Number(time[1]);

		if(sec >= 60){
			if(min > 0){
				sec = 0;
				min++;
			}
		}else{
			sec++;
		}

		if(sec < 10){
			sec = "0" + sec;
		}

		if(min < 10){
			min = "0" + min;
		}

		recordTiming.html(min + ":" + sec);
	}
	initAudioRecording();
}

/* end audio recorder area code*/

/* start audio playing area code*/
class PlayerAudioSrc{
	playersObject = {}
	playingAudioCurrent=null
	progress;
	constructor() {
		this.playersObject = {}
	}
	pushData(id, data){
		this.playersObject[id] = data;
	}
	getSrc(id){
		if (this.playersObject[id] !== undefined) {
			return this.playersObject[id].src
		}else {
			return null;
		}
	}
	isPlaying(id){
		if (this.playersObject[id] !== undefined) {
			return this.playersObject[id].playing;
		}else {
			return false;
		}
	}
	setPlayingStatus(id,status){
		if (this.playersObject[id] !== undefined) {
			this.playersObject[id].playing = status;
		}
	}

}
let playerAudioSrc = new PlayerAudioSrc();
window.player = new Audio();
let AudioBoxParent;
$(document).on("click",".playAudioMsg",function (){
	let start =true;
	AudioBoxParent = $(getParent(this,"myAudioF1"));
	if (playerAudioSrc.getSrc(this.id) === null){
		let ViSrc = AudioBoxParent.find(".Visrc");
		playerAudioSrc.pushData(this.id,{
			src : ViSrc.val(),
			playing:false
		})
		ViSrc.remove();
	}
	let id = this.id;
	if (this.getAttribute("play") === "true"){
		this.setAttribute("play", false);
	}else {
		this.setAttribute("play", true);
	}
	if (playerAudioSrc.playingAudioCurrent !== id) {
		if (playerAudioSrc.getSrc(id)){
			playerAudioSrc.getSrc(id)
			window.player.src= playerAudioSrc.getSrc(id);
			window.player.id = id;
			if (playerAudioSrc.playingAudioCurrent){
				$(".playAudioMsg#"+playerAudioSrc.playingAudioCurrent).attr("play", false);
				$(".slider#slide"+playerAudioSrc.playingAudioCurrent).val(0);
				playerAudioSrc.setPlayingStatus(playerAudioSrc.playingAudioCurrent,false)
			}
			playerAudioSrc.playingAudioCurrent = id;
		}
	}
	if (playerAudioSrc.isPlaying(id)) {
		window.player.pause();
		playerAudioSrc.setPlayingStatus(id,false);
	} else {
		window.player.onloadstart = () => {

		};
		window.player.onloadeddata = () =>{
		}
		AudioBoxParent = $(getParent(this,"myAudioF1"));
		window.player.play()
		let c =window.player.currentTime;
		if (!isFinite(window.player.duration) || isNaN(window.player.duration)){
			window.player.currentTime = 100000;
			window.player.currentTime = c;
		}
		playerAudioSrc.setPlayingStatus(id,true);
		$(window.player).on('timeupdate', function () {
			if (start){
				let a = $(AudioBoxParent).find("input.slider").val();
				let b= (window.player.duration * a) / 100;
				if (!isNaN(b)){
					this.currentTime = b;
				}
				start = false;
			}
			let progress = (this.currentTime / this.duration) * 100;
			AudioBoxParent.find("input.slider").val(progress)
			AudioBoxParent.css("--i",progress+"%")
			if (progress === 100) {
				playerAudioSrc.setPlayingStatus(id,false);
				playerAudioSrc.playingAudioCurrent = null;
				window.player.pause();
				AudioBoxParent.find(".play").attr("play", false);
			}
		});

	}
})
$(document).on("input",".slider",function (e){
	let a = $(e.target).val()
	 $(getParent(e.target,"myAudioF1")).css("--i",a+"%")
	let b = window.player.duration;
	if (!isFinite(b)){
		window.player.currentTime = 10000;
		window.player.currentTime = 0;
	}
	let c =  (b * a) / 100;
	if (!isNaN(c) && e.target.id === "slide"+playerAudioSrc.playingAudioCurrent) {
		window.player.currentTime = c
	}
})
/* end audio playing area code*/

/* start chat option Menu area code*/
$(document).on("click",".btnOpenOptionChat",function (){
	$(this).toggleClass("active");
})

/* end chat option Menu  area code*/
/* start list contact area code*/
$(".btnShowListContact").click(function(){
	$(".chat-left-section .contactList").toggleClass("active");
})

let moreMsg = true;
$(document).on("click",".contactList .users .user",function (){
	initVariables();
	$(".chat-left-section .contactList").removeClass("active");
	let receiverId = $(this).find(".usid").val();
	getChatBox(receiverId);
})
$(document).on("click",".closeChatBox",function (){
	initVariables();
	let container = $(".chat-box");
	$.ajax({
		url: root +"layouts/chat/getDiscussion2.php",
		type: 'get',
		success: function(response) {
			let data = JSON.parse(response);
			$(".listOfMessages").html(data.msg);
		},
		complete: function (){
			container.removeClass("activeMsg")
		}
	})
	container.html("");
})
/* end  contact area code*/

/* start  Discussion area code*/
$(".listOfMessages").on("click",".chat-user",function (){
	initVariables();
	$(".listOfMessages").find(".chat-user").removeClass("active");
	$(this).addClass("active");
	let receiverId = $(this).find(".DId").val();
	getChatBox(receiverId);
})

function updateDiscussionList(){
	$.ajax({
		url: root +"layouts/chat/getDiscussion.php",
		type: 'get',
		success: function(response) {
			let data = JSON.parse(response);
			if (data.code > 0){
				window.data = data.msg;
				$.each(data.msg,function (i,e){
					let a = $(".listOfMessages .chat-user#"+e.id);
					if (a.length){
						if (a.html() != $(e.content).html()){
							a.html($(e.content).html());
						}
					}else {
						$(".listOfMessages").prepend(e.content);
					}
					a.css("order",i);
				})
			}
		}
	})
}

function getChatBox(receiverId){
	$.ajax({
		url: root +"layouts/chat/getChatBox.php",
		type: 'post',
		data: {
			limit_msg:window.limit_msg,
			receiver: receiverId,
		},
		success: function(response) {

			let data = JSON.parse(response);
			if(data.success){
				let container = $(".chat-box");
				container.addClass("activeMsg")
				container.html(data.msg);
                let messAra = $(container).find(".chat-aria").get(0);
                messAra.scrollTop = messAra.scrollHeight;
				window.limit_msg = data.limit_msg;
				window.last_msg = data.last_msg;
				handlerChatBox(container);
                handlerDiscussionOption(container)
				handle_message_elements_eventsFromContainer(container)
				handleBtnRecorder();
				moreMsg = true;
				$.ajax({
					url: root +"api/chat/updateStatus.php",
					type: 'post',
					data: {
						last_msg : window.last_msg,
						msg_from: receiverId,
						code: 1
					},
					success: function(response) {
					}
				})

			}
		}
	})
}
function handlerDiscussionOption(container){
    const btnShowMenuP = $(container).find(".btnShowMenuP");
	btnShowMenuP.click(function (){
        $(container).find(".right-bar-info").toggleClass("active");
    })
	const deleteDisc = $(container).find(".delete");
	deleteDisc.click(function (){
		const token = $(getParent(this,"menu-option")).find(".token_v").val();
		if (token === undefined)return false;
		console.log(token)
		$.ajax({
			url: root +"api/chat/updateDiscussionSeating.php",
			type: 'post',
			data:{
				code:1,
				token:token
			},
			success: function(response) {
				let data = JSON.parse(response);
				if (data.success){
					setTimeout(()=>{
						window.location.reload();
					},200)
				}
			}
		})
	})
}
/* end  Discussion area code*/
/* start send msg area code*/
function initVariables(){
	window.limit_msg = -1;
	window.last_msg = 0;
	window.typeOfSendMsg=0;
}
initVariables();
$(document).on("input",".inputMsgAria .msg-text-field",function (){
	chickInputMsg(this);

})
let message_writing_notifier = 0;
function chickInputMsg(e){
	if ($(e).val() === ""){
		$(".inputMsgAria .send").hide()
		$(".inputMsgAria .audio").show()
		message_writing_notifier = 1;
	}else {
		$(".inputMsgAria .send").show()
		$(".inputMsgAria .audio").hide()
		message_writing_notifier = 0;
	}
	let to = $(".inputMsgAria").find(".msg_to").val();
	$.ajax({
		type: "POST",
		url: root + "api/chat/message_writing_notifier.php",
		data: {
			to_id: to,
			code: message_writing_notifier
		},
		success: function (res) {
			let data =JSON.parse(res);
			if (data.success){
				message_writing_notifier = data.code;
			}
		},
	});
}
let getMsgAfterS = false;
$(document).on("submit","form.inputMsgAria",function (e){
	e.preventDefault();
	let input = $(".inputMsgAria .msg-text-field");
	let formData = new FormData(this);
	if (typeOfSendMsg === 2){
		if (window.audioBlob !== undefined) {
			if (window.audioBlob.type.search("audio") !== -1) {
				formData.append("mediaRecorder", window.audioBlob)
				window.audioBlob = null;
			} else {
				return;
			}
		}else {
			return;
		}
	}else {
		window.typeOfSendMsg = 0;
	}
	input.val('');
	$(".reply-container").hide().find(".replaySection").html("");
	chickInputMsg(input);
	formData.append("msg_type",typeOfSendMsg);
	$.ajax({
		url: root +"api/chat/addMsg.php",
		type: 'post',
		enctype: 'multipart/form-data',
		contentType: false,
		processData: false,
		data: formData,
		success: function(response) {
			getMsgAfterS = true;
			getMessages(true);
			window.typeOfSendMsg = 0;
		},
		complete: function (){

		}
	})
})
function getMessages(scroll=false){
	let container = $(".chat-box");
	let receiverId = container.find(".msg_to").val();
	let senderId = container.find(".msg_from").val();
	if (receiverId === undefined ||  senderId === undefined ){
		return;
	}
	$.ajax({
		url: root +"api/chat/getLastMsg.php",
		type: 'post',
		data: {
			last_msg:window.last_msg,
			to: receiverId,
			from: senderId
		},
		success: function(response) {
			let data = JSON.parse(response)
			if (data.success){
				window.last_msg = data.last_msg;
				/*new Msg cas code = 0*/
				if (data.code === 0){
					let listNewMsg = data.data;
					for (let msg of listNewMsg) {
						let container = $(".chat-box");
						container.find(".chat-aria .chats").append(msg);
						removeDoubleMSg()
						handle_message_elements_eventsFromContainer(container)
						if (scroll){
							$(".chat-box .chat-aria").scrollTop($(".chats .chat-items").last().get(0).offsetTop);
							getMsgAfterS = false;
						}
					}
					$.ajax({
						url: root +"api/chat/updateStatus.php",
						type: 'post',
						data: {
							last_msg : window.last_msg,
							msg_from: receiverId,
							code: 1
						},
						success: function(response) {
						}
					})
				}
				if (data.isTyping){
					$(".headerChatBox").find(".isTyping").addClass("active");
				}else {
					$(".headerChatBox").find(".isTyping").removeClass("active");
				}
				$(".stateOfSeen").html(data.msgStatusContainer);
			}
		}
	})
}
setInterval(()=>{
	if (!getMsgAfterS){
		getMessages()
	}
	updateDiscussionList()
},500)
function removeDoubleMSg(){
	let msgID ="";
	$(".chat-items").each((i,e)=>{
		if(msgID === e.id){
			e.remove()
			msgID = "";
		}
		msgID = e.id;
	})
}

function generateMsg(container){
	if (moreMsg){
		$(".more-msg").show();
	}
	let receiverId = $(container).find(".msg_to").val();
	$.ajax({
		url: root +"api/chat/getMsg.php",
		type: 'post',
		data: {
			limit_msg: window.limit_msg,
			receiver: receiverId,
		},
		success: function(response) {
			let data = JSON.parse(response);
			if(data.success){
				let container = $(".chat-box");
				if (data.success){
					if (data.msg !== ""){
						container.find(".chat-aria .chats").prepend(data.msg);
						window.limit_msg = data.limit_msg;
						handlerChatBox(container);
						handle_message_elements_eventsFromContainer(container)
					}else {
						moreMsg = false;
					}
				}
				$(".more-msg").hide();
			}
		}
	})
}
function handlerChatBox(container){
	const  chat_aria = $(container).find(".chat-aria");
	chat_aria.scroll(function (e){
		if(this.scrollTop <= 0){
			if (moreMsg) {
				generateMsg(container);
				$(this).scrollTop(2)
			}
		}
	})
}
/* end send msg area code*/


/* start msg event area code*/
function  handle_message_elements_eventsFromContainer(container){
	$(container).find(".chat-items.handle").each((k,e)=>{
		$(e).removeClass('handle');
		handle_message_elements_events(e);
	})
}
function handle_message_elements_events(element) {
	let btnOption = $(element).find(".chat-message-more-button");
	btnOption.click( function (event) {
		let container = $(this).parent().find(".sub-options-container");
		if (!$(this).hasClass("active")) {
			$(".chat-items")
				.find(".chat-message-more-button").removeClass("active");
			$(this).addClass("active");
			let last = $(".chat-items").find(".chat-message-more-button").last().get(0);
			if (last === this){
				$(".chat-box .chat-aria").scrollTop(getParent(last,"chat-items").offsetTop +100);
			}

		} else {
			$(this).removeClass("active");
		}
		return false;
	});
	document.addEventListener("click",(e)=>{
		if (!e.target.classList.contains(".chat-message-more-button")
			&& !e.target.classList.contains(".sub-options-container")){
			$(".chat-items")
				.find(".chat-message-more-button").removeClass("active");
		}
		if (!e.target.classList.contains("btnOpenOptionChat")){
			$(".btnOpenOptionChat ").removeClass("active");
		}

	})
	let deleteBtn =  $(element).find(".deleteBtn");
	deleteBtn.click(function (){
		let msgId = $(this).parent().find(".msg_id");
		if (msgId == null)
			return false;
		msgId = $(msgId).val();
		$.ajax({
			url: root +"api/chat/delete.php",
			type: 'post',
			data: {
				msg_id: msgId,
			},
			success: function(response) {
				let data = JSON.parse(response);
				if(data.success){
					let container = $(".chat-box");
					// if (window.last_msg == msgId){
					// 	let list =  container.find(".chat-items");
					// 	window.last_msg = list.get(list.length-2).id;
					// }
					if (data.success){
						if (data.code === 1){
							if (container.find(".chat-items#msg"+msgId).find(".myAudioF1").length){
								window.player = new Audio();
							}
							container.find(".chat-items#msg"+msgId).remove();
						}
					}
				}
			}
		})
	})

	/* btn of reply */
	let btnReply = $(element).find(".replyBtn")
	btnReply.click(function () {
		const msg_id = $(this).parent().find(".message_id").val();
		$(".chat-items")
			.find(".chat-message-more-button").removeClass("active");
		$.ajax({
			url: root +"layouts/chat/generateReplyContainer.php",
			type: 'post',
			data: {
				msg_id : msg_id
			},
			success: function(response) {
				let data = JSON.parse(response);
				if (data.success && data.code === 0){
					let replayC=  $(".reply-container")
					replayC.find(".replaySection").html($(data.msg).html())
					replayC.show();
				}
			}
		})
		return false;
	});
	/* btn of close reply container */
	$(".close-replay-container").click(function (){
		let replayC =  $(".reply-container");
		replayC.hide();
		replayC.find(".replaySection").html("");
	})

	let btnGoToOriginMsg = $(element).find(".replaySection a");

	btnGoToOriginMsg.click(function (){
		let chat = $($(this).attr("href")).find(".chat");
		chat.addClass("animateScroll");
		setInterval(()=>{
			chat.removeClass("animateScroll");
		},1000)
	});

	let btnCopyMsgText = $(element).find(".copyBtn");
	btnCopyMsgText.click(function () {
		const container = getParent(this,"chat-items");
		const msg_text = $(container).find(".msg p").html();
		if (navigator.clipboard) {
			navigator.clipboard.writeText(msg_text);
		}
	});

}
/* end msg event area code*/

/* start search area code */
$(".searchChatInput").on({
	input: function (e){
		console.log(e)
	}
})
/* end search area code */