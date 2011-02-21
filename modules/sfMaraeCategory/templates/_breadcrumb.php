<span class="marae-breadcrumb smaller">
<?php echo link_to('Index', 'forum') ?>
 &raquo;
<?php if (!empty($ancestors)): ?>
	<?php foreach ($ancestors as $ancestor): ?>
		<?php echo link_to($ancestor['name'], '@forum_show_forum?slug=' . $ancestor['slug']) ?>
	 &raquo;
	<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($no_link_last) && $no_link_last): ?>
	<?php echo $category['name'] ?>
<?php else: ?>
	<?php echo link_to($category['name'], '@forum_show_forum?slug=' . $category['slug']) ?> &raquo;
<?php endif; ?>
</span>
