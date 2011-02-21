<?php slot('title', 'Edit post') ?>

<?php include_component('sfMaraeCategory', 'breadcrumb', array('id' => $post['category_id'])) ?>

<h2>Edit post</h2>

<?php include_partial('post_edit', array('form' => $form)) ?>
