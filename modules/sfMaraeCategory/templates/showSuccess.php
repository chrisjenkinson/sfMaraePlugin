<?php slot('title', sprintf('Forum: %s', $root['name'])) ?>
<?php slot('rss', sprintf('<link rel="alternate" type="application/rss+xml" title="Latest posts" href="%s" />', url_for('@forum_show_forum?sf_format=xml&slug=' . $root['slug'], true))) ?>

<div class="span-24 last">

	<div class="quiet span-22">
		
		<?php include_component('sfMaraeCategory', 'breadcrumb', array('id' => $root['id'], 'no_link_last' => true)) ?>

		<h2><?php echo $root['name'] ?></h2>
		<h3 class="quiet"><?php echo $root['description'] ?></h3>
	</div>
	
	<div class="span-2 last">
		<span class="smaller" style="text-align: right"><a href="<?php echo url_for('@forum_show_forum?sf_format=xml&slug=' . $root['slug'], true) ?>">RSS feed</a></span>
	</div>
	
	<?php if (false): ?>
	<div id="marae-search" class="span-8 last"><?php include_component('sfMaraePost', 'search', array('category' => $root)) ?></div>
	<?php endif; ?>

</div>

<?php include_partial('categories', array('categories' => $categories)) ?>

<hr />

<?php include_partial('posts_list', array('posts' => $posts)) ?>

<hr />

<?php if ($can_post_new): ?>
<?php include_partial('sfMaraePost/post_new', array('form' => $form, 'category' => $root)) ?>
<?php endif; ?>
