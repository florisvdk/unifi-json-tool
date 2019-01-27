<?php

// include required files

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');
include('../../includes/php/top.php');

// if no site is defined abort and return to the index page

$site = mysqli_real_escape_string($dbhandle, $_GET['site']);

if ($site == NULL) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

// get site info

$siteinfo = getsiteinfo($site);
$configbgprouterid = $siteinfo['bgprouterid'];
$configbgpas = $siteinfo['bgpas'];

// get site features and add them to variables for later use

echo "<p>";

if (hasigmpproxy($site)) echo "This site uses igmp proxy.<br>";
if (hasbgp($site)) echo "This site uses bgp.<br>";
if (hasdnsredirect($site)) echo "This site uses dns redirect.<br>";

if (hasbgp($site)) $configvpnarray = getvpnconnectioninfo($site);
if (hasdnsredirect($site)) $configdnsarray = getdnsredirectinfo($site);

echo "</p>";

// get generated config

include('../generate/config.php');

echo '<pre><code>';

echo htmlspecialchars($configurationjson, ENT_QUOTES);

echo '</code></pre>';

// geef footer weer

include("../../includes/php/bottom.php");

?>
