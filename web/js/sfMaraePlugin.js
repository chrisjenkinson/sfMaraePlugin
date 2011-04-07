$(document).ready(function()
{
	var _id, _voteDirection, _voteOtherDirection, _castVote, _openReply, _closeReply;
	
	_castVote = function()
	{
		var $_voteDirection, $_voteOtherDirection;
		
		$_voteDirection = $('#marae-post-vote' + _voteDirection + '-' + _id);
		$_voteOtherDirection = $('#marae-post-vote' + _voteOtherDirection + '-' + _id);
		
		$('a', $_voteDirection).hide();
		$_voteDirection.prepend('<span class="voting">voting...</span>');
		
		$.getJSON($('a', $_voteDirection).attr('href'), function (data)
		{
			$_voteDirection.addClass('voted');
			$('span', $_voteDirection).removeClass('voting').text('voted ' + _voteDirection);
			
			$_voteOtherDirection.removeClass('voted');
			$('a', $_voteOtherDirection).show();
			$('span', $_voteOtherDirection).remove();
			
			$('#marae-post-' + _id).animate({
				opacity: data.opacity,
				backgroundColor: data.backgroundColor
			}, 'slow');
		});
	};
	
	_openReply = function()
	{
		var $_replyContainer, $_cancel, $_prependHtml, $_data;
		
		$_replyContainer = $("#marae-post-container-" + _id);
		$_cancel = $('<div><a id="marae-post-reply-close-' + _id + '" href="' + window.location.pathname + '">close</a></div>');
		$_prependHtml = $('<li id="marae-post-reply-box-' + _id + '"><div class="span-14"><div class="marae-post">Opening reply box... ' + $_cancel.html() + '</div></div></li>');
		
		$('#marae-post-reply-open-' + _id + ' a').before('<span class="replying">replying...</span>');
		$('#marae-post-reply-open-' + _id + ' a').hide();
		
		if (!$('ol', $_replyContainer).length)
		{
			$_replyContainer.append('<ol></ol>');
		}
		
		$_prependHtml.hide();
		
		$('> ol', $_replyContainer).prepend($_prependHtml);
		
		$_prependHtml.fadeIn('slow');
		
		$.get($("#marae-post-reply-open-" + _id + ' a').attr('href'), function (data)
		{
			$_data = $(data);
			$("div", $_data).unwrap();
			$("legend", $_data).remove();
			$(":submit", $_data).after(' ' + $_cancel.html());
			$_data.hide();
			
			$('#marae-post-reply-box-' + _id + ' div div', $_replyContainer).html($_data);
			
			$_data.fadeIn('slow');
		});
	};
	
	_closeReply = function()
	{
		$('#marae-post-reply-open-' + _id + ' a').show();
		$('#marae-post-reply-open-' + _id + ' .replying').remove();
		
		$("#marae-post-reply-box-" + _id).fadeOut('slow', function()
		{
			$(this).remove();
		});
	};
	
	$("[id^=marae-post-voteup-].votedup a").hide();
	$('[id^=marae-post-voteup-].votedup').removeClass('votedup').prepend('<span>voted up</span>');
	
	$("[id^=marae-post-votedown-].voteddown a").hide();
	$('[id^=marae-post-votedown-].voteddown').removeClass('voteddown').prepend('<span>voted down</span>');
	
	$("[id^=marae-post-voteup-] a").live('click', function(event)
	{
		event.preventDefault();
		
		_voteDirection = 'up';
		_voteOtherDirection = 'down';
		_id = $(this).parent().attr('id').split("-")[3];
		
		_castVote();
	});
	
	$("[id^=marae-post-votedown-] a").live('click', function(event)
	{
		event.preventDefault();
		
		_voteDirection = 'down';
		_voteOtherDirection = 'up';
		_id = $(this).parent().attr('id').split("-")[3];
		
		_castVote();
	});
	
	$("[id^=marae-post-reply-open-] a").live('click', function(event)
	{
		event.preventDefault();
		
		_id = $(this).parent().attr('id').split("-")[4];
		
		_openReply();
	});
	
	$("[id^=marae-post-reply-close-]").live('click', function(event)
	{
		event.preventDefault();
		
		_id = $(this).attr('id').split("-")[4];
		
		_closeReply();
	});
});
