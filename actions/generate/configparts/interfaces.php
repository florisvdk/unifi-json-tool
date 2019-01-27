<?php

// check for interface rules

if (hasbgp($site)) {

  $configurationjson .= '
        "interfaces": {
                "vti": {';

	$x = 0;

	foreach ($configvpnarray as &$configvpn) {

		$configurationjson .= '
                        "vti' . $x . '": {
                                "mtu": "1436"
                        },';
  	$x++;

	}

	$configurationjson = substr($configurationjson, 0, -1);

	$configurationjson .= '
                }
        },';

}

?>
