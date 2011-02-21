<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('@post_search' . (!empty($category) ? '?slug=' . $category['slug'] : '')) ?>" method="get">
<?php echo $form->renderHiddenFields() ?>

<?php echo $form['q']->render(array('class' => 'text')) ?>

<input type="submit" value="Search" />

</form>
