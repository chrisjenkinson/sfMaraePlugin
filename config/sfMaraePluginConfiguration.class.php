<?php

/**
 * sfMaraePlugin configuration.
 * 
 * @package     sfMaraePlugin
 * @subpackage  config
 * @author      Chris Jenkinson
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class sfMaraePluginConfiguration extends sfPluginConfiguration
{
	const VERSION = '1.0.0-DEV';
	static protected $HTMLPurifierLoaded = false;
	
	/**
	 * @see sfPluginConfiguration
	 */
	public function initialize()
	{
	}
}
