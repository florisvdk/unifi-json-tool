<?php

// include required files

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');

// include header

include('../../includes/php/top.php');

$site = mysqli_real_escape_string($dbhandle, $_POST['site']);

if ($site == NULL) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

// first do bgp

if ($_POST['bgp'] == 1) {

  $bgp = 1;

} else {

  $bgp = 0;

}

$bgprouterid = filter_var($_POST['bgprouterid'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
$bgpas = filter_var($_POST['bgpas'], FILTER_VALIDATE_INT);

$strQuery1 = "UPDATE `sites` SET `bgp`='" . $bgp . "', `bgprouterid`='" . $bgprouterid . "', `bgpas`='" . $bgpas . "' WHERE `idsites`='" . $site . "';";
$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

// then igmp

if ($_POST['igmp'] == 1) {

  $igmp = 1;
  $igmpupstreamaltsubnet = '0.0.0.0/0';

} else {

  $igmp = 0;
  $igmpupstreamaltsubnet = '';

}

$strQuery1 = "UPDATE `sites` SET `igmpupstream`='" . $igmp . "', `igmpupstreamaltsubnet`='" . $igmpupstreamaltsubnet . "' WHERE `idsites`='" . $site . "';";
$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

// then dns

if ($_POST['dnsredirect'] == 1) {

  $dnsredirect = 1;
  $dnsredirectip = '0.0.0.0/0';

} else {

  $dnsredirect = 0;

}

$dnsredirectip = filter_var($_POST['dnsredirectip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

$dnsridirectinterface = mysqli_real_escape_string($dbhandle, $_POST['dnsridirectinterface']);

$strQuery1 = "UPDATE `sites` SET `dnsredirect`='" . $dnsredirect . "', `dnsredirectip`='" . $dnsredirectip . "', `dnsridirectinterface`='" . $dnsridirectinterface . "' WHERE `idsites`='" . $site . "';";
$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

echo '<div class="alert alert-success" role="alert">Updated successfully.</div>';
echo '<META http-equiv="refresh" content="1;URL=' . $baseurl . 'index.php">';

// display footer

include("../../includes/php/bottom.php");

?>
