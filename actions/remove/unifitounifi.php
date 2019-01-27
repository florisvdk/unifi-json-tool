<?php

// include top

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');
include('../../includes/php/top.php');

$vpnconnection = $_GET['vpnconnection'];
$site = $_GET['site'];

$vpnconnection = mysqli_real_escape_string($dbhandle, $vpnconnection);

if ($vpnconnection == NULL) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

$strQuery1 = "DELETE FROM `vpnconnectionsunifi` WHERE `idvpnconnections`='" . $vpnconnection . "';";
$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

echo '<div class="alert alert-success" role="alert">removed vpn.</div>';
echo '<META http-equiv="refresh" content="1;URL=' . $baseurl . 'actions/get/siteconfig.php?site=' . $site . '">';

// geef footer weer

include("../../includes/php/bottom.php");

?>
