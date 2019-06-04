-------------------------------------------
--database create script
USE MASTER
GO
-- DROP DATABASE als het al bestaat
DROP DATABASE EenmaalAndermaal
--create database EenmaalAndermaal

CREATE DATABASE EenmaalAndermaal
GO
USE EenmaalAndermaal
GO

-- Vraag tabel
CREATE TABLE [dbo].[Vraag] (
    [vraagnummer] INT          IDENTITY (1, 1) NOT NULL,
    [tekstvraag]  VARCHAR (50) NOT NULL,
    CONSTRAINT [PK_Vraag_vraagnummer] PRIMARY KEY CLUSTERED ([vraagnummer] ASC)
);

-- GebruikersStatus tabel
CREATE TABLE [dbo].[Gebruikersstatus] (
    [gebruikersStatus_id]           INT           IDENTITY (1, 1) NOT NULL,
    [gebruikersStatus_omschrijving] VARCHAR (255) NOT NULL,
    CONSTRAINT [PK_GebruikersStatusid] PRIMARY KEY CLUSTERED ([gebruikersStatus_id] ASC)
);
-- Valuta Valuta
CREATE TABLE [dbo].[Valuta] (
    [Entity]              VARCHAR (58)   NOT NULL,
    [Currency]            VARCHAR (65)   NOT NULL,
    [AlphabeticCode]      VARCHAR (21)   NULL,
    [NumericCode]         VARCHAR (5)    NOT NULL,
    [MinorUnit]           VARCHAR (3)    NULL,
    [WithdrawalDate]      VARCHAR (18)   NULL,
    [currentExchangeToEU] NUMERIC (8, 5) NULL,
    PRIMARY KEY CLUSTERED ([Entity] ASC, [NumericCode] ASC)
);


-- Gebruikers tabel
CREATE TABLE [dbo].[Gebruiker] (
    [gebruikersnaam]   VARCHAR (50)  NOT NULL,
    [voornaam]         VARCHAR (50)  NOT NULL,
    [achternaam]       VARCHAR (50)  NOT NULL,
    [adresregel]       VARCHAR (255) NOT NULL,
    [postcode]         VARCHAR (7)   NOT NULL,
    [plaatsnaam]       VARCHAR (30)  NOT NULL,
    [land]             VARCHAR (10)  NOT NULL,
    [kvkNummer]        INT           NOT NULL,
    [geboorteDag]      DATE          NOT NULL,
    [mailbox]          VARCHAR (50)  NOT NULL,
    [wachtwoord]       VARCHAR (255) NOT NULL,
    [vraag]            INT           NOT NULL,
    [antwoordTekst]    VARCHAR (255) NOT NULL,
    [gebruikersStatus] INT           NOT NULL,
    [valid]            BIT           NOT NULL,
    CONSTRAINT [PK_Gebruiker_gebruikersnaam] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC),
    CONSTRAINT [FK_Gebruiker_Vraag_vraagnummer] FOREIGN KEY ([vraag]) REFERENCES [dbo].[Vraag] ([vraagnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_Gebruiker_gebruikersStatus_Status_id] FOREIGN KEY ([gebruikersStatus]) REFERENCES [dbo].[Gebruikersstatus] ([gebruikersStatus_id])
);

-- Gebruikerstelefoon tabel
CREATE TABLE [dbo].[Gebruikerstelefoon] (
    [volgnr]         INT          IDENTITY (1, 1) NOT NULL,
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
    [Valid]         BIT          NOT NULL,
    CONSTRAINT [PK_Verkoper_Gebruiker] PRIMARY KEY CLUSTERED ([gebruiker] ASC),
    CONSTRAINT [FK_Verkoper_Gebruiker_gebruikersnaam] FOREIGN KEY ([gebruiker]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]),
    CONSTRAINT [CHK_ControlePostOrCreditcard] CHECK ([ControleOptie]='Post' OR [ControleOptie]='Creditcard'),
    CONSTRAINT [CHK_BankOrCreditcard] CHECK ([bankrekening] IS NOT NULL OR [creditcard] IS NOT NULL),
    CONSTRAINT [CHK_CreditcardFilled] CHECK ([controleOptie]='Creditcard' AND [creditcard] IS NOT NULL OR [controleOptie]<>'Creditcard' AND [creditcard] IS NULL)
);

