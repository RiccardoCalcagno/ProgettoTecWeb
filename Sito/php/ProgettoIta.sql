SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";/*solo null fa incrementare i valori auto_increment*/
SET time_zone = "+01:00";

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS Utenti;
DROP TABLE IF EXISTS Foto;
DROP TABLE IF EXISTS Personaggi;
DROP TABLE IF EXISTS Report;
DROP TABLE IF EXISTS Commenti;
DROP TABLE IF EXISTS report_giocatore;
-- --------------------------------------------------------

--
-- Struttura della tabella 'Utenti'
-- (manca user_img, ma non so se la implementeremo come url dentro varchar, o altro)

CREATE TABLE Utenti (
  nome_utente varchar(50) NOT NULL,
  nome_cognome varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  passwd varchar(50) NOT NULL,
  dana_nascita date NOT NULL,
  img_path varchar(50),
  PRIMARY KEY (nome_utente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella 'Report'
--

CREATE TABLE Report (
  id int(50) NOT NULL AUTO_INCREMENT,
  titolo varchar(30) NOT NULL,
  sottotitolo varchar(80) NOT NULL,
  contenuto varchar(1000) NOT NULL,
  autore varchar(50) NOT NULL,
  isExplorable boolean NOT NULL,
  data_creazione date NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (autore) REFERENCES Utenti (nome_utente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ------------------------------------------------------

--
-- Struttura della tabella 'Foto'
--

CREATE TABLE Foto (
  id int(50) NOT NULL AUTO_INCREMENT,
  img_path varchar(50) NOT NULL,
  report int(50) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (report) REFERENCES Report (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella 'Personaggi'
--

CREATE TABLE Personaggi (
  id int(50) NOT NULL AUTO_INCREMENT,
  nome varchar(50) NOT NULL,
  razza varchar(50) NOT NULL,
  classe varchar(50) NOT NULL,
  background varchar(50) NOT NULL,
  allineamento varchar(10) NOT NULL,
  carattere varchar(150) NOT NULL,
  ideali varchar(150) NOT NULL,
  vincoli varchar(150) NOT NULL,
  difetti varchar(150) NOT NULL,
  autore varchar(50) NOT NULL,
  data_creazione date NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (autore) REFERENCES Utenti (nome_utente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella 'Commenti'
--

CREATE TABLE Commenti (
  id int(50) NOT NULL AUTO_INCREMENT,
  testo varchar(5000) DEFAULT NULL,
  data_ora timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  autore varchar(50) NOT NULL,
  report int(50) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (autore) REFERENCES Utenti (nome_utente),
  FOREIGN KEY (report) REFERENCES Report (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella report-giocatore

CREATE TABLE report_giocatore (
  autore varchar(50) NOT NULL,
  report int(50) NOT NULL,
  PRIMARY KEY (autore, report),
  FOREIGN KEY (autore) REFERENCES Utenti (nome_utente),
  FOREIGN KEY (report) REFERENCES Report (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
