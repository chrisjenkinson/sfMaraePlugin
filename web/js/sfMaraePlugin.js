$(document).ready(function()
{
	var _id, _votedirection, _voteotherdirection, _castvote;
	
	_castvote = function()
	{
		$('#marae-post-vote' + _votedirection + '-' + _id + ' a').hide();
		$('#marae-post-vote' + _votedirection + '-' + _id).prepend('<span class="voting">voting...</span>');
		
		$.getJSON($('#marae-post-vote' + _votedirection + '-' + _id + ' a').attr('href'), function (data)
		{
			$('#marae-post-vote' + _votedirection + '-' + _id).addClass('voted');
			$('#marae-post-vote' + _votedirection + '-' + _id + ' span').removeClass('voting').text('voted ' + _votedirection);
			
			$('#marae-post-vote' + _voteotherdirection + '-' + _id).removeClass('voted');
			$('#marae-post-vote' + _voteotherdirection + '-' + _id + ' a').show();
			$('#marae-post-vote' + _voteotherdirection + '-' + _id + ' span').remove();
			
			$('#marae-post-' + _id).animate({
				opacity: data.opacity,
				backgroundColor: data.backgroundColor
			}, 'slow');
		});
	};
	
	$("[id^=marae-post-voteup-].votedup a").hide();
	$('[id^=marae-post-voteup-].votedup').removeClass('votedup').prepend('<span>voted up</span>');
	
	$("[id^=marae-post-votedown-].voteddown a").hide();
	$('[id^=marae-post-votedown-].voteddown').removeClass('voteddown').prepend('<span>voted down</span>');
	
	$("[id^=marae-post-voteup-] a").live('click', function(event)
	{
		event.preventDefault();
		
		_votedirection = 'up';
		_voteotherdirection = 'down';
		_id = $(this).parent().attr('id').split("-")[3];
		
		_castvote();
	});
	
	$("[id^=marae-post-votedown-] a").live('click', function(event)
	{
		event.preventDefault();
		
		_votedirection = 'down';
		_voteotherdirection = 'up';
		_id = $(this).parent().attr('id').split("-")[3];
		
		_castvote();
	});
});
