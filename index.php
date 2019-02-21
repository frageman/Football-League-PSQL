<?php 
    function connect(){
        $host = "host = localhost";
        $port = "port = 5432";
        $dbname = "dbname = u6pogwizd";
        $credentials = "user = u6pogwizd password=6pogwizd";
        $db = pg_connect( "$host $port $dbname $credentials"  );
        if(!$db) {
           echo "Database Error : Unable to open database<br/>";
           exit;
        }
        return $db;
    }

    session_start();
     
    if (!isset($_SESSION['zalogowany']))
    {
        header('Location: logowanie.php');
        exit();
    }

    //ZAWODNICY
    function zawodnicy(){
        $db = connect();
        $query = 'SET SEARCH_PATH TO projektBD; SELECT z.imie, z.nazwisko, k.nazwa FROM zawodnik z, klub k WHERE z.id_klub = k.id_klub ORDER BY k.nazwa, z.nazwisko';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        echo " <h3 >Zawodnicy</h3>";
        echo "<table>\n
                <tr><td>Imie</td><td>Nazwisko</td><td>Klub</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        
        pg_free_result($result);

        $query = 'SET SEARCH_PATH TO projektBD; SELECT k.nazwa, COUNT(*) FROM zawodnik z, klub k WHERE z.id_klub = k.id_klub group by k.nazwa ORDER BY k.nazwa;';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());

        echo " <h3 >Liczba zawodników w klubie</h3>";
        echo "<table>\n
                <tr><td>Klub</td><td>Liczba graczy</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";

        pg_free_result($result);

        $query = 'SET SEARCH_PATH TO projektBD; SELECT z.imie, z.nazwisko, COUNT(*) as naj FROM zawodnik z, sukcesy s WHERE z.id_zawodnik = s.id_zawodnik group by z.id_zawodnik order by naj desc;';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());

        echo " <h3 >Najlepsi gracze ligi we wszystkich sezonach</h3>";
        echo "<table>\n
                <tr><td>Imie</td><td>Nazwisko</td><td>MVP spotkania</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";

        pg_free_result($result);

    }

    function dodajNowegoZawodnikaform(){
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM klub");
        echo " <h3 >Dodaj nowego zawodnika</h3>";
        echo"
        <form action = \"zawodnicy/nowyZawodnik.php\" method = \"post\">
        Imie: <br /><br />
        <input type=\"text\" name=\"imie\" ><br /><br /> 
        Nazwisko: <br /><br />
        <input type=\"text\" name=\"nazwisko\" ><br /><br />";

        echo "<select name='klub'>";
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }

        echo '</select><br /><br /> <br /><br /> ';
        echo "
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";
      
    }

    function aktualizujZawodnikaform(){
        echo " <br><h3 >Dokonaj transferu zawodnika</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM zawodnik");
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM klub");
        echo "<form action = \"zawodnicy/aktualizujZawodnika.php\" method = \"post\">
        <h3> Zawodnik </h3><select name='wybrane'>";
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[1]." ".$row[2]."</option>";
        }
        echo '</select>';
        echo "<h3> Nowy klub </h3> <select name='klub2'>";
        while($row = pg_fetch_array($query2))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select></br>';
        echo"
        <input type=\"submit\" name =\"new\" value=\"Aktualizuj\">

      </form>";
   
    }

    function zakonczKariereZawodnikaform(){
        echo " <h3 >Zakończ kariere</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM zawodnik");
        echo"
        <form action = \"zawodnicy/zakonczKariere.php\" method = \"post\">";
        
        echo "<select name='zawodnik'>";
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[1]." ".$row[2]."</option>";
        }
        echo '</select></br>';

        echo"
        <input type=\"submit\" name =\"new\" value=\"Usun\">
      </form>";
      
    }

    //TRENERZY

    
    function trenerzy(){
        echo " <h3 >Trenerzy</h3>";
        $db = connect();
        $query = 'SET SEARCH_PATH TO projektBD; SELECT z.imie, z.nazwisko, k.nazwa, z.email, r.rola FROM trener z, klub k, rola r WHERE z.id_klub = k.id_klub and z.id_rola = r.id_rola ORDER BY k.nazwa, z.nazwisko';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<table>\n
                <tr><td>Imie</td><td>Nazwisko</td><td>Klub</td><td>Email</td><td>Rola w zespole</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        
        pg_free_result($result);

        $query = 'SET SEARCH_PATH TO projektBD; SELECT k.nazwa, COUNT(*) FROM trener z, klub k WHERE z.id_klub = k.id_klub group by k.nazwa ORDER BY k.nazwa;';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());

        echo " <h3 >Liczba trenerów w klubie</h3>";
        echo "<table>\n
                <tr><td>Klub</td><td>Liczba trenerów</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";

        pg_free_result($result);
    }

    function dodajNowegoTreneraform(){
        echo " <h3 >Dodaj nowego trenera</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM klub");
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM rola");

        echo"
        <form action = \"trenerzy/nowyTrener.php\" method = \"post\">
        Imie: <br /><br />
        <input type=\"text\" name=\"imie\" ><br /><br /> 
        Nazwisko: <br /><br />
        <input type=\"text\" name=\"nazwisko\" ><br /><br />
        Email: <br /><br />
        <input type=\"text\" name=\"email\" ><br /><br />";

        echo "<select name='klub'>";
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select><br /><br />';

        echo "<select name='rola'>";
        
        while($row = pg_fetch_array($query2))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select><br /><br />';
      
        echo "
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";
      
    }

    function aktualizujTreneraform(){
        echo " <h3 >Aktualizuj dane trenera</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM trener");
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM klub");
        echo"
        <form action = \"trenerzy/aktualizujTrenera.php\" method = \"post\">";
        
        echo "<h3>Wybierz trenera</h3><select name='trener'>";
        
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[1]." " .$row[2]. "</option>";
        }
        echo '</select><br /><br />';

        echo "
        Email: <br /><br />
        <input type=\"text\" name=\"email\" ><br /><br />";

        echo "<select name='klub'>";
        while($row = pg_fetch_array($query2))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select><br /><br />';


    echo "
        <input type=\"submit\" name =\"new\" value=\"Aktualizuj\">
      </form>";
   
    }

    function zakonczKariereTreneraform(){

        echo " <h3 >Usuń trenera</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM trener");
        echo"
        <form action = \"trenerzy/zakonczKariere.php\" method = \"post\">";
        
        echo "<select name='trener'>";
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[1]." ".$row[2]."</option>";
        }
        echo '</select><br /><br />';

        echo"
        <input type=\"submit\" name =\"new\" value=\"Usun\">
      </form>";
        
      
    }
    

    //KLUBY

    function kluby(){
        echo " <h3 >Lista klubów</h3>";
        $db = connect();
        $query = 'SET SEARCH_PATH TO projektBD; SELECT klub.nazwa, klub.rok, stadion.nazwa_stadionu, ks.kolor FROM klub , stadion , koszulki ks WHERE stadion.id_stadion = klub.id_stadion and ks.id_zestaw = klub.id_zestaw1 ORDER BY klub.rok';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<table>\n
                <tr><td>Nazwa</td><td>Rok powstania</td><td>Nazwa Stadionu</td><td>Kolor koszulek</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        
        pg_free_result($result);
    }

    function dodajNowyKlubform(){
        echo " <h3 >Dodaj nowy klub!</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM stadion");
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM koszulki");

        echo"
        <form action = \"kluby/nowyKlub.php\" method = \"post\">
        Nazwa: <br /><br />
        <input type=\"text\" name=\"nazwa\" ><br /><br /> 
        Rok: <br /><br />
        <input type=\"text\" name=\"rok\" ><br /><br />";
             
        echo "Stadion: <select name='stadion'>";
        
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[4].", " .$row[1]. "</option>";
        }
        echo '</select><br /><br />';

        echo "Kolor strojów:  <select name='stroje'>";
        while($row = pg_fetch_array($query2))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select><br /><br />';

        echo "
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";
      
    }

    function aktualizujKlubform(){
        echo " <h3 >Aktualizuj dane klubu</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM stadion");
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM koszulki");
        $query3 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM klub");
        echo"
        <form action = \"kluby/aktualizujKlub.php\" method = \"post\">";

        echo "Klub: <select name='klub'>";
        
        while($row = pg_fetch_array($query3))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select>';
       
        echo " Stadion: <select name='stadion'>";
        
        while($row = pg_fetch_array($query))
        {   
           echo "<option value=".$row[0].">".$row[4].", " .$row[1]. "</option>";
        }
        echo '</select>';

        echo " Kolor strojów:  <select name='stroje'>";
        while($row = pg_fetch_array($query2))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select> <br /><br />';

        echo "
        <input type=\"submit\" name =\"new\" value=\"Aktualizuj\">
      </form>";
 
    }

    
    //SĘDZIOWIE

    function sedziowie(){
        echo " <h3 >Lista sędziów</h3>";
        $db = connect();
        $query = 'SET SEARCH_PATH TO projektBD; SELECT s.imie, s.nazwisko FROM sedzia s ORDER BY s.nazwisko';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<table>\n
                <tr><td>Imie</td><td>Nazwisko</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        
        pg_free_result($result);
    }

    function dodajSedziegoform(){
        echo " <h3 >Dodaj sędziego!</h3>";
        echo"
        <form action = \"sedziowie/nowySedzia.php\" method = \"post\">
        Imie: <br /><br />
        <input type=\"text\" name=\"imie\" ><br /><br /> 
        Nazwisko: <br /><br />
        <input type=\"text\" name=\"nazwisko\" ><br /><br />
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";
      
    }

    
    //STADIONY

    function stadiony(){
        echo " <h3 >Obiekty sportowe</h3>";
        $db = connect();
        $query = 'SET SEARCH_PATH TO projektBD; SELECT s.nazwa_stadionu, s.miasto, s.ulica, s.numer FROM stadion s ORDER BY s.miasto';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<table>\n
                <tr><td>Nazwa</td><td>Miasto</td><td>Ulica</td><td>Numer</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        
        pg_free_result($result);
    }

    function dodajStadionform(){
        echo " <h3 >Dodaj nowy stadion!</h3>";
        echo"
        <form action = \"stadiony/nowyStadion.php\" method = \"post\">
        Miasto: <br /><br />
        <input type=\"text\" name=\"miasto\" ><br /><br /> 
        Ulica: <br /><br />
        <input type=\"text\" name=\"ulica\" ><br /><br />
        Numer: <br /><br />
        <input type=\"text\" name=\"numer\" ><br /><br />
        Nazwa obiektu: <br /><br />
        <input type=\"text\" name=\"nazwa\" ><br /><br />
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";
      
    }

    //BARWY


    function koszulki(){
        echo " <h3 >Dostępne wzory koszulek</h3>";
        $db = connect();
        $query = 'SET SEARCH_PATH TO projektBD; SELECT * FROM koszulki;';
        $result = pg_query($query) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<table>\n
                <tr><td>ID</td><td>Kolor</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        
        pg_free_result($result);
    }

    function dodajKoszulkiform(){
        echo " <h3 >Dodaj nowy wzór</h3>";
        echo"
        <form action = \"koszulki/noweBarwy.php\" method = \"post\">
        Kolor: <br /><br />
        <input type=\"text\" name=\"kolor\" ><br /><br />
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";
      
    }

