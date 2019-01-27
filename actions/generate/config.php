<?php

// include required files

require_once('../../includes/php/settings.php');
require_once('../../includes/php/mysqli.php');
require_once('../../includes/php/functions.php');

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

if (hasbgp($site)) $configvpnarray = getvpnconnectioninfo($site);
if (hasdnsredirect($site)) $configdnsarray = getdnsredirectinfo($site);

// start generating config

$configurationjson .= '{';

// firewall is first

include('configparts/firewall.php');

// interfaces is seccond

include('configparts/interfaces.php');

// protocols is third

include('configparts/protocols.php');

// services

include('configparts/services.php');

// vpn

include('configparts/vpn.php');

$configurationjson .= '
}
';

?>
