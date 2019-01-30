<?php

// getsiteinfowithusg - returns an array with all the given site info

function getsiteinfo($site) {

	global $dbhandle;

  $strQuery1 = "SELECT idsites, bgp, bgprouterid, bgpas, name FROM sites WHERE usg = '1' AND idsites = '" . $site . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  $response1 = mysqli_fetch_array($result1);

	return $response1;

}

// getvpnconnectioninfo - returns an array with all the given site to site vpn info

function getvpnconnectioninfo($site) {

	global $dbhandle;

  $strQuery1 = "SELECT sites.wan1 AS localwan, sites.bgprouterid AS localrouterid, sites.bgpas AS localas, vpnconnectionsunifi.secret, sites_1.wan1 AS remotewan, sites_1.bgprouterid AS remoterouterid, sites_1.bgpas AS remoteas, sites_1.name AS remotename, vpnconnectionsunifi.idvpnconnections
  FROM sites AS sites_1 INNER JOIN (sites INNER JOIN vpnconnectionsunifi ON sites.idsites = vpnconnectionsunifi.site1) ON sites_1.idsites = vpnconnectionsunifi.site2 WHERE vpnconnectionsunifi.site1 = '" . $site . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result1)) {

		$array[] = $row;

	}

  $strQuery1 = "SELECT sites_1.wan1 AS localwan, sites_1.bgprouterid AS localrouterid, sites_1.bgpas AS localas, vpnconnectionsunifi.secret, sites.wan1 AS remotewan, sites.bgprouterid AS remoterouterid, sites.bgpas AS remoteas, sites.name AS remotename, vpnconnectionsunifi.idvpnconnections
  FROM sites AS sites_1 INNER JOIN (sites INNER JOIN vpnconnectionsunifi ON sites.idsites = vpnconnectionsunifi.site1) ON sites_1.idsites = vpnconnectionsunifi.site2 WHERE vpnconnectionsunifi.site2 = '" . $site . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result1)) {

		$array[] = $row;

	}

	return $array;

}

// getsiteswithusg - returns an array with all the sites with usg

function getsiteswithusg() {

	global $dbhandle;

  $strQuery1 = "SELECT idsites, name FROM sites WHERE usg = '1';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result1)) {

		$array[] = $row;

	}

	return $array;

}

// getvpnconnections - returns an array with all the vpn connections

function getvpnconnectionsforgraph() {

	global $dbhandle;

  $strQuery1 = "SELECT sites.name AS site1, sites_1.name AS site2
  FROM (vpnconnectionsunifi INNER JOIN sites ON vpnconnectionsunifi.site1 = sites.idsites) INNER JOIN sites AS sites_1 ON vpnconnectionsunifi.site2 = sites_1.idsites;";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result1)) {

		$array[] = $row;

	}

	return $array;

}

// hasigmpproxy - returns true if igmpproxy is used for the site

function hasigmpproxy($site) {

	global $dbhandle;

	$strQuery1 = "SELECT idsites FROM sites WHERE igmpupstream = '1' AND idsites = '" . $site . "' ;";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
	$response1 = mysqli_fetch_array($result1);

	if($response1 != null) {

		return TRUE;

	} else {

		return FALSE;

	}

}

// hasbgp - returns true if bgp is used for the site

function hasbgp($site) {

	global $dbhandle;

	$strQuery1 = "SELECT idsites FROM sites WHERE bgp = '1' AND idsites = '" . $site . "' ;";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
	$response1 = mysqli_fetch_array($result1);

	if($response1 != null) {

		return TRUE;

	}

  $strQuery1 = "SELECT site FROM sitesubnets WHERE site = '" . $site . "' AND ( igmpupstream = '1' OR igmpupstream = '1' ) ;";
  $result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  $response1 = mysqli_fetch_array($result1);

  if($response1 != null) {

    return TRUE;

  }

	return FALSE;

}

// hasmdnsrepeater - returns true if mdns repeater is used for the site

function hasmdnsrepeater($site) {

	global $dbhandle;

  $strQuery1 = "SELECT site FROM sitesubnets WHERE site = '" . $site . "' AND mdnsrepeater = '1' ;";
  $result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  $response1 = mysqli_fetch_array($result1);

  if($response1 != null) {

    return TRUE;

  }

	return FALSE;

}

// hasdnsredirect - returns true if dns redirect is used for the site

function hasdnsredirect($site) {

	global $dbhandle;

	$strQuery1 = "SELECT idsites FROM sites WHERE dnsredirect = '1' AND idsites = '" . $site . "' ;";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
	$response1 = mysqli_fetch_array($result1);

	if($response1 != null) {

		return TRUE;

	} else {

		return FALSE;

	}

}

