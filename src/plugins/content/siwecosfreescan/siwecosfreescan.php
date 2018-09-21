<?php
/**
 * Siwecos Freescan Plugin
 *
 * @version     1.0.0
 * @package     Siwecosjoomla
 * @subpackage  Siwecosfreescan
 * @copyright   Copyright (C) 2018 eco.de
 * @license     GPLv2 or later
 */
defined('_JEXEC') or die;

/**
 * Class PlgContentSiwecosfreescan
 *
 * @since  1.0.0
 */
class PlgContentSiwecosfreescan extends JPlugin
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
        if (false === strpos($article->text, '{siwecosfreescan}'))
        {
            // Bail out if there is no shortcode
            return true;
        }

        $assets = [
            "spa/freescan/manifest.js",
            "spa/freescan/vendor.js",
            "spa/freescan/app.js"
        ];

        $replacement = '<div id="app"></div>';

        foreach ($assets as $asset)
        {
            if (!file_exists(JPATH_ROOT . "/" . $asset))
            {
                $article->text = str_ireplace("{siwecosfreescan}", "Missing asset file: " . $asset, $article->text);
            }

            $replacement .= '<script type=text/javascript src="' . JURI::base() . $asset . '" defer></script>';
        }

        $article->text = str_ireplace("{siwecosfreescan}", $replacement, $article->text);
    }
}
