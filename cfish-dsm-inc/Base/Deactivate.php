<?php
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Base;

class Deactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}