<?php

include('includes/php/settings.php');
include('includes/php/mysqli.php');
include('includes/php/functions.php');
include('includes/php/top.php');

echo '<a href="actions/readcontrollerdata.php" class="btn btn-danger" role="button" target="_blank">Read controller data</a>';

echo '<br><br>';

echo '<div class="card"><div class="card-header">Vpn diagram</div><div class="card-body"><div id="vpndiagram"></div></div></div><br>';

// add new unifi to unifi vpn

echo '<div class="card"><div class="card-header">Add unifi to unifi vpn tunnel</div><div class="card-body">';

echo '<form action="actions/add/unifitounifi.php"><div class="row"><div class="col-sm-6"><div class="form-group"><label for="site1">Select site1:</label><select class="form-control" id="site1" name="site1">';

foreach (getsiteswithusg() as &$sites) {

  echo '<option value="' . $sites['idsites'] . '">' . $sites['name'] . '</option>';

}

echo '</select></div><button type="submit" class="btn btn-primary">Add vpn tunnel</button></div><div class="col-sm-6"><div class="form-group"><label for="site2">Select site2:</label><select class="form-control" id="site2" name="site2">';

foreach (getsiteswithusg() as &$sites) {

  echo '<option value="' . $sites['idsites'] . '">' . $sites['name'] . '</option>';

}

echo '</select></div></div></div></form></div></div><br>';

// show generated config

echo '<div class="card"><div class="card-header">Show configuration</div><div class="card-body">';

echo '<form action="actions/get/config.php"><div class="form-group"><label for="site">Select site:</label><select class="form-control" id="site" name="site">';

foreach (getsiteswithusg() as &$sites) {

  echo '<option value="' . $sites['idsites'] . '">' . $sites['name'] . '</option>';

}

echo '</select></div><button type="submit" class="btn btn-primary">Show configuration</button></form></div></div><br>';

// edit a site config

echo '<div class="card"><div class="card-header">Edit site configuration</div><div class="card-body">';

echo '<form action="actions/get/siteconfig.php"><div class="form-group"><label for="site">Select site:</label><select class="form-control" id="site" name="site">';

foreach (getsiteswithusg() as &$sites) {

  echo '<option value="' . $sites['idsites'] . '">' . $sites['name'] . '</option>';

}

echo '</select></div><button type="submit" class="btn btn-primary">Edit configuration</button></form></div></div><br>';

$customjs = '<script src="' . $baseurl . 'actions/generate/graph.js.php"></script>';

include('includes/php/bottom.php');

?>
