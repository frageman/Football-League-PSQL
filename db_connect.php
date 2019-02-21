<?php 

    $host = "host = localhost";
    $port = "port = 5432";
    $dbname = "dbname = u6pogwizd";
    $credentials = "user = u6pogwizd password=6pogwizd";
    $db = pg_connect( "$host $port $dbname $credentials"  );
    if(!$db) {
        echo "Nie można połączyć z bazą<br/>";
        exit;
    }
    else{
        echo "Połączono<br>";
    }
?>