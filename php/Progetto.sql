SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";/*solo null fa incrementare i valori auto_increment*/
SET time_zone = "+01:00";

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Photo;
DROP TABLE IF EXISTS Characters;
DROP TABLE IF EXISTS Report;
DROP TABLE IF EXISTS Comments;
DROP TABLE IF EXISTS report_giocatore;
-- --------------------------------------------------------

--
-- Struttura della tabella 'User'
-- (manca user_img, ma non so se la implementeremo come url dentro varchar, o altro)

CREATE TABLE Users (
  username varchar(50) NOT NULL,
  name_surname varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  passwd varchar(50) NOT NULL,
  birthdate date NOT NULL,
  img_path varchar(50),
  PRIMARY KEY (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella 'Report'
--

CREATE TABLE Report (
  id int(50) NOT NULL AUTO_INCREMENT,
  title varchar(30) NOT NULL,
  subtitle varchar(80) NOT NULL,
  content varchar(1000) NOT NULL,
  author varchar(50) NOT NULL,
  isExplorable boolean NOT NULL,
  last_modified date NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (author) REFERENCES Users (username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ------------------------------------------------------

--
-- Struttura della tabella 'Photo'
--

CREATE TABLE Photo (
  id int(50) NOT NULL AUTO_INCREMENT,
  img_path varchar(50) NOT NULL,
  report int(50) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (report) REFERENCES Report (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella 'Character'
--

CREATE TABLE Characters (
  id int(50) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  race varchar(50) NOT NULL,
  class varchar(50) NOT NULL,
  background varchar(50) NOT NULL,
  alignment varchar(17) NOT NULL,
  traits varchar(1000) NOT NULL,
  ideals varchar(1000) NOT NULL,
  bonds varchar(1000) NOT NULL,
  flaws varchar(1000) NOT NULL,
  author varchar(50) NOT NULL,
  creation_date timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (id),
  FOREIGN KEY (author) REFERENCES Users (username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella 'Comments'
--

CREATE TABLE Comments (
  id int(50) NOT NULL AUTO_INCREMENT,
  tex varchar(5000) DEFAULT NULL,
  date_time timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  author varchar(50) NOT NULL,
  report int(50) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (author) REFERENCES Users (username) ON DELETE CASCADE,
  FOREIGN KEY (report) REFERENCES Report (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella report-giocatore

CREATE TABLE report_giocatore (
  author varchar(50) NOT NULL,
  report int(50) NOT NULL,
  PRIMARY KEY (author, report),
  FOREIGN KEY (author) REFERENCES Users (username) ON DELETE CASCADE,
  FOREIGN KEY (report) REFERENCES Report (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
