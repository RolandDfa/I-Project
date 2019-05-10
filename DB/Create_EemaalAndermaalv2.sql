-------------------------------------------
--database create script
USE MASTER
GO
-- DROP DATABASE als het al bestaat
DROP DATABASE EenmaalAndermaal1
--create database EenmaalAndermaal

CREATE DATABASE EenmaalAndermaal1
GO
USE EenmaalAndermaal1
GO

-- Vraag tabel
CREATE TABLE [dbo].[Vraag] (
    [vraagnummer] TINYINT     NOT NULL,
    [tekstvraag]  VARCHAR (50) NULL,
    CONSTRAINT [PK_Vraag_vraagnummer] PRIMARY KEY CLUSTERED ([vraagnummer] ASC)
);

-- GebruikersStatus tabel
CREATE TABLE [dbo].[Gebruikersstatus] (
	[gebruikersStatus_id] INT IDENTITY(1,1) NOT NULL,
	[gebruikersStatus_omschrijving] VARCHAR(255) NOT NULL,
	CONSTRAINT [PK_GebruikersStatusid] PRIMARY KEY ([gebruikersStatus_id] ASC)
	);

-- Gebruikers tabel
CREATE TABLE [dbo].[Gebruiker] (
    [gebruikersnaam] VARCHAR (50)  NOT NULL,
    [voornaam]       VARCHAR (50)  NOT NULL,
    [achternaam]     VARCHAR (50)  NOT NULL,
    [adresregel]     VARCHAR (255) NOT NULL,
    [postcode]       VARCHAR (6)   NOT NULL,
    [plaatsnaam]     VARCHAR (30)  NOT NULL,
    [land]           VARCHAR (10)  NOT NULL,
	[kvkNummer]		 INT		   NOT NULL,
    [geboorteDag]    DATE          NOT NULL,
    [mailbox]        VARCHAR (50)  NOT NULL,
    [wachtwoord]     VARCHAR (MAX)  NOT NULL,
    [vraag]          TINYINT       NOT NULL,
    [antwoordTekst]  VARCHAR (255) NOT NULL,
    [Gebruikersstatus]	INT           NOT NULL,
    [valid]          BIT           NOT NULL,
    CONSTRAINT [PK_Gebruiker_gebruikersnaam] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC),
	CONSTRAINT [FK_Gebruiker_Vraag_vraagnummer] FOREIGN KEY ([Vraag]) REFERENCES [dbo].[Vraag] ([vraagnummer]) ON UPDATE CASCADE,
	CONSTRAINT [FK_Gebruiker_gebruikersStatus_Status_id] FOREIGN KEY ([Gebruikersstatus]) REFERENCES [dbo].[Gebruikersstatus] ([gebruikersStatus_id]),
   
);

-- Gebruikerstelefoon tabel
CREATE TABLE [dbo].[Gebruikerstelefoon] (
    [volgnr]         INT   IDENTITY(1,1)       NOT NULL,
    [gebruikersnaam] VARCHAR (50) NOT NULL,
    [Telefoon]       VARCHAR (15) NOT NULL,
    CONSTRAINT [PK_Gebruikerstelefoon_volgnr_Gebruiker] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC, [volgnr] ASC),
    CONSTRAINT [FK_Gebruikerstelefoon_Gebruiker_gebruikersnaam] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Verkoper tabel
CREATE TABLE [dbo].[Verkoper] (
    [gebruiker]     VARCHAR (50) NOT NULL,
    [bank]          VARCHAR (25) NULL,
    [bankrekening]  VARCHAR (18) NULL,
    [controleOptie] VARCHAR (25) NOT NULL,
    [creditcard]    VARCHAR (30) NULL,
    CONSTRAINT [PK_Verkoper_Gebruiker] PRIMARY KEY CLUSTERED ([Gebruiker] ASC),
    CONSTRAINT [FK_Verkoper_Gebruiker_gebruikersnaam] FOREIGN KEY ([Gebruiker]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]),
    CONSTRAINT [CHK_ControlePostOrCreditcard] CHECK ([ControleOptie]='Post' OR [ControleOptie]='Creditcard'),
    CONSTRAINT [CHK_BankOrCreditcard] CHECK ([Bankrekening] IS NOT NULL OR [Creditcard] IS NOT NULL),
    CONSTRAINT [CHK_CreditcardFilled] CHECK ([ControleOptie]='Creditcard' AND [Creditcard] IS NOT NULL OR [ControleOptie]<>'Creditcard' AND [Creditcard] IS NULL)
);

