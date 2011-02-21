<?php use_helper('Date') ?>
<?php use_helper('XssSafe') ?>

<?php $rootPurified = $sf_data->get('root', ESC_XSSSAFE) ?>

<li>

<div class="span-14">
	<div id="marae-post-<?php echo $root['id'] ?>" class="marae-post">
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
			<li><?php echo link_to('permalink', '@post_show_post?id=' . $root['id'], array('anchor' => 'marae-post-' . $root['id'])) ?></li>
			<?php if (!empty($parent_id)): ?>
				<li><a href="#marae-post-<?php echo $parent_id ?>">parent</a></li>
			<?php endif; ?>
			<li><?php echo link_to('reply', '@post_reply?id=' . $root['id']) ?></li>
			<?php if ($sf_user->isAuthenticated() && $root['user_id'] == $sf_user->getId()): ?>
				<li><?php echo link_to('edit', '@post_edit?id=' . $root['id']) ?></li>
				<li><?php echo link_to('delete', '@post_delete?id=' . $root['id'], array('post' => true, 'method' => 'delete')) ?></li>
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
