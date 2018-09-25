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
defined('_JEXEC') or die; ?>
<div class="accountbox <?php echo $moduleclassSfx; ?>" id="accountbox">
	<ul>
		<li class="accountbox__link accountbox__link--loggedoff">
			<a href="<?php echo JURI::base(); ?>app/#/login"><?php echo JText::_('MOD_ACCOUNTBOX_LOGIN'); ?></a>
		</li>
		<li class="accountbox__link accountbox__link--loggedoff">
			<a href="<?php echo JURI::base(); ?>app/#/register"><?php echo JText::_('MOD_ACCOUNTBOX_REGISTER'); ?></a>
		</li>
		<li class="accountbox__link accountbox__link--loggedin">
			<a href="<?php echo JURI::base(); ?>app/#/account"><?php echo JText::_('MOD_ACCOUNTBOX_ACCOUNT'); ?></a>
		</li>
		<li class="accountbox__link accountbox__link--loggedin">
			<a href="<?php echo JURI::base(); ?>app/#/domains"><?php echo JText::_('MOD_ACCOUNTBOX_OVERVIEW'); ?></a>
		</li>
		<li class="accountbox__link accountbox__link--loggedin">
			<a id="accountbox__logoff" href="<?php echo JURI::base(); ?>app"><?php echo JText::_('MOD_ACCOUNTBOX_LOGOUT'); ?></a>
		</li>
	</ul>
</div>
