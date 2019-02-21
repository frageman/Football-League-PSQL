<?php 

    include('../db_connect.php');
  
    $query = "SET SEARCH_PATH TO projektBD; UPDATE mecz SET data_meczu ='$_POST[dataMeczu]', id_sedzia ='$_POST[sedzia]', bramki_gosc ='$_POST[bramkiGosc]', bramki_gospodarz ='$_POST[bramkiGosp]', czy_rozegrano = 1 WHERE id_mecz = '$_POST[meczycho]' ; INSERT INTO sukcesy VALUES ('$_POST[gwiazda]')";

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