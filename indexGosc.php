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

   

    //KLUBY

    function kluby(){
        echo " <h3 >Kluby</h3>";
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




    
    //SĘDZIOWIE

    function sedziowie(){
        echo " <h3 >Sędziowie</h3>";
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



    //STADIONY

    function stadiony(){
        echo " <h3 >Stadiony</h3>";
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

   
    //BARWY


    function koszulki(){
        echo " <h3 >Barwy</h3>";
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

 
    

    function pokazTerminarzform(){
        echo " <h3 >Rozgrywki</h3>";
        $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; Select m.id_mecz, m.data_meczu, k1.nazwa, k2.nazwa, s.imie, s.nazwisko, st.nazwa_stadionu, m.bramki_gospodarz, m.bramki_gosc, m.czy_rozegrano from mecz m, klub k1, klub k2, sedzia s, stadion st where k1.id_klub = m.id_gospodarz and k2.id_klub = m.id_gosc and m.id_sedzia = s.id_sedzia and k1.id_stadion = st.id_stadion order by m.id_gospodarz;");
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; Select m.id_mecz, m.data_meczu, k1.nazwa, k2.nazwa, s.imie, s.nazwisko, st.nazwa_stadionu, m.bramki_gospodarz, m.bramki_gosc, m.czy_rozegrano from mecz m, klub k1, klub k2, sedzia s, stadion st where k1.id_klub = m.id_gospodarz and k2.id_klub = m.id_gosc and m.id_sedzia = s.id_sedzia and k1.id_stadion = st.id_stadion order by m.data_meczu;");

        echo"
        <form action = \"mecze/dodajWynik.php\" method = \"post\">";

        echo "<h3>Terminarz</h3>";
        
        while($row = pg_fetch_array($query))
        {  if( $row[9] == 0)
             echo " ".$row[2]." - ".$row[3]. " Stadion: ".$row[6]." Wynik: - : - </br>";
        }
        echo '</br> </br>';

        echo "<h3>Mecze rozegrane</h3>";
        
        while($row = pg_fetch_array($query2))
        {  if( $row[9] == 1)
             echo "".$row[1]." ".$row[2]." - ".$row[3]. " Sędzia: ".$row[4]." " .$row[5]." Stadion: ".$row[6]." Wynik: ".$row[7].":".$row[8]."</br>";
        }
        echo '</br></br>';

        $query3 = 'SET SEARCH_PATH TO projektBD; SELECT * FROM widok;';
        $result = pg_query($query3) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<h3>Tabela rozgrywek</h3><table>\n
                <tr><td>Druzyna</td><td>Punkty</td><td>Bramki strzelone</td><td>Bramki stracone</td><td>Bilans bramek</td><td>Wygrane</td><td>Remisy</td><td>Porażki</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";
        
        pg_free_result($result);

        $query4 = 'SET SEARCH_PATH TO projektBD; SELECT * FROM widokdomowy;';
        $result = pg_query($query4) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<h3>Mecze u siebie</h3><table>\n
                <tr><td>Druzyna</td><td>Punkty</td><td>Bramki strzelone</td><td>Bramki stracone</td><td>Bilans bramek</td><td>Wygrane</td><td>Remisy</td><td>Porażki</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";

        pg_free_result($result);

        $query5 = 'SET SEARCH_PATH TO projektBD; SELECT * FROM widokwyjazdowy;';
        $result = pg_query($query5) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        
        echo "<h3>Mecze na wyjeździe</h3><table>\n
                <tr><td>Druzyna</td><td>Punkty</td><td>Bramki strzelone</td><td>Bramki stracone</td><td>Bilans bramek</td><td>Wygrane</td><td>Remisy</td><td>Porażki</td></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";

        pg_free_result($result);

        $kluby;
        $i = 0;

        $query7 = 'SET SEARCH_PATH TO projektBD; SELECT k.nazwa as id FROM klub k;';
        $result = pg_query($query7) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $kluby[$i] = $line['id'];
            $i++;
        }
        pg_free_result($result);

        echo"<h3>Forma drużyny z 5 ostatnich meczy u siebie (W - wygrana, R - remis, P - przegrana)</h3>";
        for($x=0; $x< $i; $x++){
        $query6 = "SET SEARCH_PATH TO projektBD; SELECT k.nazwa,
        CASE m.punkty_gospodarz
          When 3 then 'W'
          When 1 then 'R'
          when 0 then 'P'
          else 'brak'
        end as forma
        from klub k, mecz m where k.nazwa = '$kluby[$x]' and  k.id_klub = m.id_gospodarz and m.czy_rozegrano = 1 order by m.data_meczu desc limit 5;";
        $result = pg_query($query6) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
       
        echo "<table>
                <tr><td width='50%'>".$kluby[$x]."</td>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                echo "<td>".$line['forma']."</td>\n";
        }
        echo "</tr></table>\n";
    }

    echo"<h3>Forma drużyny z 5 ostatnich meczy wyjazdowych (W - wygrana, R - remis, P - przegrana)</h3>";
        for($x=0; $x< $i; $x++){
        $query6 = "SET SEARCH_PATH TO projektBD; SELECT k.nazwa,
        CASE m.punkty_gosc
          When 3 then 'W'
          When 1 then 'R'
          when 0 then 'P'
          else 'brak'
        end as forma
        from klub k, mecz m where k.nazwa = '$kluby[$x]' and  k.id_klub = m.id_gosc and m.czy_rozegrano = 1 order by m.data_meczu desc limit 5;";
        $result = pg_query($query6) or die('Nieprawidłowe zapytanie: ' . pg_last_error());
       
        echo "<table>
                <tr><td width='50%''>".$kluby[$x]."</td>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                echo "<td>".$line['forma']."</td>\n";
        }
        echo "</tr></table>\n";
    }
        echo "
        
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
        
            <div class = "section">
            <input type="submit" name="zawodnicy" value="Baza zawodników" /><br/>
            <input type="submit" name="trenerzy" value="Baza trenerów" /><br/>
            <input type="submit" name="kluby" value="Lista klubów" /><br/>
            <input type="submit" name="sedziowie" value="Baza sędziów" /><br/>
            <input type="submit" name="stadiony" value="Obiekty sportowe" /><br/>
            <input type="submit" name="koszulki" value="Dostępne wzory" /><br/>
            <input type="submit" name="pokazTerminarzform" value="Terminarz i tabela rozgrywek!" /><br/>
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
                elseif(array_key_exists('trenerzy',$_POST)){
                    trenerzy();
                }
                elseif(array_key_exists('kluby',$_POST)){
                    kluby();
                }
                elseif(array_key_exists('sedziowie',$_POST)){
                    sedziowie();
                }
                elseif(array_key_exists('stadiony',$_POST)){
                    stadiony();
                }
                elseif(array_key_exists('koszulki',$_POST)){
                    koszulki();
                }
                elseif(array_key_exists('pokazTerminarzform',$_POST)){
                    pokazTerminarzform();
                }
                
                ?>
    </div>
</div>
    </body>
</html>