<?php 
    include('../db_connect.php');
  
    $_id = intval($_POST[id]);
    $query = "SET SEARCH_PATH TO projektBD; UPDATE klub SET id_stadion ='$_POST[stadion]',  id_zestaw1 ='$_POST[stroje]' WHERE id_klub = '$_POST[klub]';";
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