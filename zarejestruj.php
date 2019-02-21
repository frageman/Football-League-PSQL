<?php

session_start();

include('./db_connect.php');

$login = $_POST['rejlogin'];
$haslo = $_POST['rejhaslo'];
$funkcja = $_POST['funkcja'];

$query = "SET SEARCH_PATH TO projektBD; SELECT * FROM uzytkownicy where nazwa_uzytkownika = '$login'";
$result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
   
if ($line = pg_fetch_array($result, null, PGSQL_ASSOC) || !strcmp("", $login) || !strcmp("", $haslo)) {
    $_SESSION['blad'] = '<span style="color:red">Ten login jest już zajęty lub podałeś niepoprawne dane!</span>';
    header('Location: logowanie.php');
}
else {
    $query = "SET SEARCH_PATH TO projektBD; INSERT INTO uzytkownicy values (default, '$login', '$haslo', '$funkcja')";
    $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
    $_SESSION['blad'] = '<span style="color:green">Zarejestrowano!</span>';
    header('Location: logowanie.php');
}

pg_free_result($result);


?>