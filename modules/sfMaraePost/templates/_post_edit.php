<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php echo form_tag_for($form, '@post') ?>
<?php echo $form->renderHiddenFields() ?>

<fieldset>
	<legend>Edit post</legend>
	
	<?php if ($form->hasGlobalErrors()): ?>
	<div class="error">
		<?php echo $form->renderGlobalErrors() ?>
	</div>
	<?php endif; ?>
	
	<?php if ($form->getOption('isRoot')): ?>
	<div class="append-bottom<?php if ($form['title']->hasError()): ?> error<?php endif; ?>">
		<?php echo $form['title']->renderLabel() ?><br />
		<?php echo $form['title']->render(array('class' => 'text')) ?><br />
		<span class="help"><?php echo $form['title']->renderHelp() ?></span>
		<?php echo $form['title']->renderError() ?>
	</div>
	<?php endif; ?>
	
	<div class="append-bottom<?php if ($form['message']->hasError()): ?> error<?php endif; ?>">
		<?php echo $form['message']->renderLabel() ?><br />
		<?php echo $form['message']->render() ?><br />
		<span class="help"><?php echo $form['message']->renderHelp() ?></span>
		<?php echo $form['message']->renderError() ?>
	</div>
	
	<hr />
	
	<input type="submit" value="Say" />
	
</fieldset>
	
</form>
