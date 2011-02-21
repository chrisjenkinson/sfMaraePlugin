<?php slot('title', 'New post') ?>

<?php include_component('sfMaraeCategory', 'breadcrumb', array('id' => $category['id'])) ?>

<h2>New post</h2>

<?php include_partial('post_new', array('form' => $form, 'category' => $category)) ?>
