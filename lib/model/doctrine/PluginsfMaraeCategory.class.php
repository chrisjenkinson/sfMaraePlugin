<?php

/**
 * PluginsfMaraeCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class PluginsfMaraeCategory extends BasesfMaraeCategory
{
	public function getTree()
	{
		$o = Doctrine_Core::getTable('sfMaraeCategory')->getTree();
		$o->setBaseQuery(self::baseQuery());
		
		return $o;
	}
	
	public static function baseQuery()
	{
		return Doctrine_Query::create()
			->select('c.name, c.description, c.slug, (SELECT COUNT(p.id) FROM sfMaraePost p WHERE p.category_id = c.id AND p.lft = 1) AS threads, 
				(SELECT COUNT(p2.id) FROM sfMaraePost p2 WHERE p2.category_id = c.id AND p2.lft <> 1) as replies')
			->from('sfMaraeCategory c')
			->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
	}
}
