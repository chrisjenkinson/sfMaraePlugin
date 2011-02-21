<?php use_helper('Date') ?>
<?php if (!empty($posts)): ?>

<h3>Posts</h3>

<table>
	<thead>
		<tr>
			<th class="span-20">Title</th>
			<th class="center span-4">Replies</th>
		</tr>
	</thead>
	
	<tbody>
		<?php if (count($posts)): ?>
		<?php foreach ($posts as $root): ?>
		<tr>
			<td>
				<div class="marae-post-title"><?php echo link_to($root['title'], 'post_show_post', array('id' => $root['id'], 'replies' => (($root['rgt'] - $root['lft'] - 1)/2))) ?></div>
				<div class="marae-post-about quiet smaller">posted by <strong><?php echo $root['sfGuardUser']['username'] ?></strong> <?php echo time_ago_in_words(strtotime($root['created_at']), true) ?> ago</div>
			</td>
			<td class="center">
				<div class="marae-post-replies quiet"><?php echo (($root['rgt'] - $root['lft'] - 1)/2) ?></div>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php else: ?>
		<tr>
			<td colspan="2" class="center"><div class="prepend-top">There are no posts yet - why not be the first?</div></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>

<hr class="space" />

<?php endif; ?>
