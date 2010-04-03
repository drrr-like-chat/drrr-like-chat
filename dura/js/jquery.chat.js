jQuery(function($)
{
	var postAction = null;
	var getAction  = null;

	var formElement     = null;
	var textareaElement = null;
	var talksElement    = null;
	var membersElement  = null;
	var logoutElement   = null;
	var buttonElement   = null;
	var iconElement     = null;

	var isSubmitting = false;
	var isUseSound   = true;
	var lastMessage  = '';
	var isLoggedOut  = false;
	var isLoading    = false;

	var userId   = null;
	var userName = null;
	var userIcon = null;

	var messageLimit = 50;

	var construct = function()
	{
		var url = location.href.replace(/#/, '');

		if ( url.replace(/\?/, '') != url )
		{
			postAction = url+"&ajax=1";
		}
		else
		{
			postAction = url+"?ajax=1";
		}

		getAction  = duraUrl+'/ajax.php';

		formElement     = $("#message");
		textareaElement = $("#message textarea");
		talksElement    = $("#talks");
		membersElement  = $("#members");
		logoutElement   = $("input[name=logout]");
		buttonElement   = $("input[name=post]");
		iconElement     = $("dl.talk dt");

		userId   = trim($("#user_id").text());
		userName = trim($("#user_name").text());
		userIcon = trim($("#user_icon").text());

		messageMaxLength = 140;

		if ( typeof(GlobalMessageMaxLength) != 'undefined' )
		{
			messageMaxLength = GlobalMessageMaxLength;
		}

		appendEvents();
		separateMemberList();
		roundBaloons();

		if ( useComet )
		{
			getMessages();
		}
		else
		{
			var timer = setInterval(function(){getMessagesOnce();}, 1500);
		}
	}

	var appendEvents = function()
	{
		formElement.submit(submitMessage);
		textareaElement.keyup(enterToSubmit);
		logoutElement.click(logout);
		iconElement.click(addUserNameToTextarea);
	}

	var submitMessage = function()
	{
		var message = textareaElement.val();
		message.replace(/[\r\n]+/g, "");

		if ( message.replace(/^[ \n]+$/, '') == '' )
		{
			return false;
		}

		if ( isSubmitting )
		{
			return false;
		}

		var data = formElement.serialize();

		if ( message.match(/^\/sound/) )
		{
			if ( isUseSound == true )
			{
				isUseSound = false;
			}
			else
			{
				isUseSound = true;
			}

			ringSound();
			textareaElement.val('');
			return false;
		}

		if ( message.match(/^\/member/) )
		{
			if ( membersElement.is(":hidden") )
			{
				membersElement.slideDown("slow");
				textareaElement.val('');
				return false;
			}
		}

		if ( membersElement.is(":not(:hidden)") )
		{
			membersElement.slideUp("slow");
		}

		if ( message == lastMessage )
		{
			if ( confirm(t("Will you stop sending the same message? If you click 'Cancel' you can send it again.")) )
			{
				textareaElement.val('');
				return false;
			}
		}

		textareaElement.val('');
		isSubmitting = true;
		buttonElement.val(t("Sending..."));

		lastMessage  = message;

		if ( message.length - 1 > messageMaxLength )
		{
			message = message.substring(0, messageMaxLength)+"...";
		}

		writeSelfMessage(message);

		$.post(postAction, data,
			function()
			{
				isSubmitting = false;
				buttonElement.val(t("POST!"));
			}
		);

		return false;
	}

	var getMessagesOnce = function()
	{
		if ( isLoading )
		{
			return;
		}

		isLoading = true;

		$.post(getAction+'?fast=1', {}, 
			function(data)
			{
				isLoading = false;
				validateResult(data);
				writeMessages(data);
				writeUserList(data);
			}
		, 'xml');
	}


	var getMessages = function()
	{
		$.post(getAction+'?fast=1', {}, 
			function(data)
			{
				loadMessages();
				validateResult(data);
				writeMessages(data);
				writeUserList(data);
			}
		, 'xml');
	}

	var loadMessages = function()
	{
		$.post(getAction, {}, 
			function(data)
			{
				loadMessages();
				validateResult(data);
				writeMessages(data);
				writeUserList(data);
			}
		, 'xml');
	}

	var writeMessages = function(data)
	{
		$.each($(data).find("talks"), writeMessage);
	}

	var writeMessage = function()
	{
		var id = $(this).find("id").text();

		if ( $("#"+id).length > 0 )
		{
			return;
		}

		var uid     = trim($(this).find("uid").text());
		var name    = trim($(this).find("name").text());
		var message = trim($(this).find("message").text());
		var icon    = trim($(this).find("icon").text());
		var time    = trim($(this).find("time").text());

		name    = escapeHTML(name);
		message = escapeHTML(message);

		if ( uid == 0 || uid == '0' )
		{
			var content = '<div class="talk system" id="'+id+'">'+message+'</div>';
			talksElement.prepend(content);
		}
		else if ( uid != userId )
		{
			var content = '<dl class="talk '+icon+'" id="'+id+'">';
			content += '<dt>'+name+'</dt>';
			content += '<dd><div class="bubble">';
			content += '<p class="body">'+message+'</p>';
			content += '</div></dd></dl>';
			talksElement.prepend(content);
			effectBaloon();
		}

		weepMessages();
	}

	var writeUserList = function(data)
	{
		membersElement.find("li").remove();

		var total = $(data).find("users").length;
		membersElement.append('<li>('+total+')</li>');

		$.each($(data).find("users"), 
			function()
			{
				var name = $(this).find("name").text();
				membersElement.append('<li>'+name+'</li>');
			}
		);

		separateMemberList();
	}

	var writeSelfMessage = function(message)
	{
		var name    = escapeHTML(userName);
		var message = escapeHTML(message);

		var content = '<dl class="talk '+userIcon+'" id="'+userId+'">';
		content += '<dt>'+name+'</dt>';
		content += '<dd><div class="bubble">';
		content += '<p class="body">'+message+'</p>';
		content += '</div></dd></dl>';
		talksElement.prepend(content);
		effectBaloon();
		weepMessages();
	}

	var validateResult = function(data)
	{
		var error = $(data).find("error").text() * 1;

		if ( error == 0 || isLoggedOut )
		{
			return;
		}
		else if ( error == 1 )
		{
			isLoggedOut = true;
			alert(t("Session time out."));
		}
		else if ( error == 2 )
		{
			isLoggedOut = true;
			alert(t("Room was deleted."));
		}
		else if ( error == 3 )
		{
			isLoggedOut = true;
			alert(t("Login error."));
		}

		location.href = duraUrl;
	}

	var effectBaloon = function()
	{
		var thisBobble = $(".bubble .body:first");
		var oldWidth  = thisBobble.width()+'px';
		var oldHeight = thisBobble.height()+'px';

		ringSound();

		$("dl.talk:first dt").click(addUserNameToTextarea);

		thisBobble.css({
			'border-width' : '0px',
			'font-size' : '0px',
			'text-indent' : '-100000px',
			'opacity' : '0',
			'width': '0px',
			'height': '0px'
		});
		thisBobble.animate({ 
			'fontSize': "1em", 
			'borderWidth': "4px",
			'width': oldWidth,
			'height': oldHeight,
			'opacity': 1,
			'textIndent': 0
		}, 200, "easeInQuart", roundBaloon);
	}

	var ringSound = function()
	{
		if ( !isUseSound )
		{
			return;
		}

		if ( $(".beep_sound").length )
		{
			$(".beep_sound").remove();
		}

		if ( $("a#sound").length )
		{
			var soundUrl = $("a#sound").attr("href");

			try
			{
				$.sound.play(soundUrl);
			}
			catch(e)
			{
			}
		}
	}

	var escapeHTML = function(ch)
	{ 
		ch = ch.replace(/&/g,"&amp;");
		ch = ch.replace(/"/g,"&quot;");
		ch = ch.replace(/'/g,"&#039;");
		ch = ch.replace(/</g,"&lt;");
		ch = ch.replace(/>/g,"&gt;");
		return ch;
	}

	var enterToSubmit = function(e)
	{
		var content = textareaElement.val();
		if ( content != content.replace(/[\r\n]+/g, "") )
		{
			formElement.submit();
			return false;
		}
	}

	var logout = function()
	{
		$.post(postAction, {'logout':'logout'},
			function(result)
			{
				isLoggedOut = true;
				location.href = duraUrl;
			}
		);
	}

	var weepMessages = function()
	{
		if ( $(".talk").length > messageLimit )
		{
			while ( $(".talk").length > messageLimit )
			{
				$(".talk:last").remove();
			}
		}
	}

	var separateMemberList = function()
	{
		membersElement.find('li:not(:last)').each(
			function()
			{
				$(this).append(', ');
			}
		);
	}

	var addUserNameToTextarea = function()
	{
		var name = $(this).text();
		var text = textareaElement.val();
		textareaElement.focus();

		if ( text.length > 0 )
		{
			textareaElement.val(text+' @'+name);
		}
		else
		{
			textareaElement.val(text+'@'+name+' ');
		}
	}

	var trim = function(string)
	{
		string = string.replace(/^\s+|\s+$/g, '');
		return string;
	}

	var roundBaloons = function()
	{
		$("#talks dl.talk dd div.bubble p.body").each(roundBaloon);
	}

	var roundBaloon = function()
	{
		var isMSIE = /*@cc_on!@*/false;

		if ( !isMSIE )
		{
			return;
		}

		var width = $(this).width();
		var borderWidth = $(this).css('border-width');
		var padding = $(this).css('padding-left');
		var color = $(this).css('border-color');
		width = width + padding.replace(/px/, '') * 2;

		$(this).corner("round 10px cc:"+color)
		.parent().css({
				"background" : color,
				"padding" : borderWidth,
				"width" : width
			}).corner("round 13px");
	}

	var dump = function($val)
	{
		talksElement.prepend($val);
	}

	construct();
});