?>

<html lang="pl">

    <head>
        <meta charset="utf-8" >
        <title>Liga piłkarska</title>
        <link rel="StyleSheet" href="style.css" type="text/css">
    </head>
    
    <body>
 
  
    <h1 style="text-align:center;">Centrum zarządzania ligą piłkarską</h1>

<div class = "row">
    <div class = "column left">
        <h2>MENU</h2>
    <form method="post">
            <h3 >Centrum Transferowe</h3>
            <div class = "section">
            <input type="submit" name="zawodnicy" value="Baza zawodników" /><br/>
            <input type="submit" name="dodajNowegoZawodnikaform" value="Dodaj nową gwiazde piłki nożnej!" /><br/>
            <input type="submit" name="aktualizujZawodnikaform" value="Dokonaj transferu zawodnika!" /><br/>
            <input type="submit" name="zakonczKariereZawodnikaform" value="Zakończ kariere zawodnika" /><br/>
            </div>
        
    
        <h3 >Centrum Treningowe</h3>
            <div class = "section">
        <input type="submit" name="trenerzy" value="Baza trenerów" /><br/>
            <input type="submit" name="dodajNowegoTreneraform" value="Dodaj nowego selekcjonera!" /><br/>
            <input type="submit" name="aktualizujTreneraform" value="Aktualizuj dane trenera" /><br/>
            <input type="submit" name="zakonczKariereTreneraform" value="Zwolnij trenera" /><br/>
            </div>
        

        <h3 >Centrum Klubowe</h3>
            <div class = "section">
        <input type="submit" name="kluby" value="Lista klubów" /><br/>
            <input type="submit" name="dodajNowyKlubform" value="Dodaj nowy klub i graj o najwyższe cele!" /><br/>
            <input type="submit" name="aktualizujKlubform" value="Aktualizuj dane klubu" /><br/>
            </div>
        

        <h3 >Centrum Sędziowskie</h3>
            <div class = "section">
        <input type="submit" name="sedziowie" value="Baza sędziów" /><br/>
            <input type="submit" name="dodajSedziegoform" value="Dodaj nowy sędziego!" /><br/>
            <!--<input type="submit" name="zwolnijSedziegoform" value="Nieuczciwy sędzia? Usuń go z ligi!" /><br/>-->
            </div>
        

        <h3 >Stadiony</h3>
            <div class = "section">
            <input type="submit" name="stadiony" value="Obiekty sportowe" /><br/>
            <input type="submit" name="dodajStadionform" value="Dodaj nowy stadion!" /><br/>
            </div>
        

        <h3 >Barwy klubowe</h3>
            <div class = "section">
        <input type="submit" name="koszulki" value="Dostępne wzory" /><br/>
            <input type="submit" name="dodajKoszulkiform" value="Dodaj nowy kolor!" /><br/>
            </div>
        
        
        <div class="section">
        <br/>
        <br/>
        <a href="wyloguj.php"><input type="button" value="Wyloguj!" /></a>;
        </div>

    </form>
    </div>
              
    <div class="column right">

                <div id="insert_section"></div>
                <?php #endregion
                if(array_key_exists('zawodnicy',$_POST)){
                    zawodnicy();
                }
                elseif(array_key_exists('dodajNowegoZawodnikaform',$_POST)){
                    dodajNowegoZawodnikaform();
                }
                elseif(array_key_exists('aktualizujZawodnikaform',$_POST)){
                    aktualizujZawodnikaform();
                }
                elseif(array_key_exists('zakonczKariereZawodnikaform',$_POST)){
                    zakonczKariereZawodnikaform();
                }
                elseif(array_key_exists('trenerzy',$_POST)){
                    trenerzy();
                }
                elseif(array_key_exists('dodajNowegoTreneraform',$_POST)){
                    dodajNowegoTreneraform();
                }
                elseif(array_key_exists('aktualizujTreneraform',$_POST)){
                    aktualizujTreneraform();
                }
                elseif(array_key_exists('zakonczKariereTreneraform',$_POST)){
                    zakonczKariereTreneraform();
                }
                elseif(array_key_exists('kluby',$_POST)){
                    kluby();
                }
                elseif(array_key_exists('dodajNowyKlubform',$_POST)){
                    dodajNowyKlubform();
                }
                elseif(array_key_exists('aktualizujKlubform',$_POST)){
                    aktualizujKlubform();
                }
                elseif(array_key_exists('sedziowie',$_POST)){
                    sedziowie();
                }
                elseif(array_key_exists('dodajSedziegoform',$_POST)){
                    dodajSedziegoform();
                }
                elseif(array_key_exists('zwolnijSedziegoform',$_POST)){
                    zwolnijSedziegoform();
                }
                elseif(array_key_exists('stadiony',$_POST)){
                    stadiony();
                }
                elseif(array_key_exists('dodajStadionform',$_POST)){
                    dodajStadionform();
                }
                elseif(array_key_exists('koszulki',$_POST)){
                    koszulki();
                }
                elseif(array_key_exists('dodajKoszulkiform',$_POST)){
                    dodajKoszulkiform();
                }
               
                
                ?>
    </div>
</div>
    </body>
</html>