-- Voorwerp tabel
CREATE TABLE [dbo].[Voorwerp] (
    [voorwerpnummer]        INT   IDENTITY (1, 1) NOT NULL,
    [titel]                 VARCHAR (200)  NOT NULL,
    [beschrijving]          VARCHAR (MAX) NOT NULL,
    [startprijs]            NUMERIC (8, 2) NOT NULL,
    [betalingswijzenaam]    VARCHAR (10)   NOT NULL,
    [betalingsinstructie]   VARCHAR (30)   NULL,
    [plaatsnaam]            VARCHAR (25)   NOT NULL,
    [landnaam]              VARCHAR (10)   NOT NULL,
    [looptijd]              TINYINT        DEFAULT ((7)) NOT NULL,
    [looptijdbeginDag]      DATE           NOT NULL,
    [looptijdbeginTijdstip] TIME (7)       NOT NULL,
    [verzendkosten]         NUMERIC (8, 2) NULL,
    [verzendinstructies]    VARCHAR (100)  NULL,
    [verkopernaam]          VARCHAR (50)   NOT NULL,
    [kopernaam]             VARCHAR (50)   NULL,
    [looptijdeindeDag]      AS             (DATEADD(day,[looptijd],[looptijdbeginDag])),
    [looptijdeindeTijdstip] TIME (7)       DEFAULT (CONVERT([time],getdate())) NOT NULL,
    [veilingGesloten]       BIT            NOT NULL,
    [verkoopprijs]          NUMERIC (8, 2) NULL,
    CONSTRAINT [PK_Voorwerp_voorwerpnummer] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC),
    CONSTRAINT [FK_Voorwerp_Verkoper_Gebruiker] FOREIGN KEY ([verkopernaam]) REFERENCES [dbo].[Verkoper] ([Gebruiker]) ON UPDATE CASCADE,
    CONSTRAINT [FK_Voorwerp_Koper_gebruikersnaam] FOREIGN KEY ([kopernaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]),
    CONSTRAINT [CHK_looptijd] CHECK ([looptijd]=(10) OR [looptijd]=(7) OR [looptijd]=(5) OR [looptijd]=(3) OR [looptijd]=(1)),
    CONSTRAINT [CHK_betalingswijzenaam] CHECK ([betalingswijzenaam]='Contant' OR [betalingswijzenaam]='Bank/Giro' OR [betalingswijzenaam]='Anders')
);

-- Rubriek tabel
CREATE TABLE [dbo].[Rubriek] (
    [rubrieknummer] INT          NOT NULL,
    [rubrieknaam]   VARCHAR (50) NOT NULL,
    [parent]        INT          NULL,
    [volgNr]        INT          NOT NULL,
    CONSTRAINT [PK_Rubriek_rubrieknummer] PRIMARY KEY CLUSTERED ([rubrieknummer] ASC),
    CONSTRAINT [FK_Rubriek_rubriek_rubrieknummer] FOREIGN KEY ([parent]) REFERENCES [dbo].[Rubriek] ([rubrieknummer])
);

--Voorwerp in rubriek tabel
CREATE TABLE [dbo].[Voorwerp_in_rubriek] (
    [voorwerpnummer]         INT NOT NULL,
    [RubriekOpLaagsteNiveau] INT          NOT NULL,
    CONSTRAINT [PK_VoorwerpInRubriek_Voorwerp_Rubriek] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC, [RubriekOpLaagsteNiveau] ASC),
    CONSTRAINT [FK_VoorwerpInRubriek_Voorwerp_voorwerpnummer] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_VoorwerpInRubriek_RubriekOpLaagsteNiveau_rubrieknummer] FOREIGN KEY ([RubriekOpLaagsteNiveau]) REFERENCES [dbo].[Rubriek] ([rubrieknummer]) ON UPDATE CASCADE
);

