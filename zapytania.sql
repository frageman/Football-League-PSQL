--Widok tabeli ligowej --
Create view widok as 
    SELECT
    k.nazwa,
    SUM(CASE when k.id_klub = m.id_gospodarz then m.punkty_gospodarz else 0 end) + SUM(CASE when k.id_klub = m.id_gosc then m.punkty_gosc else 0 end) as punkty, 
    SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gospodarz else 0 end) + SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gosc else 0 end) as bramkistrzelone,
    SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gosc else 0 end) + SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gospodarz else 0 end) as bramkistracone,
    SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gospodarz else 0 end) + SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gosc else 0 end) - SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gosc else 0 end) - SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gospodarz else 0 end) as bilans,
    SUM(CASE when k.id_klub = m.id_gospodarz and m.punkty_gospodarz = 3 then 1 else 0 end) + SUM(CASE when k.id_klub = m.id_gosc and m.punkty_gosc = 3 then 1 else 0 end) as wygrane,
    SUM(CASE when k.id_klub = m.id_gospodarz and m.punkty_gospodarz = 1 then 1 else 0 end) + SUM(CASE when k.id_klub = m.id_gosc and m.punkty_gosc = 1 then 1 else 0 end) as remisy,
    SUM(CASE when k.id_klub = m.id_gospodarz and m.punkty_gospodarz = 0 then 1 else 0 end) + SUM(CASE when k.id_klub = m.id_gosc and m.punkty_gosc = 0 then 1 else 0 end) as porazki
    from klub k, mecz m where m.czy_rozegrano = 1 group by k.nazwa order by punkty desc, bilans desc;

--Tabela meczow domowych--
Create view widokdomowy as 
    SELECT
    k.nazwa,
    SUM(CASE when k.id_klub = m.id_gospodarz then m.punkty_gospodarz else 0 end)  as punkty, 
    SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gospodarz else 0 end) as bramkistrzelone,
    SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gosc else 0 end) as bramkistracone,
    SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gospodarz else 0 end)  - SUM(CASE when k.id_klub = m.id_gospodarz then m.bramki_gosc else 0 end) as bilans,
    SUM(CASE when k.id_klub = m.id_gospodarz and m.punkty_gospodarz = 3 then 1 else 0 end) as wygrane,
    SUM(CASE when k.id_klub = m.id_gospodarz and m.punkty_gospodarz = 1 then 1 else 0 end) as remisy,
    SUM(CASE when k.id_klub = m.id_gospodarz and m.punkty_gospodarz = 0 then 1 else 0 end) as porazki
    from klub k, mecz m where m.czy_rozegrano = 1 group by k.nazwa order by punkty desc, bilans desc;

--Tabela meczow wyjazdowych --
Create view widokwyjazdowy as 
    SELECT
    k.nazwa,
    SUM(CASE when k.id_klub = m.id_gosc then m.punkty_gosc else 0 end)  as punkty, 
    SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gosc else 0 end) as bramkistrzelone,
    SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gospodarz else 0 end) as bramkistracone,
    SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gosc else 0 end)  - SUM(CASE when k.id_klub = m.id_gosc then m.bramki_gospodarz else 0 end) as bilans,
    SUM(CASE when k.id_klub = m.id_gosc and m.punkty_gosc = 3 then 1 else 0 end) as wygrane,
    SUM(CASE when k.id_klub = m.id_gosc and m.punkty_gosc = 1 then 1 else 0 end) as remisy,
    SUM(CASE when k.id_klub = m.id_gosc and m.punkty_gosc = 0 then 1 else 0 end) as porazki
    from klub k, mecz m where m.czy_rozegrano = 1 group by k.nazwa order by punkty desc, bilans desc;

--Forma klubow u siebie--
SELECT k.nazwa,
        CASE m.punkty_gospodarz
          When 3 then 'W'
          When 1 then 'R'
          when 0 then 'P'
          else 'brak'
        end as forma
        from klub k, mecz m where k.nazwa = 'WARTOSC Z FORMULARZA' and  k.id_klub = m.id_gosc and m.czy_rozegrano = 1 order by m.data_meczu desc;

--Forma klubow na wyjezdzie--
SELECT k.nazwa,
        CASE m.punkty_gosc
          When 3 then 'W'
          When 1 then 'R'
          when 0 then 'P'
          else 'brak'
        end as forma
        from klub k, mecz m where k.nazwa = 'WARTOSC Z FORMULARZA' and  k.id_klub = m.id_gosc and m.czy_rozegrano = 1 order by m.data_meczu desc limit 5;

