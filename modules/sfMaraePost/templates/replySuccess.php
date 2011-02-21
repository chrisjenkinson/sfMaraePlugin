<?php slot('title', 'Reply to post') ?>

<?php include_component('sfMaraeCategory', 'breadcrumb', array('id' => $post['category_id'])) ?>

<h2>Reply to post</h2>

<?php include_partial('post_reply', array('form' => $form, 'replyId' => $post['id'])) ?>
