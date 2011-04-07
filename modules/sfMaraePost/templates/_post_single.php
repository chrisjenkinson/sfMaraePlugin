<?php use_helper('Date') ?>
<?php use_helper('XssSafe') ?>

<?php $rootPurified = $sf_data->get('root', ESC_XSSSAFE) ?>
<?php $rating = sprintf("%.2f", sfMaraePostVote::getAverageRating($root['sfMaraePostVote'])) ?>
<?php $opacity = sfMaraePost::getOpacity($rating) ?>
<?php $color = sfMaraePost::getColor($rating) ?>
<?php $hasVotedUp = ($sf_user->isAuthenticated() ? sfMaraePostVote::hasUserVoted($root['sfMaraePostVote'], $sf_user->getId(), sfMaraePostVote::VOTE_UP) : false) ?>
<?php $hasVotedDown = ($sf_user->isAuthenticated() ? sfMaraePostVote::hasUserVoted($root['sfMaraePostVote'], $sf_user->getId(), sfMaraePostVote::VOTE_DOWN) : false) ?>

<li id="marae-post-container-<?php echo $root['id'] ?>">

<div class="span-14">
	<div id="marae-post-<?php echo $root['id'] ?>" class="marae-post" style="opacity: <?php echo $opacity ?>; background-color: <?php echo $color ?>">
		<span class="marae-post-info quiet">
			posted by <strong><?php echo $root['sfGuardUser']['username'] ?></strong> 
			<span title="<?php echo format_datetime($root['created_at']) ?>"><?php echo time_ago_in_words(strtotime($root['created_at'])) ?> ago</span>
			<?php if ($root['created_at'] !== $root['updated_at']): ?>
				- last updated <span title="<?php echo format_datetime($root['updated_at']) ?>"><?php echo time_ago_in_words(strtotime($root['updated_at'])) ?> ago</span>
			<?php endif; ?>
		</span>
		
		<hr />
		
		<div class="marae-post-message"><?php echo $rootPurified['message'] ?></div>
		
		<ul class="marae-post-options quiet">
			<li><?php echo link_to('permalink', sprintf('@post_show_post?id=%d&slug=%s', $root['id'], $root['slug']), array('anchor' => 'marae-post-' . $root['id'])) ?></li>
			<?php if (!empty($parent_id)): ?>
				<li><a href="#marae-post-<?php echo $parent_id ?>">parent</a></li>
			<?php endif; ?>
			<?php if ($sf_user->isAuthenticated()): ?>
				<li id="marae-post-reply-open-<?php echo $root['id'] ?>"><?php echo link_to('reply', '@post_reply?id=' . $root['id']) ?></li>
				<?php if ($root['user_id'] == $sf_user->getId()): ?>
					<li><?php echo link_to('edit', '@post_edit?id=' . $root['id']) ?></li>
					<?php if (!(!empty($children) && count($children))): ?>
						<li><?php echo link_to('delete', '@post_delete?id=' . $root['id'], array('post' => true, 'method' => 'delete')) ?></li>
					<?php endif; ?>
				<?php else: ?>
				<?php endif; ?>
				<li id="marae-post-voteup-<?php echo $root['id'] ?>"<?php if ($hasVotedUp): ?> class="votedup"<?php endif; ?>><?php echo link_to('vote up', '@post_vote_up?id=' . $root['id']) ?></li>
				<li id="marae-post-votedown-<?php echo $root['id'] ?>"<?php if ($hasVotedDown): ?> class="voteddown"<?php endif; ?>><?php echo link_to('vote down', '@post_vote_down?id=' . $root['id']) ?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>

<?php if (!empty($children)): ?>
	<ol>
		<?php foreach ($children as $child): ?>
			<?php include_partial('post_single', array('root' => $child, 'children' => $child['__children'], 'parent_id' => $root['id'])) ?>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

</li>
