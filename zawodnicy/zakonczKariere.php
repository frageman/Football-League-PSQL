<?php 
    include('../db_connect.php');
  
    $_id = intval($_POST[id]);
    $query = "SET SEARCH_PATH TO projektBD; DELETE FROM sukcesy where id_zawodnik = '$_POST[zawodnik]'; DELETE FROM zawodnik WHERE id_zawodnik = '$_POST[zawodnik]'; ";
    echo "Wykonane polecenie: $query<br/>";

    $res = pg_query($db, $query);
    if(!$res){
        echo "BŁĄD BAZY DANYCH:<br>";
        echo pg_last_error($db)."<br>";
        echo "<a href=\"/~6pogwizd/projekt_bd/index.php\">powrót do strony głównej</a> ";
        }
    else{
    header("Location: /~6pogwizd/projekt_bd/index.php");
    exit;
    }

?>