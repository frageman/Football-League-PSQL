<?php 

    include('../db_connect.php');

    $query = "SET SEARCH_PATH TO projektBD; SELECT k1.id_klub as klub1, k2.id_klub as klub2 from klub k1, klub k2 where k1.id_klub < k2.id_klub;";
    $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
    $query2;
    $res;
    $note;

    while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        $gospodarz = $line['klub1'];
        $gosc = $line['klub2'];
        $query2 = "SET SEARCH_PATH TO projektBD; INSERT INTO mecz(id_mecz, data_meczu, id_sedzia, id_gospodarz, id_gosc, bramki_gosc, bramki_gospodarz, punkty_gosc, punkty_gospodarz, czy_rozegrano) VALUES(default, '2000-01-01', 1 ,'$gospodarz', '$gosc', 0, 0, 0, 0, 0), (default, '2000-01-01', 1 ,'$gosc', '$gospodarz', 0, 0, 0, 0, 0);";
        $res = pg_query($db, $query2);
        $note = pg_last_notice($db);
    }
   
    echo "Wykonane polecenie: $query<br/>";

    
    

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
