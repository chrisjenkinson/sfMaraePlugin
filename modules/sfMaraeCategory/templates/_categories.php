<?php if (!empty($categories)): ?>
<h3>Categories</h3>
<div id="marae-subcategories">
<?php foreach ($categories as $k => $root): ?>

<div class="prepend-1 span-22 append-1 last">
	<h4><?php echo link_to($root['name'], 'forum_show_forum', array('slug' => $root['slug'])) ?></h4>
	<p><?php echo $root['description'] ?></p>
	<?php if (!empty($root['__children'])): ?>
	<div>
		<ul>
		<?php foreach ($root['__children'] as $child): ?>
			<li><h5><?php echo link_to($child['name'], 'forum_show_forum', array('slug' => $child['slug'])) ?></h5>
			<p><?php echo $child['description'] ?></p></li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
	<?php if (!empty($categories[$k+1])): ?>
	<hr />
	<?php endif; ?>
</div>

<?php endforeach; ?>
</div>
<hr class="space" />
<?php endif; ?>
