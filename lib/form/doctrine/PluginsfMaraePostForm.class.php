<?php

/**
 * PluginsfMaraePost form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginsfMaraePostForm extends BasesfMaraePostForm
{
	public function setup()
	{
		parent::setup();
		
		if ($this->getOption('isRoot', false))
		{
			$this->validatorSchema['title']->setOption('required', true);
						
			$this->useFields(array('category_id', 'message', 'title'));
		}
		else
		{
			$this->widgetSchema['parent_id'] = new sfWidgetFormInputHidden();
			
			$this->setValidator('parent_id', new sfValidatorAnd(array(
				new sfValidatorDoctrineChoice(array(
					'model' => 'sfMaraePost'
				)),
				new sfValidatorDoctrineChoiceNestedSet(array(
					'model' => 'sfMaraePost',
					'node'  => $this->getObject(),
				))
			), array('halt_on_error' => true)));
			
			$this->useFields(array('category_id', 'message', 'parent_id'));
		}
		
		$this->widgetSchema['category_id'] = new sfWidgetFormInputHidden();
		
		$this->validatorSchema['message']->setOption('min_length', 20);
	}
}
