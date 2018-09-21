<?php
/**
 * Siwecos Seal of Trust Plugin
 *
 * @version     1.0.0
 * @package     Siwecosjoomla
 * @subpackage  Sealoftrustplugin
 * @copyright   Copyright (C) 2018 eco.de
 * @license     GPLv2 or later
 */
defined('_JEXEC') or die;

/**
 * Class PlgContentSealoftrust
 *
 * @since  1.0.0
 */
class PlgContentSealoftrust extends JPlugin
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
        if (false === strpos($article->text, '[/siwecos]'))
        {
            // Bail out if there is no shortcode
            return true;
        }

        // Get the customer's domain
        $user_domain = JFactory::getApplication()->input->get('data-siwecos', 'www.siwecos.de', 'cmd');

        // Get my required settings
        $domainscan_url = $this->params->get('domainscan_url');
        $date_format = $this->params->get('date_format');

        // Get the domain's scan result
        $client = JHttpFactory::getHttp();
        $response = $client->get($domainscan_url . $user_domain);

        // Get the data
        $result = '';

        if (false !== $response and $response->code === 200)
        {
            $result = json_decode($response->body);
        }

        // Find the [siwecos] ... [/siwecos] shortcode and work on its content
        $article->text = preg_replace_callback(
            "/\[siwecos\b(.*?)\](.*?)\[\/siwecos\]/s",
            function ($matches) use ($user_domain, $date_format, $result) {
                // Replace the [domain]-shortcode beforehand.
                $text = preg_replace('/\[domain\]/', $user_domain, $matches[2]);

                if ('' === $result)
                {
                    // Just return what's in the unknown-shortcode
                    return preg_replace("/^.*\[unknown\](.*?)\[\/unknown\].*$/s", '${1}', $text);
                }

                // Remove the [unknown]-shortcode
                $text = preg_replace("/\[unknown\].*?\[\/unknown\]/s", '', $text);

                // Parse all siwecos shortcodes
                $text = preg_replace_callback(
                    '/\[(last|score|url)\b(.*?)\]/',
                    function ($matches) use ($result, $date_format) {
                        switch ($matches[1])
                        {
                        case 'last':
                            extract(
                                $this->shortcode_attributes(
                                    [
                                        "format" => $date_format,
                                        "tz" => '',
                                    ], $matches[2]
                                )
                            );

                            $lastScan = new DateTime(
                                $result->{'lastScan'}->{'date'},
                                new DateTimeZone($result->{'lastScan'}->{'timezone'})
                            );

                            if ('' != $tz)
                            {
                                try
                                {
                                    $lastScan->setTimezone(new DateTimeZone($tz));
                                }
                                catch (Exception $e)
                                {
                                    // Ggnore wrong timezone strings
                                }
                            }

                            return $lastScan->format($format);
                        case 'score':
                            extract(
                                $this->shortcode_attributes(
                                    [
                                        "precision" => 0
                                    ], $matches[2]
                                )
                            );

                            return sprintf("%." . $precision . "f", round($result->{'Score'}, $precision));
                        case 'url':
                            return $result->{'domain'};
                        default:
                            return $matches[0];
                        }
                    },
                $text
            );

            // Siwecos shortcode class?
            extract(
                $this->shortcode_attributes(
                    [
                        "class" => ""
                    ], $matches[1]
                )
            );

            if ($class !== "")
            {
                $class = preg_replace(
                    "/%S/", floor($result->{'Score'} / 10), preg_replace("/%s/", floor($result->{'Score'}), $class)
                );

                return '<div class="' . $class . '">' . $text . '</div>';
            }

            return $text;
            },
        $article->text
        );

        return true;
    }

    /**
     * Extract attribute from shortcode
     *
     * @param   array   $attributes  atttributes array
     * @param   string  $string      string to process
     *
     * @return array
     */
    protected  function shortcode_attributes($attributes = [], $string = "")
    {
        // Split at whitespace followed by something that looks like an attribute
        $matches = [];

        // String has to start with an identifier followed by "="
        while (1 === preg_match("/^\s+(\w+)\s*=\s*/", $string, $matches))
        {
            $string = substr($string, strlen($matches[0]));
            $k = $matches[1];

            if (1 == preg_match("/^'((?:\\\\.|[^\\'\\\\])*)'/", $string, $matches)
             || 1 == preg_match('/^"((?:\\\\.|[^\\"\\\\])*)"/', $string, $matches))
            {
                $attributes[$k] = preg_replace("/\\\\(.)/", '${1}', $matches[1]);
            }
            elseif (1 == preg_match("/^(\S+)/", $string, $matches))
            {
                $attributes[$k] = $matches[1];
            }
            else
            {
                break;
            }

            $string = substr($string, strlen($matches[0]));
        }

        return $attributes;
    }
}
