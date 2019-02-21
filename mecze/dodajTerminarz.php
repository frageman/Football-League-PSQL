<?php 

    include('../db_connect.php');
    $query = "SET SEARCH_PATH TO projektBD; INSERT INTO mecz(id_mecz, data_meczu, id_sedzia, id_gospodarz, id_gosc, bramki_gosc, bramki_gospodarz, punkty_gosc, punkty_gospodarz, czy_rozegrano) VALUES(default, '$_POST[dataMeczu]','$_POST[sedzia]','$_POST[gospodarz]', '$_POST[gosc]', 0, 0, 0, 0, 0);";
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
    header("Location: /~6pogwizd/projekt_bd/indexSedzia.php");
    exit;
    }        
    echo "<a href=\"/~6pogwizd/projekt_bd/indexSedzia.php\">powrót do strony głównej</a> ";

?>