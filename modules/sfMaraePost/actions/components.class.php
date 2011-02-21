<?php

class sfMaraePostComponents extends sfComponents
{
	public function executeSearch()
	{
		$form = new sfForm();
		
		$form->setWidgets(array(
			'q'	=> new sfWidgetFormInputText()
		));
		
		$form->setvalidators(array(
			'q'	=> new sfValidatorString(array('max_length' => 200))
		));
		
		$this->form = $form;
	}
}