-- Voorwerp tabel
CREATE TABLE [dbo].[Voorwerp] (
    [voorwerpnummer]        BIGINT          IDENTITY (400808720558, 1) NOT NULL,
    [titel]                 VARCHAR (200)   NOT NULL,
    [beschrijving]          VARCHAR (MAX)   NOT NULL,
    [startprijs]            NUMERIC (12, 2) NOT NULL,
    [Valuta]                VARCHAR (5)     NULL,
    [betalingswijzenaam]    VARCHAR (10)    NOT NULL,
    [betalingsinstructie]   VARCHAR (30)    NULL,
    [plaatsnaam]            VARCHAR (25)    NOT NULL,
    [landnaam]              VARCHAR (10)    NOT NULL,
    [looptijd]              TINYINT         DEFAULT ((7)) NOT NULL,
    [looptijdbeginDag]      DATE            NOT NULL,
    [looptijdbeginTijdstip] TIME (7)        NOT NULL,
    [verzendkosten]         NUMERIC (8, 2)  NULL,
    [verzendinstructies]    VARCHAR (100)   NULL,
    [verkopernaam]          VARCHAR (50)    NOT NULL,
    [kopernaam]             VARCHAR (50)    NULL,
    [looptijdeindeDag]      AS              (dateadd(day,[looptijd],[looptijdbeginDag])),
    [looptijdeindeTijdstip] TIME (7)        DEFAULT (CONVERT([time],getdate())) NOT NULL,
    [veilingGesloten]       BIT             NOT NULL,
    [verkoopprijs]          NUMERIC (8, 2)  NULL,
    CONSTRAINT [PK_Voorwerp_voorwerpnummer] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC),
    CONSTRAINT [FK_Voorwerp_Verkoper_Gebruiker] FOREIGN KEY ([verkopernaam]) REFERENCES [dbo].[Verkoper] ([gebruiker]) ON UPDATE CASCADE,
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
    [voorwerpnummer]         BIGINT NOT NULL,
    [RubriekOpLaagsteNiveau] INT    NOT NULL,
    CONSTRAINT [PK_VoorwerpInRubriek_Voorwerp_Rubriek] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC, [RubriekOpLaagsteNiveau] ASC),
    CONSTRAINT [FK_VoorwerpInRubriek_Voorwerp_voorwerpnummer] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_VoorwerpInRubriek_RubriekOpLaagsteNiveau_rubrieknummer] FOREIGN KEY ([RubriekOpLaagsteNiveau]) REFERENCES [dbo].[Rubriek] ([rubrieknummer]) ON UPDATE CASCADE
);

