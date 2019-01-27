<?php

// include required files

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');

// include header

include('../../includes/php/top.php');

$site = mysqli_real_escape_string($dbhandle, $_GET['site']);
$network = mysqli_real_escape_string($dbhandle, $_GET['network']);

if ($site == NULL OR $network == NULL) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

$siteinfo = getsiteinfofull($site);
$networkinfo = getnetworkinfofull($network);


// site name and network title

echo '<h1>' . $siteinfo['name'] . ': ' . $networkinfo['name'] . '</h1>';

// edit network settings

echo '<div class="card"><div class="card-header">Network settings</div><div class="card-body"><form action="' . $baseurl . 'actions/update/networkconfig.php" method="post">';

echo '<input type="hidden" id="site" name="site" value="' . $site . '">';
echo '<input type="hidden" id="site" name="network" value="' . $network . '">';

echo '<div class="form-group form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="bgp" value="1"';

if ($networkinfo['inbgp'] == 1) {

  echo " checked";

}

echo '> List in Bgp</label></div>';

echo '<div class="form-group form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="igmpupstream" value="1"';

if ($networkinfo['igmpupstream'] == 1) {

  echo " checked";

}

echo '> Use for igmp upstream</label></div>';

echo '<div class="form-group form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="igmpdownstream" value="1"';

if ($networkinfo['igmpdownstream'] == 1) {

  echo " checked";

}

echo '> Use as igmp downstream interface</label></div>';

echo '<div class="form-group form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="dnsredirect" value="1"';

if ($networkinfo['dnsredirect'] == 1) {

  echo " checked";

}

echo '> Use dns redirect</label></div>';

echo '<button type="submit" class="btn btn-success">Save</button></form>';

echo '</div></div><br>';


// display footer

include("../../includes/php/bottom.php");

?>
