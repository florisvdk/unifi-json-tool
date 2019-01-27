<?php
$dbhandle = new mysqli($hostdb, $userdb, $passdb, $namedb);
if ($dbhandle->connect_error) {
    exit("There was an error with your connection: ".$dbhandle->connect_error);
}
$dbhandle->set_charset('utf8mb4');
?>