// getigmpproxyupstream - returns an array with all the needed info

function getigmpproxyupstream($site) {

	global $dbhandle;

  $strQuery1 = "SELECT wan1interface AS interface, igmpupstreamaltsubnet AS altsubnet FROM sites WHERE igmpupstream = '1' AND idsites = '" . $site . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  $response1 = mysqli_fetch_array($result1);

  if ($response1 != Null) {

    return $response1;

  }

  $strQuery1 = "SELECT interface AS interface, subnet AS altsubnet FROM sitesubnets WHERE igmpupstream = '1' AND site = '" . $site . "';";
  $result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  $response1 = mysqli_fetch_array($result1);

  if ($response1 != Null) {

    return $response1;

  }

  return FALSE;

}

// getigmpproxydownstream - returns an array with all the igmpproxy downstream info

function getigmpproxydownstream($site) {

	global $dbhandle;

  $strQuery1 = "SELECT interface, subnet AS altsubnet
  FROM sitesubnets WHERE site = '" . $site . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result1)) {

		$array[] = $row;

	}

	return $array;

}

// getdnsredirect - returns an array with all the needed info

function getdnsredirectinfo($site) {

	global $dbhandle;

	$strQuery2 = "SELECT interface FROM sitesubnets WHERE dnsredirect = '1' AND site = '" . $site . "';";
	$result2 = $dbhandle->query($strQuery2) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result2)) {

		$array[] = $row;

	}

  return $array;

}

// getmdnsrepeaterinfo - returns an array with all the needed info

function getmdnsrepeaterinfo($site) {

	global $dbhandle;

	$strQuery2 = "SELECT interface FROM sitesubnets WHERE mdnsrepeater = '1' AND site = '" . $site . "';";
	$result2 = $dbhandle->query($strQuery2) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result2)) {

		$array[] = $row;

	}

  return $array;

}

// getdnsredirectinfosite - returns an array with all the needed info

function getdnsredirectinfosite($site) {

	global $dbhandle;

	$strQuery2 = "SELECT dnsridirectinterface, dnsredirectip FROM sites WHERE dnsredirect = '1' AND idsites = '" . $site . "';";
	$result2 = $dbhandle->query($strQuery2) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
	$response2 = mysqli_fetch_array($result2);

  return $response2;

}

// getsitenetworks - returns an array with all the networks a site has

function getsitenetworks($site) {

	global $dbhandle;

  $strQuery1 = "SELECT * FROM sitesubnets WHERE site = '" . $site . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

	while ($row = mysqli_fetch_array($result1)) {

		$array[] = $row;

	}

	return $array;

}

// getsiteinfofull - returns an array with all the given site info

function getsiteinfofull($site) {

	global $dbhandle;

  $strQuery1 = "SELECT * FROM sites WHERE idsites = '" . $site . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  $response1 = mysqli_fetch_array($result1);

	return $response1;

}

// getnetworkinfofull - returns an array with all the given site info

function getnetworkinfofull($network) {

	global $dbhandle;

  $strQuery1 = "SELECT * FROM sitesubnets WHERE idsitesubnets = '" . $network . "';";
	$result1 = $dbhandle->query($strQuery1) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  $response1 = mysqli_fetch_array($result1);

	return $response1;

}

// calculateunifinetworkadress - returns an sting in the correct format giveen from the givven ipadressa and subnet

function calculateunifinetworkadress($ipadressfull) {

	$ipadress = strtok( $ipadressfull, '/' );
	$shortnetmask = strtok( '' );

	$longnetmask = long2ip(-1 << (32 - (int)$shortnetmask));


	$input = new stdClass();
	$input->ip = $ipadress;
	$input->netmask = $longnetmask;
	$input->ip_int = ip2long($input->ip);
	$input->netmask_int = ip2long($input->netmask);
	// Network is a logical AND between the address and netmask
	$input->network_int = $input->ip_int & $input->netmask_int;
	$input->network = long2ip($input->network_int);

	return $input->network . '/' . $shortnetmask;

}

// getRandomBytes - get seure random bytes

function getRandomBytes($nbBytes = 32) {

	$bytes = openssl_random_pseudo_bytes($nbBytes, $strong);

	if (false !== $bytes && true === $strong) {

		return $bytes;

	} else {

		throw new \Exception("Unable to generate secure token from OpenSSL.");

	}

}

// generatePassword - generte a secure pawwsord with the provided length

function generatePassword($length) {

	return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(getRandomBytes($length+1))),0,$length);

}

?>
