<?php
/**
 * Siwecos accountbox module
 *
 * @version     1.0.0
 * @package     Siwecosjoomla
 * @subpackage  accoutbox
 * @copyright   Copyright (C) 2018 eco.de
 * @license     GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die;


$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_accountbox', $params->get('layout', 'default'));
