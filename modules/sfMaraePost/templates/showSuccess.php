<?php slot('title', sprintf('Post: %s', $root['title'])) ?>

<?php include_component('sfMaraeCategory', 'breadcrumb', array('id' => $root['category_id'])) ?>

<h2><?php echo $root['title'] ?></h2>

<?php include_partial('posts_tree', array('root' => $root, 'posts' => $posts)) ?>