-- Feedback tabel
CREATE TABLE [dbo].[Feedback] (
    [voorwerp]       BIGINT        NOT NULL,
    [gebruikersnaam] VARCHAR (50)  NOT NULL,
    [feedbackSoort]  VARCHAR (10)  NOT NULL,
    [dag]            DATE          NOT NULL,
    [tijdstip]       TIME (7)      NOT NULL,
    [commentaar]     VARCHAR (255) NOT NULL,
    CONSTRAINT [PK_Feedback_Voorwerp_Soort_Gebruiker] PRIMARY KEY CLUSTERED ([voorwerp] ASC, [gebruikersnaam] ASC),
    CONSTRAINT [FK_Feedback_voorwerp_voorwerpnummer] FOREIGN KEY ([voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_Feedback_gebruiker_gebruikersnaam] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON UPDATE CASCADE
);

-- Bod tabel
CREATE TABLE [dbo].[Bod] (
    [voorwerp]    BIGINT         NOT NULL,
    [bodbedrag]   NUMERIC (8, 2) NOT NULL,
    [gebruiker]   VARCHAR (50)   NOT NULL,
    [bodDag]      DATE           NOT NULL,
    [bodTijdstip] TIME (7)       NOT NULL,
    CONSTRAINT [PK_Bod_Voorwerp_Bodbedrag] PRIMARY KEY CLUSTERED ([voorwerp] ASC, [bodbedrag] ASC),
    CONSTRAINT [FK_Bod_voorwerp_voorwerpnummer] FOREIGN KEY ([voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_Bod_gebruiker_gebruikersnaam] FOREIGN KEY ([gebruiker]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON UPDATE CASCADE
);


GO
CREATE TRIGGER Minimaal_verhoging_bod ON Bod
FOR INSERT, UPDATE
AS 
BEGIN
	DECLARE @ID NUMERIC(12)
	SET @ID = (SELECT TOP 1 Voorwerp FROM inserted)
	DECLARE @BodBedrag NUMERIC(8,2)
	SET @BodBedrag = (SELECT BodBedrag FROM inserted)
	DECLARE @vorig_bod NUMERIC(8,2);
	SET @vorig_bod = (SELECT TOP 1 Bodbedrag FROM Bod WHERE Bodbedrag NOT IN (SELECT TOP 1 Bodbedrag FROM Bod WHERE Bod.Voorwerp = @ID ORDER BY Bodbedrag DESC) AND Bod.Voorwerp = @ID ORDER BY Bodbedrag DESC);
	IF @vorig_bod>0.0
	BEGIN
		IF @BodBedrag>0.99 AND @BodBedrag > @vorig_bod --bigger than one and not first bid
		BEGIN
			IF @BodBedrag >0.99 AND @BodBedrag <50
			BEGIN
				IF @BodBedrag-@vorig_bod<0.50
				BEGIN
					RAISERROR ('Een bod tussen 1 en 50 Euro moet met minimaal 50 eurocent worden verhoogd',16,1);
					ROLLBACK
				END		
			END
			IF @BodBedrag>49.99 AND @BodBedrag<500
			BEGIN
				IF @BodBedrag-@vorig_bod<1.00
				BEGIN
					RAISERROR ('Een bod tussen 50 en 500 Euro moet met minimaal 1 euro worden verhoogd',16,1);
					ROLLBACK
				END		
			END
			IF @BodBedrag>499.99 AND @BodBedrag<1000
			BEGIN
				IF @BodBedrag-@vorig_bod<5.00
				BEGIN
					RAISERROR ('Een bod tussen 500 en 1000 Euro moet met minimaal 5 euro worden verhoogd',16,1);
					ROLLBACK
				END		
			END
			IF @BodBedrag>999.99 AND @BodBedrag<5000
			BEGIN
				IF @BodBedrag-@vorig_bod<10.00
				BEGIN
					RAISERROR ('Een bod tussen 1000 en 5000 Euro moet met minimaal 10 euro worden verhoogd',16,1);
					ROLLBACK
				END		
			END
			IF @BodBedrag>5000
			BEGIN
				IF @BodBedrag-@vorig_bod<50.00
				BEGIN
					RAISERROR ('Een bod vanaf 5000 Euro moet met minimaal 50 euro worden verhoogd',16,1);
					ROLLBACK
				END		
			END
		END
		ELSE
		BEGIN
			RAISERROR ('Bod is kleiner dan of gelijk aan huidige hoogste bod',16,1);
			ROLLBACK
		END
	END
END

-- Bestand tabel
CREATE TABLE [dbo].[Bestand] (
    [bestandsnaam] VARCHAR (50) NOT NULL,
    [Voorwerp]     BIGINT       NOT NULL,
    CONSTRAINT [PK_Bestand_filenaam] PRIMARY KEY CLUSTERED ([bestandsnaam] ASC),
    CONSTRAINT [FK_Bestand_voorwerp_voorwerpnummer] FOREIGN KEY ([Voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT [CHK_BestandLimiet] CHECK ([dbo].[BestandLimiet]([voorwerp])=(1))
);

-- tabel Email validatie
CREATE TABLE [dbo].[Email_validatie] (
    [gebruikersnaam] VARCHAR (50) NOT NULL,
    [code]           VARCHAR (50) NOT NULL,
    [valid_until]    DATE         NOT NULL,
    CONSTRAINT [PK_Email_validatie] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC, [code] ASC, [valid_until] ASC),
    CONSTRAINT [FK_Validatie_gebruikersnaam] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Verkoper] ([gebruiker])
);

-- tabel verficatiecode
CREATE TABLE [dbo].[verificatiecode] (
    [volgnr]          INT         IDENTITY (1, 1) NOT NULL,
    [registratiecode] VARCHAR (8) not NULL,
	[email]				VARCHAR(50)not null,
    [codetijd]        time(7)    not NULL,
    CONSTRAINT [PK_verificatiecode] PRIMARY KEY CLUSTERED ([volgnr] ASC)
);

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER FUNCTION [dbo].[BestandLimiet](@voorwerp BIGINT)
RETURNS BIT
AS
	BEGIN
		DECLARE @BestandLimiet BIT
			if(SELECT COUNT(voorwerp) FROM Bestand WHERE Voorwerp = @voorwerp) <= 4
			SET @BestandLimiet = 1
			ELSE
			SET @BestandLimiet = 0

			RETURN @BestandLimiet

			END

---------------------------------------------------------------------
-- INSERT DATA TO DATABASE
---------------------------------------------------------------------
