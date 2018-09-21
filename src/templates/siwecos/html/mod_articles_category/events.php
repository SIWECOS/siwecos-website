<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<ul class="eventmodule<?php echo $moduleclass_sfx; ?>">
    <?php foreach ($list as $item) : ?>
        <?php
        $customFields = FieldsHelper::getFields('com_content.article', $item);

        foreach ($customFields as $customField)
        {
            $customFields[$customField->name] = $customField;
        }
        ?>
        <li>
            <a class="eventmodule__title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
            <dl class="eventmodule__facts">
                <dt class="visuallyhidden">
                    <?php echo JText::_('TPL_SIWECOS_EVENTS_DATE'); ?>
                </dt>
                <dd>
                    <?php echo JFactory::getDate($customFields["datum-von"]->value)->format("d.m.Y"); ?>

                    <?php if($customFields["datum-bis"]->value && JFactory::getDate($customFields["datum-von"]->value)->format("d.m.Y") !== JFactory::getDate($customFields["datum-bis"]->value)->format("d.m.Y")): ?>
                        - <?php echo JFactory::getDate($customFields["datum-bis"]->value)->format("d.m.Y"); ?>
                    <?php endif; ?>
                </dd>
                <dt class="visuallyhidden">
                    <?php echo JText::_('TPL_SIWECOS_EVENTS_LOCATION'); ?>
                </dt>
                <dd>
                    <?php echo $customFields["veranstaltungsort"]->value; ?>
                </dd>
            </dl>
        </li>
    <?php endforeach; ?>
</ul>
