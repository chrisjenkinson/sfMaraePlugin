<?php

require_once dirname(__FILE__).'/../lib/BasesfMaraeCategoryActions.class.php';

/**
 * sfMaraeCategory actions.
 * 
 * @package    sfMaraePlugin
 * @subpackage sfMaraeCategory
 * @author     Chris Jenkinson
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class sfMaraeCategoryActions extends BasesfMaraeCategoryActions
{
	static $permissions, $user;
	
	public function executeIndex(sfWebRequest $request)
	{
		if (!$this->checkPermission('index', 0))
		{
			
		}
		
		$c = new sfMaraeCategory();
		
		$tree = $c->getTree();
		
		$this->roots = $tree->fetchRoots();
		
		$rootIds = array();
		
		foreach ($this->roots as $root)
		{
			$rootIds[] = $root['id'];
		}
		
		$this->categories = $tree->fetchTree(array('root_id' => $rootIds));
	}
	
	public function executeShow(sfWebRequest $request)
	{
		$this->root = $this->getRoute()->getObject();
		
		if (!$this->checkPermission('show', $this->root['id']))
		{
			$this->getUser()->setFlash('error', 'You do not have permission to view that forum.');
			$this->redirect($this->generateUrl('forum'));
		}
		
		$this->ancestors = $this->root->getNode()->getAncestors();
		$this->categories = $this->root->getNode()->getDescendants();
		
		$post = new sfMaraePost();
		$post->setCategoryId($this->root['id']);
		
		$postTree = $post->getTree();
		
		$post->orderByCreatedAt();
		
		$this->posts = $postTree->fetchRoots();
		
		if ($this->getUser()->isAuthenticated())
		{
			$this->form = new sfMaraePostForm($post, array('isRoot' => true));
		}
	}
	
	public function checkPermission($action, $id = 0)
	{
		$logger = $this->getContext()->getLogger();
		
		$this->user = $this->getUser()->getGuardUser();
		$permissionName = sprintf('sfMaraeCategory_%d_%s', $id, $action);
		
		$this->permissions = sfGuardPermissionTable::getInstance();
		
		if ($this->permissions->findOneByName($permissionName))
		{
			if (!($this->user instanceof sfGuardUser))
			{
				$logger->info(sprintf('checking %s - permission defined, user not logged in', $permissionName));
				return false;
			}
			if ($this->user->hasPermission($permissionName))
			{
				$logger->info(sprintf('checking %s - permission defined, user %d has', $permissionName, $this->user->getId()));
				return true;
			}
			
			$logger->info(sprintf('checking %s - permission defined, user %d does not have', $permissionName, $this->user->getId()));
			return false;
		}
		
		$logger->info(sprintf('checking %s - no permissions defined', $permissionName));
		
		return true;
	}
}