<?php

// check if protocols is used

if (hasbgp($site) or hasigmpproxy($site)) {

  $configurationjson .= '
        "protocols": {';

// bgp stuff first

  if (hasbgp($site)) {

    $configurationjson .= '
                "bgp": {
                        "' . $configbgpas . '": {
                                "neighbor": {';

    foreach ($configvpnarray as &$configvpn) {

      $configurationjson .= '
                                        "' . $configvpn['remoterouterid'] . '": {
                                                "ebgp-multihop": "2",
                                                "remote-as": "' . $configvpn['remoteas'] . '",
                                                "soft-reconfiguration": {
                                                        "inbound": "' . "''" . '"
                                                },
                                                "update-source": "' . $configvpn['localrouterid'] . '"
                                        },';

    }

    $configurationjson = substr($configurationjson, 0, -1);

    $configurationjson .= '
                                },
                                "network": {
                                        "192.168.110.0/24": "' . "''" . '"
                                },
                                "timers": {
                                        "holdtime": "180",
                                        "keepalive": "60"
                                }
                        }
                },';

  }

  if (hasigmpproxy($site)) {

    $configigmproxyupstream = getigmpproxyupstream($site);

    $configurationjson .= '
                "igmp-proxy": {
                        "interface": {
                                "' . $configigmproxyupstream['interface'] . '": {
                                        "alt-subnet": [
                                                "' . $configigmproxyupstream['altsubnet'] . '"
                                        ],
                                        "role": "upstream",
                                        "threshold": "1"
                                },';

    foreach (getigmpproxydownstream($site) as &$igmpproxydownstream) {

      $configurationjson .= '
                                "' . $igmpproxydownstream['interface'] . '": {
                                        "alt-subnet": [
                                                "' . $igmpproxydownstream['altsubnet'] . '"
                                        ],
                                        "role": "downstream",
                                        "threshold": "1"
                                },';

    }

    $configurationjson = substr($configurationjson, 0, -1);

    $configurationjson .= '
                        }
                },';

  }

// static routes for bgp

  if (hasbgp($site)) {

    $configurationjson .= '
                "static": {
                        "interface-route": {';

    $x = 0;

    foreach ($configvpnarray as &$configvpn) {

      $configurationjson .= '
                                "' . $configvpn['remoterouterid'] . '/32": {
                                        "next-hop-interface": {
                                                "vti' . $x . '": "' . "''" . '"
                                        }
                                },';

      $x++;

    }

    $configurationjson = substr($configurationjson, 0, -1);


    $configurationjson .= '
                        }
                }';

  } else {

    $configurationjson = substr($configurationjson, 0, -1);

  }

  $configurationjson .= '
        },';


}







?>
