USE master
IF EXISTS(select * from sys.databases where name= 'EenmaalAndermaal')
DROP DATABASE EenmaalAndermaal

CREATE DATABASE EenmaalAndermaal

USE EenmaalAndermaal

CREATE TABLE Geheime_Vraag(
	VraagId					INTEGER			NOT NULL,
	Vraag					VARCHAR(100)	NOT NULL,
	CONSTRAINT pk_persoon	PRIMARY KEY (VraagId)	
)


CREATE TABLE Persoon(
	Id						INTEGER			NOT NULL,
	Email					VARCHAR(100)	NOT NULL,
	Gebruikersnaam			VARCHAR(100)	NOT NULL,
	Wachtwoord				VARCHAR(100)	NOT NULL,
	Voornaam				VARCHAR(50)		NOT NULL,
	Achternaam				VARCHAR(50)		NOT NULL,
	Adres					VARCHAR(50)		NOT NULL,
	Postcode				VARCHAR(10)		NOT NULL,
	Plaats					VARCHAR(50)		NOT NULL,
	Land					VARCHAR(50)		NOT NULL,
	Tel1					VARCHAR(15)		NOT NULL,
	Tel2					VARCHAR(15)			NULL,
	Geboortedatum			DATE			NOT NULL,
	Geheime_Vraag			INTEGER			NOT NULL,
	Antwoord				VARCHAR(100)	NOT NULL,
	Bank					VARCHAR(50)			NULL,
	RekeningNr				INTEGER				NULL,
	ControleVia				VARCHAR(50)			NULL,
	CreditcardNr			VARCHAR(50)			NULL,
	Status					INTEGER			NOT NULL,
	CONSTRAINT pk_persoon	PRIMARY KEY (Id)
	
)