-- Feedback tabel
CREATE TABLE [dbo].[Feedback] (
    [voorwerp]        INT	   NOT NULL,
    [gebruikersnaam] varchar(50)          NOT NULL,
    [feedbackSoort]   VARCHAR (10) NOT NULL,
    [dag]             DATE         NOT NULL,
    [tijdstip]        TIME (7)     NOT NULL,
    [commentaar]      VARCHAR (255) NOT NULL,
    CONSTRAINT [PK_Feedback_Voorwerp_Soort_Gebruiker] PRIMARY KEY CLUSTERED ([Voorwerp] ASC, [gebruikersnaam] ASC),
    CONSTRAINT [FK_Feedback_voorwerp_voorwerpnummer] FOREIGN KEY ([Voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_Feedback_gebruiker_gebruikersnaam] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([Gebruikersnaam]) ON UPDATE CASCADE
);
-- Bod tabel
CREATE TABLE [dbo].[Bod] (
    [voorwerp]    INT   NOT NULL,
    [bodbedrag]   NUMERIC (8, 2) NOT NULL,
    [gebruiker]   VARCHAR (50)   NOT NULL,
    [bodDag]      DATE           NOT NULL,
    [bodTijdstip] TIME (7)       NOT NULL,
    CONSTRAINT [PK_Bod_Voorwerp_Bodbedrag] PRIMARY KEY CLUSTERED ([Voorwerp] ASC, [Bodbedrag] ASC),
    CONSTRAINT [FK_Bod_voorwerp_voorwerpnummer] FOREIGN KEY ([Voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_Bod_gebruiker_gebruikersnaam] FOREIGN KEY ([Gebruiker]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON UPDATE CASCADE
);

-- Bestand tabel
CREATE TABLE [dbo].[Bestand] (
    [bestandsnaam] VARCHAR (50) NOT NULL,
    [Voorwerp]INT	NOT NULL,
    CONSTRAINT [PK_Bestand_filenaam] PRIMARY KEY CLUSTERED ([bestandsnaam] ASC),
    CONSTRAINT [FK_Bestand_voorwerp_voorwerpnummer] FOREIGN KEY ([Voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE CASCADE ON UPDATE CASCADE
);

---------------------------------------------------------------------
-- INSERT DATA TO DATABASE
---------------------------------------------------------------------
-- Vragen tabel
INSERT INTO [dbo].[Vraag] 
VALUES (1, 'Wat was uw eerste baan?'),
 (2, 'Hoe heette uw eerste huisdier?'),
 (3, 'Wat is de meisjesnaam uw moeder?'),
 (4, 'Wat is uw lievelingsgerecht?'),
  (5, 'Waar bent u geboren?');
-- GebruikerStatus tabel
INSERT INTO Gebruikersstatus
VALUES('Bezoeker'),
		('Klant'),
		('Verkoper'),
		('Administrator');

-- Gebruiker tabel
INSERT INTO [dbo].[Gebruiker] 
 VALUES ('Drogo', 'Eric', 'Onstenk', 'Oudegracht 164', '3511AC', 'Utrecht', 'Nederland', 12345, '2005-02-20', 'dra6onkiller@gmail.com', '811e7b1b41fe185b656b2373a12ab3a48ddfd9190997ea391b9e331dd20be516', 1, 'vakkenvuller', 1, 1),
 ('Jack1', 'Jack', 'Septi', 'Papegaaiweg 133', '7345DK', 'Wenum Wiesel', 'Nederland', 42598456, '2000-09-03', 'jackhiggins28@gmail.com', '786289ab443f89eaa406c6e135320a0d1a21d273f16a7478e253ea03cf0c9ce3', 4, 'Lasagne', 3, 1),
('James', 'Jesus', 'Mina', 'Spuitstraat 167', '7461CA', 'Rijsse', 'Nederland', 34567890, '2001-11-06', 'gamingguy@rocketmail.com', '5c5899efdb3c9494e95076395151d60aca37ba786ff08689394b8bb8cc9a4e4f', 3, 'Anita', 4, 0),
('Jones', 'Dyla', 'Nugger', 'Mozartlaan 136', '2625CX', 'Delft', 'Nederland', 23456, '2010-04-22', 'djoneshots123@gmail.com', '3822cb0c53621f844f09d6fcc654fac30b95e2941f547ba2b7d4476c6db51709', 2, 'Mickey', 2, 1)

--Gebruikers telefoon tabel
INSERT INTO Gebruikerstelefoon
VALUES('Drogo', '06-12345678'),
	('Jack1', '06-23456789'),
	('James', '0478-12345678')

-- Verkoper tabel
INSERT INTO Verkoper
VALUES('Drogo', 'Rabobank','NL83RABO012345678','Creditcard','12345')
-- Voorwerp tabel
INSERT INTO Voorwerp(titel,beschrijving,startprijs,betalingswijzenaam,plaatsnaam,landnaam,looptijd,looptijdbeginDag,looptijdbeginTijdstip,verkopernaam,looptijdeindeTijdstip,veilingGesloten)
 VALUES
 ('Laptop Asus','Deze laptop voldoet niet meer aan mijn eisen daarom bied ik het bij deze aan.',1.99,'Bank/Giro','Nijmegen','Nederland',DEFAULT,convert(date,getdate()),convert(time,getdate()),'Drogo','12:00:00.000',0),
 ('Laptop Lenovo','Deze laptop voldoet niet meer aan mijn eisen daarom bied ik het bij deze aan.',2.99,'Bank/Giro','Nijmegen','Nederland',5,convert(date,getdate()),convert(time,getdate()),'Drogo','12:00:00.000',0),
 ('Laptop Apple','Deze laptop voldoet niet meer aan mijn eisen daarom bied ik het bij deze aan.',6.99,'Bank/Giro','Sneek','Nederland',7,convert(date,getdate()),convert(time,getdate()),'Drogo','12:00:00.000',0),
 ('Bank zwart','Deze bank past niet meer bij mijn huis.',9.99,'Bank/Giro','Arnhem','Nederland',10,convert(date,getdate()),convert(time,getdate()),'Drogo','12:00:00.000',0),
 ('Bank groen','Afgelopen winter is mijn huis gekropen en nu past de bank niet meer.',9.99,'Bank/Giro','Venlo','Nederland',10,convert(date,getdate()),convert(time,getdate()),'Drogo','12:00:00.000',0),
 ('Bank geel','Last van geelzucht',9.99,'Bank/Giro','Arnhem','Nederland',10,convert(date,getdate()),convert(time,getdate()),'Drogo','12:00:00.000',0),
 ('Bank donkerwit (a.k.a. zwart)','Het is de schuld van de Rabobank!!!!!!!!!!!',9.99,'Bank/Giro','Boxmeer','Nederland',10,convert(date,getdate()),convert(time,getdate()),'Drogo','12:00:00.000',0)
-- Rubrieken tabel
 INSERT INTO Rubriek VALUES(1, 'Overige', NULL, 1),
							(2, 'Mulitmedia', NULL, 2),
							(3, 'Bouw en doe het zelf', NULL, 3),
							(4, 'Bedrijfsinventaris', NULL, 4),
							(5, 'Auto''s en overig vervoer', NULL, 5),
							(6, 'Agrarisch', NULL, 6),
							(7, 'Audio en TV', 2, 7),
							(8, 'Batterijen, accu''s en laders', 2, 8),
							(9, 'Bekabeling', 2, 9),
							(10, 'Computers', 2, 10),
							(11, 'Foto en video', 2, 11),
							(12, 'Hardware', 2, 12),
							(13, 'Monitore', 2, 13),
							(14, 'Overige', 2, 14),
							(15, 'Printers en kopieerapparate', 2, 15),
							(16, 'Professioneel AVL', 2, 16),
							(17, 'Tablets', 2, 17),
							(18, 'Telefonie', 2, 18),
							(19, 'Camera''s', 11, 19),
							(20, 'Fotostudio accesoires', 11, 20),
							(21, 'Standaarde', 11, 21),
							(22, 'Aggregaten, stroomkasten en kabels', 3, 22),
							(23, 'Bebording en afzetmateriaal', 3, 23),
							(24, 'Compressors', 3, 24),
							(25, 'Gereedschap', 3, 25),
							(26, 'Meetinstrumente', 3, 26),
							(27, 'Nat- en droogzuigers', 3, 27),
							(28, 'Overige', 3, 28),
							(29, 'Pompe', 3, 29),
							(30, 'Trappen, ladders en steigers', 3, 30),
							(31, 'Haspels', 22, 31),
							(32, 'Omvormer', 22, 32),
							(33, 'Verlengkabel', 22, 33),
							(34, 'Hekke', 23, 34),
							(35, 'Elektrisch gereedschap', 25, 35),
							(36, 'Handgereedschap', 25, 36),
							(37, 'Pneumatisch gereedschap', 25, 37),
							(38, 'Lasers', 26, 38),
							(39, 'Temperatuurmeters', 26, 39),
							(40, 'Waterpasse', 26, 40),
							(41, 'Stofzuigers', 27, 41),
							(42, 'Beautysalo', 4, 42),
							(43, 'Betaalsysteme', 4, 43),
							(44, 'Beveiliging', 4, 44),
							(45, 'BHV', 4, 45),
							(46, 'Garage inventaris', 4, 46),
							(47, 'Garderobe, Kantine', 4, 47),
							(48, 'Horeca', 4, 48),
							(49, 'Intern transport', 4, 49),
							(50, 'Kantoor', 4, 50),
							(51, 'Koffiemachines en drankautomate', 4, 51),
							(52, 'Magazijnkasten en stellinge', 4, 52),
							(53, 'Overige', 4, 53),
							(54, 'Reclamemateriaal', 4, 54),
							(55, 'Reiniging', 4, 55),
							(56, 'Vlaggenmasten en benodigdhede', 4, 56),
							(57, 'Werkbanke', 4, 57),
							(58, 'Werkplaatsinventaris', 4, 58),
							(59, 'Winkel', 4, 59),
							(60, 'Apparatuur', 42, 60),
							(61, 'Massagetafels', 42, 61),
							(62, 'Kassa''s', 43, 62),
							(63, 'Sorteermachine', 43, 63),
							(64, 'Alarmsysteme', 44, 64),
							(65, 'Camera''s', 44, 65),
							(66, 'Brandblussers', 45, 66),
							(67, 'EHBO-doze', 45, 67),
							(68, 'Apparatuur', 46, 68),
							(69, 'Brugge', 46, 69),
							(70, 'Krikke', 46, 70),
							(71, 'Meters', 46, 71),
							(72, 'Opbergmogelijkhede', 46, 72),
							(73, 'Pompe', 46, 73),
							(74, '2-kolomsbrugge', 69, 74),
							(75, '4-kolomsbrugge', 69, 75),
							(76, 'Kapstokke', 47, 76),
							(77, 'Stoele', 47, 77),
							(78, 'Tafels', 47, 78),
							(79, 'Machines', 48, 79),
							(80, 'Servies', 48, 80),
							(81, 'Bestek', 80, 81),
							(82, 'Borde', 80, 82),
							(83, 'Palletwage', 49, 83),
							(84, 'Steekwagens', 49, 84),
							(85, 'Transportkarre', 49, 85),
							(86, 'Apparatuur', 50, 86),
							(87, 'Bureau''s', 50, 87),
							(88, 'Opbergmogelijkhede', 50, 88),
							(89, 'Stoele', 50, 89),
							(90, 'Drankautomate', 51, 90),
							(91, 'Koffiemachines', 51, 91),
							(92, 'Soepautomate', 51, 92),
							(93, 'Kaste', 52, 93),
							(94, 'Rekke', 52, 94),
							(95, 'Stellinge', 52, 95),
							(96, 'Verrijdbaar', 94, 96),
							(97, 'Reclameborde', 54, 97),
							(98, 'Spandoekframe''s', 54, 98),
							(99, 'Ultrasoniche reinigers', 55, 99),
							(100, 'Veegmachines', 55, 100),
							(101, 'Aanhangwagens en accessoires', 5, 101),
							(102, 'Auto accessoires', 5, 102),
							(103, 'Auto onderdele', 5, 103),
							(104, 'Bedrijfsauto''s', 5, 104),
							(105, 'overige', 5, 105),
							(106, 'Achteruitrijcamera''s', 102, 106),
							(107, 'Navigatiesysteme', 102, 107),
							(108, 'Verlichting', 102, 108),
							(109, 'Afrasteringsmateriaal', 6, 109),
							(110, 'Gewasbescherming en bemesting', 6, 110),
							(111, 'Oogstmachines', 6, 111),
							(112, 'Overige', 6, 112),
							(113, 'Parkmachines', 6, 113),
							(114, 'Tractoren en toebehore', 6, 114),
							(115, 'Veeteelt', 6, 115);








