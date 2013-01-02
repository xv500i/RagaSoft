DROP TABLE IF EXISTS Llar CASCADE;
CREATE TABLE Llar
(
	adreca VARCHAR(50) NOT NULL,
	contrasenya VARCHAR(15) NOT NULL,
	usuari VARCHAR(15) NOT NULL,
	periodeConfirmacio INTEGER NOT NULL,
	PRIMARY KEY (usuari)

) ;

DROP TABLE IF EXISTS Resident CASCADE;
CREATE TABLE Resident
(
	horaArribada TIME NOT NULL,
	idRfid VARCHAR(50) NOT NULL,
	nom VARCHAR(50) NOT NULL,
	teCaigudaActivada BOOL NOT NULL,
	teTardancaActivada BOOL NOT NULL,
	teMancaActivada BOOL NOT NULL,
	tePermanenciaActivada BOOL NOT NULL,
	usuariLlar VARCHAR(15) NOT NULL,
	PRIMARY KEY (idRfid),
	KEY (usuariLlar)

) ;


ALTER TABLE Resident ADD CONSTRAINT FK_Resident_Llar
	FOREIGN KEY (usuariLlar) REFERENCES Llar (usuari)
	ON DELETE CASCADE ON UPDATE CASCADE;




DROP TABLE IF EXISTS Cuidador CASCADE;
CREATE TABLE Cuidador
(
	telefon VARCHAR(15) NOT NULL,
	nom VARCHAR(50) NOT NULL,
	usuariLlar VARCHAR(50) NOT NULL,
	PRIMARY KEY (telefon)

) ;

ALTER TABLE Cuidador ADD CONSTRAINT FK_Cuidador_Llar
	FOREIGN KEY (usuariLlar) REFERENCES Llar (usuari)
	ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS Contactes CASCADE;
CREATE TABLE Contactes
(
	telefon VARCHAR(15) NOT NULL,
	descripcio VARCHAR(30) NOT NULL,
	PRIMARY KEY (telefon)

) ;


DROP TABLE IF EXISTS Emergencia CASCADE;
CREATE TABLE Emergencia
(
	moment DATETIME NOT NULL,
	tipus VARCHAR(10) NOT NULL,
	usuariLlar VARCHAR(15) NULL,
	idRfidResident VARCHAR(50) NULL,
	PRIMARY KEY (moment),
	KEY (usuariLlar),
	KEY (idRfidResident)

) ;


ALTER TABLE Emergencia ADD CONSTRAINT FK_Emergencia_Llar
	FOREIGN KEY (usuariLlar) REFERENCES Llar (usuari)
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE Emergencia ADD CONSTRAINT FK_Emergencia_Resident
	FOREIGN KEY (idRfidResident) REFERENCES Resident (idRfid)
	ON DELETE CASCADE ON UPDATE CASCADE;


DROP TABLE IF EXISTS Notificacio CASCADE;
CREATE TABLE Notificacio
(
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	idCuidador VARCHAR(15) NOT NULL,
	momentEmergencia DATETIME NOT NULL,
	confirmada BOOL NOT NULL,
	esPotConfirmar BOOL NOT NULL,
	PRIMARY KEY (id),
	KEY (idCuidador),
	KEY (momentEmergencia)

) ;


ALTER TABLE Notificacio ADD CONSTRAINT FK_Notificacio_Cuidador
	FOREIGN KEY (idCuidador) REFERENCES Cuidador (telefon)
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE Notificacio ADD CONSTRAINT FK_Notificacio_Emergencia
	FOREIGN KEY (momentEmergencia) REFERENCES Emergencia (moment)
	ON DELETE CASCADE ON UPDATE CASCADE;
