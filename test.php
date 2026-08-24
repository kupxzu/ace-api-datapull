<?php
$serverName = "127.0.0.1,1433";
$connectionInfo = array(
    "Database" => "MyDatabase",
    "Uid" => "sa",
    "PWD" => "StrongP@ss123!"
);
 
$conn = sqlsrv_connect($serverName, $connectionInfo);
 
if ($conn === false) {
    echo "CONNECTION FAILED:\n";
    print_r(sqlsrv_errors());
} else {
    echo "CONNECTED SUCCESSFULLY!\n";
    sqlsrv_close($conn);
}
 