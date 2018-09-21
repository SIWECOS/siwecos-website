<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Create some shortcuts.
$params    = &$this->item->params;
$n         = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>

<?php if (empty($this->items)) : ?>
    <?php if ($this->params->get('show_no_articles', 1)) : ?>
        <p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
    <?php endif; ?>
<?php else : ?>
    <table class="category table table-striped table-bordered table-hover<?php echo $tableClass; ?> eventlist">
        <caption class="visuallyhidden"><?php echo JText::sprintf('COM_CONTENT_CATEGORY_LIST_TABLE_CAPTION', $this->category->title); ?></caption>
        <thead>
            <tr>
                <th><?php echo JText::_('TPL_SIWECOS_EVENTSCOL_DATE'); ?></th>
                <th><?php echo JText::_('TPL_SIWECOS_EVENTSCOL_EVENT'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($this->items as $i => $article) : ?>
            <?php
            // Beitrags-Felder Mapping
            $customFields = $article->jcfields;

            foreach ($customFields as $customField)
            {
            $customFields[$customField->name] = $customField;
            }
            ?>

            <?php if ($this->items[$i]->state == 0) : ?>
                <tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
            <?php else : ?>
                <tr class="cat-list-row<?php echo $i % 2; ?>" >
            <?php endif; ?>
            <td>
                <?php echo JFactory::getDate($customFields["datum-von"]->value)->format("d.m.Y"); ?>

                <?php if($customFields["datum-bis"]->value && JFactory::getDate($customFields["datum-von"]->value)->format("d.m.Y") !== JFactory::getDate($customFields["datum-bis"]->value)->format("d.m.Y")): ?>
                - <?php echo JFactory::getDate($customFields["datum-bis"]->value)->format("d.m.Y"); ?>
                <?php endif; ?>
                /
                <?php if($customFields["ganztaegig"]->value === "Ja"): ?>
                    <?php echo JText::_('TPL_SIWECOS_EVENTS_FULLDAY'); ?>
                <?php else: ?>
                    <?php echo JFactory::getDate($customFields["datum-von"]->value)->format("H:i"); ?>

                    <?php if($customFields["datum-bis"]->value): ?>
                        -

                        <?php echo JFactory::getDate($customFields["datum-bis"]->value)->format("H:i"); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td class="list-title">
                <?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
                    <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)); ?>">
                        <?php echo $this->escape($article->title); ?>
                    </a>
                    <?php if($customFields["veranstaltungsort"]->value): ?>
                        <br />
                        <?php if($customFields["veranstaltungsort-link"]->value): ?>
                        <a class="eventlist__location" href="<?php echo $customFields["veranstaltungsort-link"]->rawvalue; ?>" target="_blank" rel="noopener noreferrer">
                        <?php endif; ?>
                            <?php echo $customFields["veranstaltungsort"]->value; ?>
                        <?php if($customFields["veranstaltungsort-link"]->value): ?>
                        </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (JLanguageAssociations::isEnabled() && $this->params->get('show_associations')) : ?>
                        <?php $associations = ContentHelperAssociation::displayAssociations($article->id); ?>
                        <?php foreach ($associations as $association) : ?>
                            <?php if ($this->params->get('flags', 1) && $association['language']->image) : ?>
                                <?php $flag = JHtml::_('image', 'mod_languages/' . $association['language']->image . '.gif', $association['language']->title_native, array('title' => $association['language']->title_native), true); ?>
                                &nbsp;<a href="<?php echo JRoute::_($association['item']); ?>"><?php echo $flag; ?></a>&nbsp;
                            <?php else : ?>
                                <?php $class = 'label label-association label-' . $association['language']->sef; ?>
                                &nbsp;<a class="<?php echo $class; ?>" href="<?php echo JRoute::_($association['item']); ?>"><?php echo strtoupper($association['language']->sef); ?></a>&nbsp;
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php else : ?>
                    <?php
                    echo $this->escape($article->title) . ' : ';
                    $menu   = JFactory::getApplication()->getMenu();
                    $active = $menu->getActive();
                    $itemId = $active->id;
                    $link   = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
                    $link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)));
                    ?>
                    <a href="<?php echo $link; ?>" class="register">
                        <?php echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>
                    </a>
                    <?php if (JLanguageAssociations::isEnabled() && $this->params->get('show_associations')) : ?>
                        <?php $associations = ContentHelperAssociation::displayAssociations($article->id); ?>
                        <?php foreach ($associations as $association) : ?>
                            <?php if ($this->params->get('flags', 1)) : ?>
                                <?php $flag = JHtml::_('image', 'mod_languages/' . $association['language']->image . '.gif', $association['language']->title_native, array('title' => $association['language']->title_native), true); ?>
                                &nbsp;<a href="<?php echo JRoute::_($association['item']); ?>"><?php echo $flag; ?></a>&nbsp;
                            <?php else : ?>
                                <?php $class = 'label label-association label-' . $association['language']->sef; ?>
                                &nbsp;<a class="' . <?php echo $class; ?> . '" href="<?php echo JRoute::_($association['item']); ?>"><?php echo strtoupper($association['language']->sef); ?></a>&nbsp;
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($article->state == 0) : ?>
                    <span class="list-published label label-warning">
                                <?php echo JText::_('JUNPUBLISHED'); ?>
                            </span>
                <?php endif; ?>
                <?php if (strtotime($article->publish_up) > strtotime(JFactory::getDate())) : ?>
                    <span class="list-published label label-warning">
                                <?php echo JText::_('JNOTPUBLISHEDYET'); ?>
                            </span>
                <?php endif; ?>
                <?php if ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != JFactory::getDbo()->getNullDate()) : ?>
                    <span class="list-published label label-warning">
                                <?php echo JText::_('JEXPIRED'); ?>
                            </span>
                <?php endif; ?>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php // Code to add a link to submit an article. ?>
<?php if ($this->category->getParams()->get('access-create')) : ?>
    <?php echo JHtml::_('icon.create', $this->category, $this->category->params); ?>
<?php endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
    <?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
        <div class="pagination">

            <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                <p class="counter pull-right">
                    <?php echo $this->pagination->getPagesCounter(); ?>
                </p>
            <?php endif; ?>

            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
</form>
