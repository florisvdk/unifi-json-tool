<?php

include('../includes/php/settings.php');
include('../includes/php/mysqli.php');
include('../includes/php/functions.php');
include('../includes/php/top.php');

require_once ('../includes/php/settings.php');
require_once ('../includes/php/unifi-api-client/client.php');

// get data from unifi controller

$unifi_connection = new UniFi_API\Client($controlleruser, $controllerpassword, $controllerurl, $site_id, $controllerversion);
$set_debug_mode   = $unifi_connection->set_debug($debug);
$loginresults     = $unifi_connection->login();

// get client and device info

$sites_array = $unifi_connection->list_sites();

// loop trugh sites

echo'<div id="contolerlog">';

foreach ($sites_array as $site) {

  $usg = 0;
  $wanip = '0.0.0.0';
  $wanip2 = '0.0.0.0';

  $wanint = '';
  $wan2int = '';
  $lanint = '';
  $lan2int = '';

  // get devices and check for usg

  $unifi_connection2 = new UniFi_API\Client($controlleruser, $controllerpassword, $controllerurl, $site->name, $controllerversion);
  $set_debug_mode2   = $unifi_connection2->set_debug($debug);
  $loginresults2     = $unifi_connection2->login();

  $devices_array = $unifi_connection2->list_devices();

  foreach ($devices_array as $device) {

    if ($device->model == 'UGW3' or $device->model == 'UGW4'or $device->model == 'UGWXG') {

      $usg = 1;

      $ports_array = $device->port_table;

      foreach ($ports_array as $port) {

        if ($port->name == "wan") {

          $wanip = $port->ip;
          $wanint = $port->ifname;

        }

        if ($port->name == "wan2") {

          $wanip2 = $port->ip;
          $wan2int = $port->ifname;

        }

        if ($port->name == "lan") {

          $lanint = $port->ifname;

        }

        if ($port->name == "lan2") {

          $lan2int = $port->ifname;

        }

      }

    }

  }

  // insert into db

  $strQuery1 = "SELECT idsites FROM sites WHERE unifiid = '" . $site->name . "' ;";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
	$response1 = mysqli_fetch_array($result1);

  if ($response1 == Null) {

    $strQuery1 = "INSERT INTO `sites` (`name`, `unifiid`, `usg`, `wan1`, `wan2`, `wan1interface`, `wan2interface`)
    VALUES ('" . $site->desc . "', '" . $site->name . "', '" . $usg . "', '" . $wanip . "', '" . $wanip2 . "', '" . $wanint . "', '" . $wan2int . "');";
  	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  } else {

    $strQuery1 = "UPDATE `sites` SET `usg`='" . $usg . "', `wan1`='" . $wanip . "', `wan2`='" . $wanip2 . "', `wan1interface`='" . $wanint . "', `wan2interface`='" . $wan2int . "' WHERE `unifiid`='" . $site->name . "';";
  	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  }

  //display site info

  echo '<div class="row"><div class="col">';
  echo '<div class="card"><div class="card-header">Site data</div><div class="card-body">';
  echo '<b>Site name:</b> ' . $site->desc . '<br>';
  echo '<b>Site id:</b> ' . $site->name . '<br>';
  echo '<b>Site has usg:</b> ' . $usg . '<br>';
  echo '<b>Wan ip:</b> ' . $wanip . '<br>';
  echo '<b>Wan interface:</b> ' . $wanint . '<br>';
  echo '<b>Wan2 ip:</b> ' . $wanip2 . '<br>';
  echo '<b>Wan2 interface:</b> ' . $wan2int . '<br>';
  echo '<b>Lan interface:</b> ' . $lanint . '<br>';
  echo '<b>Lan2 interface:</b> ' . $lan2int . '<br>';

  $strQuery1 = "SELECT idsites FROM sites WHERE unifiid = '" . $site->name . "' ;";
  $result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  $response1 = mysqli_fetch_array($result1);

  $siteid = $response1['idsites'];

  // loop trugh networks

  $networks_array = $unifi_connection2->list_networkconf();

  foreach ($networks_array as $network) {

    if ($network->vlan == null) {

      if ($network->networkgroup == 'LAN') {

        $networkinterface = $lanint;

      } else {

        $networkinterface = $lan2int;

      }

    } else {

      if ($network->networkgroup == 'LAN') {

        $networkinterface = $lanint . "." . $network->vlan;

      } else {

        $networkinterface = $lan2int . "." . $network->vlan;

      }

    }

    $strQuery1 = "SELECT idsitesubnets FROM sitesubnets WHERE site = '" . $siteid . "' AND subnet = '" . calculateunifinetworkadress($network->ip_subnet) . "';";
    $result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
    $response1 = mysqli_fetch_array($result1);

// this is realy hackey and needs to be fixed

    if ($response1 == Null) {

      if ($network->ip_subnet != NULL) {

        $strQuery1 = "INSERT INTO `sitesubnets` (`subnet`, `inbgp`, `site`, `igmpupstream`, `igmpdownstream`, `dnsredirect`, `name`, `interface`)
        VALUES ('" . calculateunifinetworkadress($network->ip_subnet) . "', '0', '" . $siteid . "', '0', '0', '0', '" . $network->name . "', '" . $networkinterface . "');";
        $result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

      }

    } else {

      $strQuery1 = "UPDATE `sitesubnets` SET `interface`='" . $networkinterface . "', `name`='" . $network->name . "' WHERE site = '" . $siteid . "' AND subnet = '" . calculateunifinetworkadress($network->ip_subnet) . "';";
      $result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

    }

  }

  echo '</div></div></div></div><br>';

}

echo'</div>';

include('../includes/php/bottom.php');

?>
