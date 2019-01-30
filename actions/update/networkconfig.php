<?php

// include required files

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');

// include header

include('../../includes/php/top.php');

$site = mysqli_real_escape_string($dbhandle, $_POST['site']);
$network = mysqli_real_escape_string($dbhandle, $_POST['network']);

if ($site == NULL AND $network == NULL) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

if ($_POST['bgp'] == 1) {

  $bgp = 1;

} else {

  $bgp = 0;

}

if ($_POST['igmpupstream'] == 1) {

  $igmpupstream = 1;

} else {

  $igmpupstream = 0;

}

if ($_POST['igmpdownstream'] == 1) {

  $igmpdownstream = 1;

} else {

  $igmpdownstream = 0;

}

if ($_POST['dnsredirect'] == 1) {

  $dnsredirect = 1;

} else {

  $dnsredirect = 0;

}

if ($_POST['mdnsrepeater'] == 1) {

  $mdnsrepeater = 1;

} else {

  $mdnsrepeater = 0;

}

$strQuery1 = "UPDATE `sitesubnets` SET `inbgp`='" . $bgp . "', `igmpupstream`='" . $igmpupstream . "', `igmpdownstream`='" . $igmpdownstream . "', `dnsredirect`='" . $dnsredirect . "', `mdnsrepeater`='" . $mdnsrepeater . "' WHERE `idsitesubnets`='" . $network . "';";
$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

echo '<div class="alert alert-success" role="alert">Updated successfully.</div>';
echo '<META http-equiv="refresh" content="1;URL=' . $baseurl . 'actions/get/siteconfig.php?site=' . $site . '">';

// display footer

include("../../includes/php/bottom.php");

?>
