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

  

    //TERMINARZ

    function dodajTerminarzform(){
        echo " <h3 >Dodaj mecz do terminarza</h3>";
        $db = connect();
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM sedzia");
        $query3 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM klub");
        $query4 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM klub");
        echo"
        <form action = \"mecze/dodajTerminarz.php\" method = \"post\">";

        echo "Gospodarz: <select name='gospodarz'>";
        
        while($row = pg_fetch_array($query3))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select>';

        echo " Gość: <select name='gosc'>";
        
        while($row = pg_fetch_array($query4))
        {   
           echo "<option value=".$row[0].">".$row[1]."</option>";
        }
        echo '</select><br /><br />';
       
        echo "Sedzia:  <select name='sedzia'>";
        while($row = pg_fetch_array($query2))
        {   
           echo "<option value=".$row[0].">".$row[1]." ".$row[2]."</option>";
        }
        echo '</select>';
        $dzisiaj = date("Y-m-d");
        echo "
        <br /><br /> Data : <input type=\"date\" name =\"dataMeczu\" value='$dzisiaj'>
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";
    }

    function dodajWynikform(){
        echo " <h3 >Wprowadź wynik meczu</h3>";
       $db = connect();
        $query = pg_query($db, "SET SEARCH_PATH TO projektBD; Select m.id_mecz, k1.nazwa, k2.nazwa, m.czy_rozegrano from mecz m, klub k1, klub k2 where k1.id_klub = m.id_gospodarz and k2.id_klub = m.id_gosc order by m.id_gospodarz;");
        $query2 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT * FROM sedzia");
        $query3 = pg_query($db, "SET SEARCH_PATH TO projektBD; SELECT z.id_zawodnik, z.imie, z.nazwisko, k.nazwa FROM zawodnik z join klub k on z.id_klub = k.id_klub");
        echo"
        <form action = \"mecze/dodajWynik.php\" method = \"post\">";

        echo "Mecz: <select name='meczycho'>";
        
        while($row = pg_fetch_array($query))
        {  if( $row[3] == 0)
             echo "<option value=".$row[0].">".$row[1]." - ".$row[2]."</option>";
        }
        echo '</select>';
        echo "<br /><br />
        <input type=\"number\" name =\"bramkiGosp\" value = 0> : 
        <input type=\"number\" name =\"bramkiGosc\" value = 0> <br /><br /><br /><br />";

        echo "Sedzia:  <select name='sedzia'>";
        while($row = pg_fetch_array($query2))
        {   
           echo "<option value=".$row[0].">".$row[1]." ".$row[2]."</option>";
        }
        echo '</select>';
        $dzisiaj = date("Y-m-d");
        echo "
        <br /><br /> Data : <input type=\"date\" name =\"dataMeczu\" value='$dzisiaj'><br><br>";

        echo "Zawodnik meczu:  <select name='gwiazda'>";
        while($row = pg_fetch_array($query3))
        {   
           echo "<option value=".$row[0].">".$row[1]." ".$row[2].", ".$row[3]."</option>";
        }
        echo '</select>';
        
        echo"
        <input type=\"submit\" name =\"new\" value=\"Dodaj\">
      </form>";

    }

    function losujLigeForm(){

        echo "<h3>Uwaga! Czy chcesz wygenerować kolejną rundę rozgrywek? <br>
              Do terminarza zostanie dodana rudna domowa oraz wyjazdowa!</h3>";
        echo '<a href="./mecze/losujTerminarz.php"><input type="button" value="Stwórz nową rundę rozgrywek!" />';
    }

    function usunLigeForm(){

        echo "<h3>Uwaga! Zakończenie rozgrywek usuwa wszystkie bieżące mecze, terminarz oraz wyniki!<br>
                Czy chcesz zakończyć?</h3>";
        echo '<a href="./mecze/usunTerminarz.php"><input type="button" value="Zakończ bieżące rozgrywki!" /></a>';
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
                <tr><td width='50%'>".$kluby[$x]."</td>";
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
            
        <h3 >Centrum Sędziowskie</h3>
            <div class = "section">
        <input type="submit" name="sedziowie" value="Baza sędziów" /><br/>
            </div>
       

        <h3 >Liga</h3>
            <div class = "section">
            <input type="submit" name="losujLigeform" value="Generuj kolejną rundę rozgrywek!" /><br/>
            <input type="submit" name="usunLigeform" value="Zakończ bieżące rozgrywki!" /><br/><br/>
            <input type="submit" name="dodajTerminarzform" value="Dodaj własne mecze!" /><br/>
            <input type="submit" name="dodajWynikform" value="Dodaj wynik meczu!" /><br/>
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
                if(array_key_exists('sedziowie',$_POST)){
                    sedziowie();
                }
                elseif(array_key_exists('dodajTerminarzform',$_POST)){
                    dodajTerminarzform();
                }
                elseif(array_key_exists('dodajWynikform',$_POST)){
                    dodajWynikform();
                }
                elseif(array_key_exists('pokazTerminarzform',$_POST)){
                    pokazTerminarzform();
                }
                elseif(array_key_exists('usunLigeform',$_POST)){
                    usunLigeform();
                }
                elseif(array_key_exists('losujLigeform',$_POST)){
                    losujLigeform();
                }
                
                ?>
    </div>
</div>
    </body>
</html>