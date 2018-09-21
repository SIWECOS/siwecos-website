<?php
/**
 * Siwecos App Plugin
 *
 * @version     1.0.0
 * @package     Siwecosjoomla
 * @subpackage  Siwecosapp
 * @copyright   Copyright (C) 2018 eco.de
 * @license     GPLv2 or later
 */
defined('_JEXEC') or die;

/**
 * Class PlgContentSiwecosapp
 *
 * @since  1.0.0
 */
class PlgContentSiwecosapp extends JPlugin
{
    /**
     * Embed shortcode
     *
     * @param   string  $context   context string
     * @param   object  &$article  article object
     * @param   object  &$params   params object
     * @param   int     $page      page number
     *
     * @return bool
     */
    public function onContentPrepare($context, &$article, &$params, $page = 0)
    {
        if (false === strpos($article->text, '{siwecosapp}'))
        {
            // Bail out if there is no shortcode
            return true;
        }


        $assets = [
            "spa/main/manifest.js",
            "spa/main/vendor.js",
            "spa/main/app.js"
        ];

        $replacement = '<div id="app"></div>';

        foreach ($assets as $asset)
        {
            if (!file_exists(JPATH_ROOT . "/" . $asset))
            {
                $article->text = str_ireplace("{siwecosapp}", "Missing asset file: " . $asset, $article->text);
            }

            $replacement .= '<script type=text/javascript src="' . JURI::base() . $asset . '" defer></script>';
        }

        $article->text = str_ireplace("{siwecosapp}", $replacement, $article->text);
    }
}
