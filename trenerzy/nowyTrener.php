<?php 

    include('../db_connect.php');
    $_id = intval($_POST[id]);
    $query = "SET SEARCH_PATH TO projektBD; INSERT INTO trener(id_trener, imie, nazwisko, email, id_klub, id_rola) VALUES(default, '$_POST[imie]','$_POST[nazwisko]', '$_POST[email]', '$_POST[klub]', '$_POST[rola]');";
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