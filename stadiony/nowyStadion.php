<?php 

    include('../db_connect.php');
    $_id = intval($_POST[id]);
    $query = "SET SEARCH_PATH TO projektBD; INSERT INTO stadion(id_stadion, miasto, ulica, numer, nazwa_stadionu) VALUES(default, '$_POST[miasto]','$_POST[ulica]','$_POST[numer]', '$_POST[nazwa]');";
    echo "Wykonane polecenie: $query<br/>";

    $res = pg_query($db, $query);
    $note = pg_last_notice($db);

    if(!$res){
        echo "BŁĄD BAZY DANYCH:<br>";
        echo pg_last_error($db)."<br>";
        }
    else if($note ){
        echo "$note<br>";
    }
    else{
    header("Location: /~6pogwizd/projekt_bd/index.php");
    exit;
    }        
    echo "<a href=\"/~6pogwizd/projekt_bd/index.php\">powrót do strony głównej</a> ";

?>