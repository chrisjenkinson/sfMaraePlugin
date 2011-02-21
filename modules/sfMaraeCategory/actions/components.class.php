<?php

class sfMaraeCategoryComponents extends sfComponents
{
	public function executeBreadcrumb()
	{
		$category = new sfMaraeCategory();
		
		$category->assignIdentifier(array('id' => $this->id));
		
		$node = $category->getNode();
		
		$this->ancestors = $node->getAncestors();
		
		$this->category = $category;
	}
}
