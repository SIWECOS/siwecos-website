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
<div class="accountbox" id="accountbox">
    <ul>
        <li class="accountbox__link accountbox__link--loggedoff">
            <a href="<?php echo JURI::base(); ?>app/#/login">Anmelden</a>
        </li>
        <li class="accountbox__link accountbox__link--loggedoff">
            <a href="<?php echo JURI::base(); ?>app/#/register">Registrieren</a>
        </li>
        <li class="accountbox__link accountbox__link--loggedin">
            <a href="<?php echo JURI::base(); ?>app/#/account">Meine Daten</a>
        </li>
        <li class="accountbox__link accountbox__link--loggedin">
            <a href="<?php echo JURI::base(); ?>app/#/domains">Domainübersicht</a>
        </li>
        <li class="accountbox__link accountbox__link--loggedin">
            <a id="accountbox__logoff" href="<?php echo JURI::base(); ?>app">Abmelden</a>
        </li>
    </ul>
</div>