--Zamienianie imion i nazwisk na duze litery przy wstawianiu danych--
CREATE OR REPLACE FUNCTION norm_data () RETURNS TRIGGER AS $$
BEGIN
  IF NEW.nazwisko IS NOT NULL THEN
     NEW.nazwisko := lower(NEW.nazwisko);
     NEW.nazwisko := initcap(NEW.nazwisko);
  END IF;
  IF NEW.imie IS NOT NULL THEN
     NEW.imie := lower(NEW.imie);
     NEW.imie := initcap(NEW.imie);
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER zawodnik_norm
  BEFORE INSERT OR UPDATE ON zawodnik
  FOR EACH ROW
  EXECUTE PROCEDURE norm_data();

CREATE TRIGGER trener_norm
  BEFORE INSERT OR UPDATE ON trener
  FOR EACH ROW
  EXECUTE PROCEDURE norm_data();

CREATE TRIGGER sedzia_norm
  BEFORE INSERT OR UPDATE ON sedzia
  FOR EACH ROW
  EXECUTE PROCEDURE norm_data();

  --walidacja emaila u trenera

CREATE OR REPLACE FUNCTION walidacja_email () RETURNS TRIGGER AS $$
BEGIN
  IF NEW.email NOT LIKE '%_@%_.__%' THEN
		NEW.email = 'Błędny adres!';
	END IF;
  RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER trener_email
  BEFORE INSERT OR UPDATE ON trener
  FOR EACH ROW
  EXECUTE PROCEDURE walidacja_email();

--Walidacja daty--
CREATE OR REPLACE FUNCTION walidacja_daty() RETURNS TRIGGER AS $$
BEGIN
  IF NEW.data_meczu = '' THEN
		SET NEW.data_meczu = '2000-01-01';
	END IF;
  RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER data_mecz
  BEFORE INSERT OR UPDATE ON mecz
  FOR EACH ROW
  EXECUTE PROCEDURE walidacja_daty();

  --Losowanie terminarza---
CREATE OR REPLACE FUNCTION losowanie()
BEGIN
SELECT k1.nazwa as klub1, k2.nazwa as klub2 from klub k1, klub k2 where k1.id_klub < k2.id_klub;
INSERT INTO mecz (id_gospodarz, id_gosc) values (klub1, klub2), (klub2, klub1);
END;
$$ LANGUAGE 'plpgsql';

--Dodawanie punktow w zaleznosci od liczby strzelonych i straconych bramek--
CREATE OR REPLACE FUNCTION bramki_data () RETURNS TRIGGER AS $$
BEGIN
  IF NEW.bramki_gosc < NEW.bramki_gospodarz THEN
     NEW.punkty_gosc = 0;
     NEW.punkty_gospodarz = 3;
  END IF;
  IF NEW.bramki_gospodarz < NEW.bramki_gosc THEN
     NEW.punkty_gosc = 3;
     NEW.punkty_gospodarz = 0;
  END IF;
  IF NEW.bramki_gospodarz = NEW.bramki_gosc THEN
     NEW.punkty_gosc = 1;
     NEW.punkty_gospodarz = 1;
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER bramki_mecz
  BEFORE INSERT OR UPDATE ON mecz
  FOR EACH ROW
  EXECUTE PROCEDURE bramki_data();

--Zliczanie liczby zawodnikow w danym klubie--
 SELECT k.nazwa, COUNT(*) FROM zawodnik z, klub k WHERE z.id_klub = k.id_klub group by k.nazwa ORDER BY k.nazwa

 --ZLiczanie liczby trenerow w klubie --
  SELECT k.nazwa, COUNT(*) FROM trener z, klub k WHERE z.id_klub = k.id_klub group by k.nazwa ORDER BY k.nazwa

  --Wyswietlanie listy klubow wraz ze stadionami--
  SELECT klub.nazwa, klub.rok, stadion.nazwa_stadionu, ks.kolor FROM klub , stadion , koszulki ks WHERE stadion.id_stadion = klub.id_stadion and ks.id_zestaw = klub.id_zestaw1 ORDER BY klub.rok;

  --Wyswietlanie terminarza rozgrywek --
   Select m.id_mecz, m.data_meczu, k1.nazwa, k2.nazwa, s.imie, s.nazwisko, st.nazwa_stadionu, m.bramki_gospodarz, m.bramki_gosc, m.czy_rozegrano from mecz m, klub k1, klub k2, sedzia s, stadion st where k1.id_klub = m.id_gospodarz and k2.id_klub = m.id_gosc and m.id_sedzia = s.id_sedzia and k1.id_stadion = st.id_stadion order by m.data_meczu;

