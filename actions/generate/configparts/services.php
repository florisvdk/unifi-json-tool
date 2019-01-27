<?php

// check for service usage

if (hasdnsredirect($site)) {

// nat counter variables
$natlow = 1;
$nathigh = 6000;


//check dns redirect

  $configurationjson .= '
        "service": {';

  if (hasdnsredirect($site)) {

    $configurationjson .= '
                "nat": {
                        "rule": {';
    $x = 1;

    $dnsredirectsiteconfig = getdnsredirectinfosite($site);

    foreach ($configdnsarray as &$configdns) {

      $configurationjson .= '
                                "' . $natlow . '": {
                                        "description": "DNS Redirect",
                                        "destination": {
                                                "port": "53"
                                        },
                                        "inbound-interface": "' . $configdns['interface'] . '",
                                        "inside-address": {
                                                "address": "' . $dnsredirectsiteconfig['dnsredirectip'] . '"
                                        },
                                        "source": {
                                                "address": "!' . $dnsredirectsiteconfig['dnsredirectip'] . '"
                                        },
                                        "log": "disable",
                                        "protocol": "tcp_udp",
                                        "type": "destination"
                                },';

    $natlow++;

	  }

  $configurationjson .= '
                                "' . $nathigh . '": {
                                        "description": "Translate DNS to Internal",
                                        "destination": {
                                                "address": "' . $dnsredirectsiteconfig['dnsredirectip'] . '",
                                                "port": "53"
                                        },
                                        "log": "disable",
                                        "outbound-interface": "' . $dnsredirectsiteconfig['dnsridirectinterface'] . '",
                                        "protocol": "tcp_udp",
                                        "type": "masquerade"
                                },';

  $configurationjson = substr($configurationjson, 0, -1);

  $configurationjson .= '
                        }';

  }

  $configurationjson .= '
                }
        },';

if(!hasbgp($site)) $configurationjson = substr($configurationjson, 0, -1);

}

?>
