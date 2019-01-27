<?php

include('../../includes/php/settings.php');
include('../../includes/php/mysqli.php');
include('../../includes/php/functions.php');

$configurationjson = '';

foreach (getsiteswithusg() as &$sites) {

	$configurationjson .= '"' . $sites['name'] . '" [style=filled];';

}

foreach (getvpnconnectionsforgraph() as &$vpnconnection) {

	$configurationjson .= '"' . $vpnconnection['site1'] . '" -> "' . $vpnconnection['site2'] . '" [arrowhead=none]; ';

}


?>
var viz = new Viz();

  viz.renderSVGElement('digraph { <?php echo $configurationjson; ?> }')
  .then(function(element) {
    document.getElementById("vpndiagram").appendChild(element);
  })
  .catch(error => {
    // Create a new Viz instance (@see Caveats page for more info)
    viz = new Viz();

    // Possibly display the error
    console.error(error);
  });
<?php

?>
