<?php

// include required files

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');

// include header

include('../../includes/php/top.php');

$site = mysqli_real_escape_string($dbhandle, $_GET['site']);

if ($site == NULL) {

	echo '<META http-equiv="refresh" content="0;URL=' . $baseurl . 'index.php">';
	exit;

}

$siteinfo = getsiteinfofull($site);

// site name title

echo '<h1>' . $siteinfo['name'] . '</h1>';

// show vpn connections

echo '<div class="card"><div class="card-header">Vpn tunnels</div><div class="card-body"><div class="table-responsive"><table class="table table-striped"><thead><tr><th> </th><th>To site</th></tr></thead><tbody>';

foreach (getvpnconnectioninfo($site) as &$vpnconnections) {

  echo '<tr><td><div class="btn-group"><a href="' . $baseurl . 'actions/remove/unifitounifi.php?site=' . $site . '&vpnconnection=' . $vpnconnections['idvpnconnections'] . '" class="btn btn-danger" role="button"><i class="fas fa-trash"></i></a></button></div></td><td>' . $vpnconnections['remotename'] . '</td></tr>';

}

echo '</tbody></table></div></div></div><br>';

// show netwoks

echo '<div class="card"><div class="card-header">Site networks</div><div class="card-body"><div class="table-responsive"><table class="table table-striped"><thead><tr><th> </th><th>To site</th><th>Distribute in bgp</th><th>IGMP</th><th>DNS redirect</th><th>mDNS repeater</th></tr></thead><tbody>';

foreach (getsitenetworks($site) as &$sitesubnets) {

  echo '<tr><td><div class="btn-group"><a href="' . $baseurl . 'actions/remove/network.php?site=' . $site . '&network=' . $sitesubnets['idsitesubnets'] . '" class="btn btn-danger" role="button"><i class="fas fa-trash"></i></a>
	<a href="' . $baseurl . 'actions/get/networkconfig.php?site=' . $site . '&network=' . $sitesubnets['idsitesubnets'] . '" class="btn btn-primary" role="button"><i class="fas fa-edit"></i></a>

	</div></td><td>' . $sitesubnets['subnet'] . '</td><td>';

  if ($sitesubnets['inbgp'] == 1 ) {

    echo '<i class="fas fa-check text-success"></i>';

  } else {

    echo '<i class="fas fa-times text-danger"></i>';

  }

  echo '</td><td>';

  if ($sitesubnets['igmpupstream'] == 1 or $sitesubnets['igmpdownstream'] == 1) {

    echo '<i class="fas fa-check text-success"></i>';

  } else {

    echo '<i class="fas fa-times text-danger"></i>';

  }

  echo '</td><td>';

  if ($sitesubnets['dnsredirect'] == 1) {

    echo '<i class="fas fa-check text-success"></i>';

  } else {

    echo '<i class="fas fa-times text-danger"></i>';

  }

	echo '</td><td>';

  if ($sitesubnets['mdnsrepeater'] == 1) {

    echo '<i class="fas fa-check text-success"></i>';

  } else {

    echo '<i class="fas fa-times text-danger"></i>';

  }

  echo '</td></tr>';

}

echo '</tbody></table></div></div></div><br>';

// edit site settings

echo '<div class="card"><div class="card-header">Site settings</div><div class="card-body"><form action="' . $baseurl . 'actions/update/siteconfig.php" method="post">';

echo '<input type="hidden" id="site" name="site" value="' . $site . '">';

echo '<div class="form-group form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="bgp" value="1"';

if ($siteinfo['bgp'] == 1) {

  echo " checked";

}

echo '> Uses Bgp</label></div><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">bgp router id:</span></div><input type="text" class="form-control" name="bgprouterid" id="bgprouterid" value="' . $siteinfo['bgprouterid'] . '"></div>';

echo '<div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">bgp as:</span></div><input type="text" class="form-control" name="bgpas" id="bgpas" value="' . $siteinfo['bgpas'] . '"></div>';

echo '<br>';

echo '<div class="form-group form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="igmp" value="1"';

if ($siteinfo['igmpupstream'] == 1) {

  echo " checked";

}

echo '> Use wan1 for igmp upstream</label></div><br>';

echo '<div class="form-group form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="dnsredirect" value="1"';

if ($siteinfo['dnsredirect'] == 1) {

  echo " checked";

}

echo '> Uses dns redirect</label></div>';

echo '<div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">dns redirect ip:</span></div><input type="text" class="form-control" name="dnsredirectip" id="dnsredirectip" value="' . $siteinfo['dnsredirectip'] . '"></div>';

echo '<div class="form-group"><label for="dnsridirectinterface">dns redirect interface:</label><select class="form-control" id="dnsridirectinterface" name="dnsridirectinterface">';

foreach (getsitenetworks($site) as &$networks) {

  echo '<option ';

	if ($siteinfo['dnsridirectinterface'] == $networks['interface']) {

		echo "selected ";

	}

	echo 'value="' . $networks['interface'] . '">' . $networks['name'] . ' - ' . $networks['subnet'] . '</option>';

}

echo '</select></div>';

echo '<button type="submit" class="btn btn-success">Save</button></form>';

echo '</div></div><br>';

// display footer

include("../../includes/php/bottom.php");

?>
