<?php

// check for firewall rules

if (hasbgp($site)) {

	$configurationjson .= '
        "firewall": {
                "group": {
                        "network-group": {
                                "remote_site_vpn_network": {
                                        "network": [
                                                "10.0.0.0/8",
                                                "172.16.0.0/12",
                                                "192.168.0.0/16"
                                        ]
                                }
                        }
                },
                "options": {
                        "mss-clamp": {
                                "interface-type": [
                                        "pppoe",
                                        "pptp"
                                ],
                                "mss": "1350"
                        },
                        "mss-clamp6": {
                                "interface-type": [
                                        "pppoe",
                                        "pptp"
                                ],
                                "mss": "1452"
                        }
                }
        },';
}

?>
