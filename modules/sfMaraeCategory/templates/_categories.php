<?php if (!empty($categories) && count($categories)): ?>
<hr class="space" />
<h3>Categories</h3>
<div id="marae-subcategories">
<?php foreach ($categories as $k => $root): ?>

<div class="prepend-1 span-22 append-1 last">
	<div class="span-18"><h4><?php echo link_to($root['name'], 'forum_show_forum', array('slug' => $root['slug'])) ?></h4>
	<p><?php echo $root['description'] ?></p>
	</div>
	<div class="span-4 last smaller center">
	<?php echo $root['threads'] ?> <?php if ($root['threads'] == 1): ?>thread<?php else: ?>threads<?php endif; ?>, <?php echo $root['replies'] ?> <?php if ($root['replies'] == 1): ?>reply<?php else: ?>replies<?php endif; ?>
	</div>
	<?php if (!empty($root['__children'])): ?>
	<div>
		<?php foreach ($root['__children'] as $child): ?>
			<div class="prepend-1 span-17">
			<h5><?php echo link_to($child['name'], 'forum_show_forum', array('slug' => $child['slug'])) ?></h5>
			<p><?php echo $child['description'] ?></p>
			
			</div>
			<div class="span-4 last smaller center">
			<?php echo $child['threads'] ?> <?php if ($child['threads'] == 1): ?>thread<?php else: ?>threads<?php endif; ?>, <?php echo $child['replies'] ?> <?php if ($child['replies'] == 1): ?>reply<?php else: ?>replies<?php endif; ?>
			</div>
		<?php endforeach; ?>
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
