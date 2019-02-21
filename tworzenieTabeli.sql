SET search_path to projektBD;

CREATE TABLE sedzia
(
	id_sedzia		serial         not null,
	imie		varchar(16)     not null,
	nazwisko	varchar(32)     not null
);

CREATE TABLE mecz
(
	id_mecz		serial             not null,
	data_meczu  date,
    id_sedzia   serial,
    id_gospodarz serial,
    id_gosc serial,
    bramki_gosc  int,
    bramki_gospodarz int,
    punkty_gosc int,
    punkty_gospodarz int,
    czy_rozegrano int      
);


CREATE TABLE klub
(
	id_klub     serial      ,
    nazwa       varchar(30)     not null,
    rok         int             not null,
    id_stadion  serial,
    id_zestaw1  serial
);

CREATE TABLE koszulki
(
    id_zestaw   serial             not null,
    kolor    varchar(30)        not null
);


CREATE TABLE zawodnik
(
    id_zawodnik serial             not null,
    imie        varchar(30)     not null,
    nazwisko    varchar(30)     not null,
    id_klub     serial
);


CREATE TABLE trener
(
    id_trener   serial             not null,
    imie  id      varchar(30)     not null,
    nazwisko    varchar(30)     not null,
    email       varchar(30)     not null,
    id_klub     serial
);

CREATE TABLE stadion
(
    id_stadion  serial             not null,
    miasto      varchar(30)     not null,
    ulica       varchar(30)     not null,
    numer       varchar(30)     not null,
    nazwa_stadionu      varchar(30)     not null
);

CREATE TABLE uzytkownicy(
    id_uzytkownik serial,
    nazwa_uzytkownika varchar(30),
    haslo varchar(30),
    funkcja varchar(30)
);

CREATE TABLE sukcesy
(
    id_zawodnik serial
);

CREATE TABLE rola(
    id_rola serial,
    rola varchar(30)
);

ALTER TABLE sukcesy ADD FOREIGN KEY (id_zawodnik) REFERENCES zawodnik (id_zawodnik);


ALTER TABLE trener ADD PRIMARY KEY (id_trener);
ALTER TABLE zawodnik  ADD PRIMARY KEY (id_zawodnik);
ALTER TABLE stadion ADD PRIMARY KEY (id_stadion);
ALTER TABLE klub ADD PRIMARY KEY (id_klub);
ALTER TABLE koszulki ADD PRIMARY KEY (id_zestaw);
ALTER TABLE mecz ADD PRIMARY KEY (id_mecz);
ALTER TABLE sedzia ADD PRIMARY KEY(id_sedzia);
ALTER TABLE rola ADD PRIMARY KEY(id_rola);

ALTER TABLE trener ADD FOREIGN KEY (id_klub) REFERENCES klub (id_klub);

ALTER TABLE zawodnik ADD FOREIGN KEY (id_klub) REFERENCES klub (id_klub);

ALTER TABLE klub ADD FOREIGN KEY (id_stadion) REFERENCES stadion (id_stadion);

ALTER TABLE klub ADD FOREIGN KEY (id_zestaw1) REFERENCES koszulki (id_zestaw);

ALTER TABLE mecz ADD FOREIGN KEY (id_gosc) REFERENCES klub (id_klub);
ALTER TABLE mecz ADD FOREIGN KEY (id_gospodarz) REFERENCES klub (id_klub);
ALTER TABLE mecz ADD FOREIGN KEY (id_sedzia) REFERENCES sedzia (id_sedzia);
ALTER TABLE trener ADD FOREIGN KEY (id_rola) REFERENCES rola (id_rola);

