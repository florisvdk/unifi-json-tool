<?php

// include top

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');
include('../../includes/php/top.php');

$network = $_GET['network'];
$site = $_GET['site'];

$network = mysqli_real_escape_string($dbhandle, $network);

if ($network == NULL) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

$strQuery1 = "DELETE FROM `sitesubnets` WHERE `idsitesubnets`='" . $network . "';";
$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

echo '<div class="alert alert-success" role="alert">removed network.</div>';
echo '<META http-equiv="refresh" content="1;URL=' . $baseurl . 'actions/get/siteconfig.php?site=' . $site . '">';

// geef footer weer

include("../../includes/php/bottom.php");

?>
