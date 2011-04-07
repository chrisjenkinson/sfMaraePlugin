<?php

require_once dirname(__FILE__).'/../lib/BasesfMaraePostActions.class.php';

/**
 * sfMaraePost actions.
 * 
 * @package    sfMaraePlugin
 * @subpackage sfMaraePost
 * @author     Chris Jenkinson
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class sfMaraePostActions extends BasesfMaraePostActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->redirect($this->generateUrl('forum'));
	}
	
	public function executeShow(sfWebRequest $request)
	{
		$post = $this->getRoute()->getObject();
		
		$this->category = sfMaraeCategoryTable::getInstance()->findOneById($post['category_id']);
		
		if (!$this->checkPermission('show', $this->category['id']))
		{
			$this->getUser()->setFlash('error', 'You do not have permission to view threads in this category.');
			$this->redirect($this->generateUrl('forum_show_forum', array('slug' => $this->category['slug'])));
		}
		
		$tree = $post->getTree();
		$node = $post->getNode();
		
		if (!$node->isRoot())
		{
			$root = $tree->fetchRoot($node->getRootValue());
			$this->redirect($this->generateUrl('post_show_post', array('id' => $root['id'], 'slug' => $root['slug'])));
		}
		
		$this->posts = $post->getNode()->getDescendants();
		$this->root = $post;
	}
	
	public function executeSearch(sfWebRequest $request)
	{
	}
	
	public function executeVoteUp(sfWebRequest $request)
	{
		return $this->processVote($request, 1);
	}
	
	public function executeVoteDown(sfWebRequest $request)
	{
		return $this->processVote($request, -1);
	}
	
	protected function processVote(sfWebRequest $request, $value)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->getUser()->setFlash('warning', 'You must be signed in to do that!');
			$this->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
		}
		
		$post = $this->getRoute()->getObject();
		
		$this->category = sfMaraeCategoryTable::getInstance()->findOneById($post['category_id']);
		
		if (!$this->checkPermission('show', $this->category['id']))
		{
			$this->getUser()->setFlash('error', 'You do not have permission to view threads in this category.');
			$this->redirect($this->generateUrl('forum_show_forum', array('slug' => $this->category['slug'])));
		}
		
		$tree = $post->getTreeObject();
		$node = $post->getNode();
		
		$root = $tree->fetchRoot($node->getRootValue());
		
		$pv = new sfMaraePostVote();
		
		if (sfMaraePostVote::hasUserVoted($post['sfMaraePostVote'], $this->getUser()->getId()))
		{
			$pv->assignIdentifier(array(
				'post_id'	=> $post['id'],
				'user_id'	=> $this->getUser()->getId()
			));
		}
		else
		{
			$pv->setPostId($post['id']);
			$pv->setUserId($this->getUser()->getId());
		}
		
		$pv->setVote($value);
		
		$pv->save();
		
		if ($request->isXmlHttpRequest())
		{
			$postVote = sfMaraePostVoteTable::getInstance()->findByPostId($post['id']);
			$rating = sfMaraePostVote::getAverageRating($postVote);
				
			return $this->renderText(json_encode(array(
				'opacity'			=> sfMaraePost::getOpacity($rating),
				'backgroundColor'	=> sfMaraePost::getColor($rating)
			)));
		}
		
		$this->redirect($this->generateUrl('post_show_post', array(
			'id'		=> $root['id'],
			'slug'	=> $root['slug']
		)) . '#marae-post-' . $post['id']);
	}
	
	protected function newAndCreateNew()
	{
		$this->category = $this->getRoute()->getObject();
		
		if (!$this->checkPermission('new', $this->category['id']))
		{
			$this->getUser()->setFlash('error', 'You do not have permission to start new threads in this category.');
			$this->redirect($this->generateUrl('forum_show_forum', array('slug' => $this->category['slug'])));
		}
		
		$this->form = new sfMaraePostForm(null, array('isRoot' => true));
	}
	
	public function executeNew(sfWebRequest $request)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->getUser()->setFlash('warning', 'You must be signed in to do that!');
			$this->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
		}
		
		$this->newAndCreateNew();
	}
	
	public function executeCreateNew(sfWebRequest $request)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->redirect($this->generateUrl('forum'));
		}
		
		$this->newAndCreateNew();
		
		$this->processForm($request, $this->form);

		$this->setTemplate('new');
	}
	
	public function replyAndCreateReply()
	{
		$this->post = $this->getRoute()->getObject();
		
		$this->category = sfMaraeCategoryTable::getInstance()->findOneById($this->post['category_id']);
		
		if (!$this->checkPermission('reply', $this->category['id']))
		{
			$this->getUser()->setFlash('error', 'You do not have permission to reply to posts in this category.');
			$this->redirect($this->generateUrl('forum_show_forum', array('slug' => $this->category['slug'])));
		}
	}
	
	public function executeReply(sfWebRequest $request)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->getUser()->setFlash('warning', 'You must be signed in to do that!');
			$this->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
		}
		
		$this->replyAndCreateReply();
		
		$reply = new sfMaraePost();
		
		$reply->setRootId($this->post['root_id']);
		$reply->setCategoryId($this->post['category_id']);
		
		$this->form = new sfMaraePostForm($reply);
		
		$this->form->setDefault('parent_id', $this->post['id']);
		
		if ($request->isXmlHttpRequest())
		{
			return $this->renderPartial('post_reply', array('form' => $this->form, 'replyId' => $this->post['id']));
		}
	}
	
	public function executeCreateReply(sfWebRequest $request)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->redirect($this->generateUrl('forum'));
		}
		
		$this->replyAndCreateReply();
		
		$this->form = new sfMaraePostForm();
		
		$this->processForm($request, $this->form);
		
		$this->setTemplate('reply');
	}
	
	protected function editAndUpdate()
	{
		$post = $this->getRoute()->getObject();
		
		$this->category = sfMaraeCategoryTable::getInstance()->findOneById($post['category_id']);
		
		if (!$this->checkPermission('edit', $this->category['id']))
		{
			$this->getUser()->setFlash('error', 'You do not have permission to edit posts in this category.');
			$this->redirect($this->generateUrl('forum_show_forum', array('slug' => $this->category['slug'])));
		}
		
		$tree = $post->getTree();
		$node = $post->getNode();
		
		$root = $tree->fetchRoot($node->getRootValue());
		
		$parent = $node->getParent();
		
		if ($post['user_id'] != $this->getUser()->getId())
		{
			$this->getUser()->setFlash('error', "You cannot edit another person's post!");
			$this->redirect($this->generateUrl('post_show_post', array(
				'id'		=> $root['id'],
				'slug'	=> $root['slug']
			)));
		}
		
		$this->post = $post;
		
		$this->form = new sfMaraePostForm($post, array('isRoot' => $node->isRoot()));
		$this->form->setDefault('parent_id', $parent['id']);
	}
	
	public function executeEdit(sfWebRequest $request)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->getUser()->setFlash('warning', 'You must be signed in to do that!');
			$this->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
		}
		
		$this->editAndUpdate();
	}
	
	public function executeUpdate(sfWebRequest $request)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->redirect($this->generateUrl('forum'));
		}
		
		$this->editAndUpdate();
		
		$this->processForm($request, $this->form);
		
		$this->setTemplate('edit');
	}
	
	public function executeDelete(sfWebRequest $request)
	{
		if (!$this->getUser()->isAuthenticated())
		{
			$this->redirect($this->generateUrl('forum'));
		}
		
		$request->checkCSRFProtection();
		
		$post = $this->getRoute()->getObject();
		
		$this->category = sfMaraeCategoryTable::getInstance()->findOneById($post['category_id']);
		
		if (!$this->checkPermission('delete', $this->category['id']))
		{
			$this->getUser()->setFlash('error', 'You do not have permission to delete posts in this category.');
			$this->redirect($this->generateUrl('forum_show_forum', array('slug' => $this->category['slug'])));
		}
		
		$tree = $post->getTreeObject();
		$node = $post->getNode();
		
		$root = $tree->fetchRoot($node->getRootValue());
		
		if ($post['user_id'] != $this->getUser()->getId())
		{
			$this->getUser()->setFlash('error', "You cannot delete another person's post!");
			$this->redirect($this->generateUrl('post_show_post', array(
				'id'		=> $root['id'],
				'slug'	=> $root['slug']
			)));
		}
		
		if ($node->hasChildren())
		{
			$this->getUser()->setFlash('error', "You cannot delete a post which has replies.");
			$this->redirect($this->generateUrl('post_show_post', array(
				'id'		=> $root['id'],
				'slug'	=> $root['slug']
			)));
		}
		
		if ($node->isRoot())
		{
			$forum = sfMaraeCategoryTable::getInstance()->findOneById($post['category_id']);
			
			$node->delete();
			
			$this->redirect($this->generateUrl('forum_show_forum', array(
				'slug'	=> $forum['slug']
			)));
		}
		else
		{
			$post->getNode()->delete();
			
			$this->redirect($this->generateUrl('post_show_post', array(
				'id'		=> $root['id'],
				'slug'	=> $root['slug']
			)));
		}
	}
	
	protected function processForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind(
			$request->getParameter($form->getName()),
			$request->getFiles($form->getName())
		);
		
		if ($form->isValid())
		{
			$form->updateObject();

			$post = $form->getObject();
			
			$tree = $post->getTree();
			$node = $post->getNode();
			
			if ($node->isValidNode())
			{
				$post->save();
				
				$this->redirect($this->generateUrl('post_show_post', array(
					'id'		=> $post['id'],
					'slug'	=> $post['slug']
				)) . '#marae-post-' . $post['id']);
			}
			else
			{
				$post->setUserId($this->getUser()->getId());
				
				$parentId = $form->getValue('parent_id');
				
				if ($parentId)
				{
					$node->insertAsLastChildOf(sfMaraePostTable::getInstance()->find($parentId));
					
					$pv = new sfMaraePostVote();
					
					$pv->setPostId($post['id']);
					$pv->setUserId($this->getUser()->getId());
					$pv->setVote(1);
					
					$pv->save();
					
					$root = $tree->fetchRoot($node->getRootValue());
					
					$this->redirect($this->generateUrl('post_show_post', array(
						'id'		=> $root['id'],
						'slug'	=> $root['slug']
					)) . '#marae-post-' . $post['id']);
				}
				else
				{
					$node->setRootValue($post->getMaxRoot()+1);
					$tree->createRoot($post);
					
					$pv = new sfMaraePostVote();
					
					$pv->setPostId($post['id']);
					$pv->setUserId($this->getUser()->getId());
					$pv->setVote(1);
					
					$pv->save();
					
					$this->redirect($this->generateUrl('post_show_post', array(
						'id'		=> $post['id'], 
						'slug'	=>  $post['slug']
					)));
				}
			}
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
