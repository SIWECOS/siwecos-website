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

define('_JEXEC', 1);
define('JPATH_BASE', '../../../');

require_once JPATH_BASE . 'includes/defines.php';
require_once JPATH_BASE . 'includes/framework.php';

/**
 * Description
 *
 * @return string
 */
function getScanDate()
{
	$scandate = '';

	$width = array(
		"0" => 12.5,
		"1" => 9.76562,
		"2" => 11.44531,
		"3" => 11.32812,
		"4" => 12.10937,
		"5" => 11.25,
		"6" => 11.99218,
		"7" => 10.58593,
		"8" => 12.14843,
		"9" => 11.99218,
		"." => 5.07812,
		"/" => 8.08593,
		"-" => 9.45312,
	);

	// Somehow get the domain url we are interested in
	$info = preg_split("/\//", substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME'])), 3);
	$domain = preg_replace('/^([a-z][\\-a-z0-9]*(?:\.[a-z][\\-a-z0-9]*)*)?.*$/i', '$1', substr($info[1], 0, 255));

	// Somehow get the format we want. defined by y,m,d,-,/ and .
	$format = $info[2];

	// Retrieve for the url the date of the last scan in ISO format
	try
	{
		// First fetch the URL from where to retrieve
		$plugin = JPluginHelper::getPlugin('content', 'sealoftrust');
		$params = new JRegistry($plugin->params);
		$coreapi = $params->get('lastscan_url', '');
		$connect_timeout = $params->get('connect_timeout', 5);
	}
	catch (Exception $e)
	{
		return "<!-- " . var_export($e, true) . ' -->';
	}

	$date = '';

	if ($coreapi !== '')
	{
		$client = JHttpFactory::getHttp();
		$response = $client->get($coreapi . '/Y-m-d/' . $domain, null, $connect_timeout);

		if ($response)
		{
			$date = $response->{"body"};
		}
	}

	// Split date into pieces
	$dateSplit = preg_split("/-/", $date);

	if (count($dateSplit) != 3)
	{
		$date = "----------";
	}
	else
	{
		// Concatenate the date as defined by $format
		$date = "";

		foreach (preg_split("//", $format) as $part)
		{
			switch ($part)
			{
				case "d":
					$date .= $dateSplit[2];
					$dateSplit[2] = "";
					break;
				case "m":
					$date .= $dateSplit[1];
					$dateSplit[1] = "";
					break;
				case "y":
					$date .= $dateSplit[0];
					$dateSplit[0] = "";
					break;
				case "/":
				case "-":
				case ".":
					$date .= $part;
					break;
			}
		}
	}

	// Make sure the date isn't too long (superfluous separators)
	$date = substr($date, 0, 10);

	// Print the date as svg code
	$x = 0;

	foreach (preg_split("//", $date) as $digit)
	{
		if ($digit == "")
		{
			continue;
		}

		$scandate .= sprintf('<use xlink:href="#L%s" x="%f"/>', $digit, $x);
		$x += $width[$digit];
	}

	return $scandate;
}
