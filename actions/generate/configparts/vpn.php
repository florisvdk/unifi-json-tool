<?php

if (hasbgp($site)) {

  $configurationjson .= '
        "vpn": {
                "ipsec": {
                        "auto-firewall-nat-exclude": "enable",
                        "esp-group": {
                                "FOO0": {
                                        "compression": "disable",
                                        "lifetime": "27000",
                                        "mode": "tunnel",
                                        "pfs": "disable",
                                        "proposal": {
                                                "1": {
                                                        "encryption": "aes256",
                                                        "hash": "sha1"
                                                }
                                        }
                                }
                        },
                        "ike-group": {
                                "FOO0": {
                                        "ikev2-reauth": "no",
                                        "key-exchange": "ikev2",
                                        "lifetime": "28800",
                                        "proposal": {
                                                "1": {
                                                        "dh-group": "2",
                                                        "encryption": "aes256",
                                                        "hash": "sha1"
                                                }
                                        }
                                }
                        },
                        "site-to-site": {
                                "peer": {';

  $x = 0;

  foreach ($configvpnarray as &$configvpn) {

    $configurationjson .= '
                                        "' . $configvpn['remotewan'] . '": {
                                                "authentication": {
                                                        "mode": "pre-shared-secret",
                                                        "pre-shared-secret": "' . $configvpn['secret'] . '"
                                                },
                                                "connection-type": "respond",
                                                "description": "ipsec",
                                                "ike-group": "FOO0",
                                                "ikev2-reauth": "inherit",
                                                "local-address": "' . $configvpn['localwan'] . '",
                                                "vti": {
                                                        "bind": "vti' . $x . '",
                                                        "esp-group": "FOO0"
                                                }
                                        },';

	  $x++;

  }

  $configurationjson = substr($configurationjson, 0, -1);

  $configurationjson .= '
                                }
                        }
                }
        }';

}

?>
