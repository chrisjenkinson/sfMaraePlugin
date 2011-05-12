<?php use_helper('Date') ?>
<?php use_helper('XssSafe') ?>

<?php $postPurified = $sf_data->get('post', ESC_XSSSAFE) ?>
<?php $rating = sprintf("%.2f", sfMaraePostVote::getAverageRating($post['sfMaraePostVote'])) ?>
<?php $opacity = sfMaraePost::getOpacity($rating) ?>
<?php $color = sfMaraePost::getColor($rating) ?>
<?php $hasVotedUp = ($sf_user->isAuthenticated() ? sfMaraePostVote::hasUserVoted($post['sfMaraePostVote'], $sf_user->getId(), sfMaraePostVote::VOTE_UP) : false) ?>
<?php $hasVotedDown = ($sf_user->isAuthenticated() ? sfMaraePostVote::hasUserVoted($post['sfMaraePostVote'], $sf_user->getId(), sfMaraePostVote::VOTE_DOWN) : false) ?>

<li id="marae-post-container-<?php echo $post['id'] ?>">

<div class="span-14">
	<div id="marae-post-<?php echo $post['id'] ?>" class="marae-post" style="opacity: <?php echo $opacity ?>; background-color: <?php echo $color ?>">
		<span class="marae-post-info quiet">
			posted by <strong><?php echo $post['sfGuardUser']['username'] ?></strong> 
			<span title="<?php echo format_datetime($post['created_at']) ?>"><?php echo time_ago_in_words(strtotime($post['created_at'])) ?> ago</span>
			<?php if ($post['created_at'] !== $post['updated_at']): ?>
				- last updated <span title="<?php echo format_datetime($post['updated_at']) ?>"><?php echo time_ago_in_words(strtotime($post['updated_at'])) ?> ago</span>
			<?php endif; ?>
		</span>
		
		<hr />
		
		<div class="marae-post-message"><?php echo $postPurified['message'] ?></div>
		
		<ul class="marae-post-options quiet">
			<li><?php echo link_to('permalink', sprintf('@post_show_post?id=%d&slug=%s', $root['id'], $root['slug']), array('anchor' => 'marae-post-' . $post['id'])) ?></li>
			<?php if (!empty($parent_id)): ?>
				<li><a href="#marae-post-<?php echo $parent_id ?>">parent</a></li>
			<?php endif; ?>
			<?php if ($sf_user->isAuthenticated()): ?>
				<li id="marae-post-reply-open-<?php echo $post['id'] ?>"><?php echo link_to('reply', '@post_reply?id=' . $post['id']) ?></li>
				<?php if ($post['user_id'] == $sf_user->getId()): ?>
					<li><?php echo link_to('edit', '@post_edit?id=' . $post['id']) ?></li>
					<?php if (!(!empty($children) && count($children))): ?>
						<li><?php echo link_to('delete', '@post_delete?id=' . $post['id'], array('post' => true, 'method' => 'delete')) ?></li>
					<?php endif; ?>
				<?php else: ?>
				<?php endif; ?>
				<li id="marae-post-voteup-<?php echo $post['id'] ?>"<?php if ($hasVotedUp): ?> class="votedup"<?php endif; ?>><?php echo link_to('vote up', '@post_vote_up?id=' . $post['id']) ?></li>
				<li id="marae-post-votedown-<?php echo $post['id'] ?>"<?php if ($hasVotedDown): ?> class="voteddown"<?php endif; ?>><?php echo link_to('vote down', '@post_vote_down?id=' . $post['id']) ?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>

<?php if (!empty($children)): ?>
	<ol>
		<?php foreach ($children as $child): ?>
			<?php include_partial('post_single', array('root' => $root, 'post' => $child, 'children' => $child['__children'], 'parent_id' => $post['id'])) ?>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

</li>
