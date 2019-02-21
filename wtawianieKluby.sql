INSERT INTO klub(id_klub, nazwa, rok, id_stadion, id_zestaw1) values
(default, 'Abacki Team', 2011, 1, 2),
(default, 'Kalafiory', 2013, 2, 4),
(default, 'Drewniaki', 2015, 3, 2);

--ZAWODNICY I ICH KLUBY --
insert into zawodnik ( id_zawodnik, imie, nazwisko, id_klub) values 
( default, 'Marek', 'Grechuta', 11),
( default, 'Andrzej', 'Fonfara',11),
( default, 'Piotr', 'Bartczyk',11),
( default, 'Krzysztof', 'Sampa',11),
( default, 'Karol', 'Sztuka',12),
( default, 'Witold', 'Polanski',12),
( default, 'Jakub', 'Pawelek',12),
( default, 'Karol', 'Gwizdek',15),
( default, 'Pawel', 'Fortuna',13),
( default, 'Franc', 'Muda',13),
( default, 'Lech', 'Trapp',13),
( default, 'Damian', 'Angulo',13);


--TRENERZY I ICH KLUBY--
insert into trener ( id_trener, imie, nazwisko, telefon, email, id_klub) values 
( default, 'Adam', 'Nawałka', '555666777', 'pzpn@gmail.com', 1),
( default, 'Michał', 'Probież', '666111222', 'pasy@adb.pl', 3),
( default, 'Jerzy', 'Brzęczek', '893453123', 'jurek@ogorek.pl', 2);


--STADIONY--
insert into stadion (id_stadion, miasto, ulica, numer, nazwa_stadionu) values
(default, 'Krakow', 'Polna', '10', 'Wiselka'),
(default, 'Wieliczka', 'Wiejska', '12', 'Legijka'),
(default, 'Warszawa', 'Grodzka', '15A', 'Polonia');


--KOLORY KOSZULEK--
insert into koszulki (id_zestaw, kolor) values
(default, 'biały'),
(default, 'czarny'),
(default, 'czerwony'),
(default, 'niebieski'),
(default, 'pomarańczowy'),
(default, 'szary'),
(default, 'żółty'),
(default, 'zielony');



--SEDZIOWIE--
insert into sedzia ( id_sedzia, imie, nazwisko) values 
( default, 'Jan', 'Flisiowski'),
( default, 'Andrzej', 'Olech'),
( default, 'Piotr', 'Pochocki'),
( default, 'Krzysztof', 'Stachura');

insert into rola values
(default, 'selekcjoner'),
(default, 'fizjoterapeuta'),
(default, 'motywator'),
(default, 'psycholog'),
(default, 'trener bramkarzy'),
(default, 'taktyk');
