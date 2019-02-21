<?php

session_start();

if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
    {
        header('Location: logowanie.php');
        exit();
    }

include('./db_connect.php');

$login = $_POST['login'];
$haslo = $_POST['haslo'];


$query = "SET SEARCH_PATH TO projektBD; SELECT * FROM uzytkownicy where nazwa_uzytkownika = '$login' and haslo = '$haslo'";
$result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
   
if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $_SESSION['zalogowany'] = true;
    $_SESSION['uzytkownik'] = $login;
    
    $funkcja = $line['funkcja'];
    if($funkcja == "admin")
        header('Location: index.php');
    
    if($funkcja == "zwykly" ){
        header('Location: indexGosc.php');}
    
    if($funkcja == "sedzia" ){
        header('Location: indexSedzia.php');}
}
else {
    $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
    header('Location: logowanie.php');
    
}
       
        
    pg_free_result($result);

    
     
?>