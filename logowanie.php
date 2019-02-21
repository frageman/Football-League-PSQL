<?php
 
    session_start();
     
    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
    {
        header('Location: index.php');
        exit();
    }
 
?>
 
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="StyleSheet" href="style.css" type="text/css">
    <title>Moja liga piłkarska</title>
</head>
 
<body>
<div class="logowanie">   
    <form action="zaloguj.php" method="post">
    <h3 >Zaloguj się!</h3>
        Login: <br /> <input type="text" name="login" /> <br />
        Hasło: <br /> <input type="password" name="haslo" /> <br /><br />
        <input type="submit" value="Zaloguj się" /><br /><br /><br />
     
    </form>
    <h3 >LUB</h3>
    <form action="zarejestruj.php" method="post">
    <h3 >Zarejestruj się!</h3>
     Login: <br /> <input type="text" name="rejlogin" /> <br />
     Hasło: <br /> <input type="password" name="rejhaslo" /> <br /><br />
     <select name='funkcja'>
        <option value="admin">Administrator ligi</option>
        <option value="sedzia">Sędzia</option>
        <option value="zwykly">Zwykły użytkownik</option>
    </select><br /><br />
       
     <input type="submit" value="Zarejestruj się" />
  
    </form>
    </div>
<?php
    if(isset($_SESSION['blad']))    echo $_SESSION['blad'];
?>
 
</body>
</html>