<?php

// include top

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');
include('../../includes/php/top.php');

$site1 = $_GET['site1'];
$site2 = $_GET['site2'];

$site1 = mysqli_real_escape_string($dbhandle, $site1);
$site2 = mysqli_real_escape_string($dbhandle, $site2);

if ($site1 == NULL OR $site2 == NULL OR $site1 == $site2) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

$strQuery1 = "SELECT idvpnconnections FROM vpnconnectionsunifi WHERE (site1 = '" . $site1 . "' AND site2 = '" . $site2 . "') or (site1 = '" . $site2 . "' AND site2 = '" . $site1 . "') ;";
$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
$response1 = mysqli_fetch_array($result1);

if ($response1 == Null) {

	// generate secret

	$secret = generatePassword(20);

	$strQuery1 = "INSERT INTO `vpnconnectionsunifi` (`site1`, `site2`, `secret`) VALUES ('" . $site1 . "', '" . $site2 . "', '" . $secret . "');";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

}

echo '<div class="alert alert-success" role="alert">Connection added or already existed.</div>';
echo '<META http-equiv="refresh" content="1;URL=' . $baseurl . 'index.php">';

// geef footer weer

include("../../includes/php/bottom.php");

?>
