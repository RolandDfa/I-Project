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
    [vraagnummer] TINYINT      NOT NULL,
    [tekstvraag]  VARCHAR (50) NULL,
    CONSTRAINT [PK_Vraag_vraagnummer] PRIMARY KEY CLUSTERED ([vraagnummer] ASC)
);

-- Gebruikers tabel
CREATE TABLE [dbo].[Gebruiker] (
    [gebruikersnaam] VARCHAR (50)  NOT NULL,
    [voornaam]       VARCHAR (50)  NOT NULL,
    [achternaam]     VARCHAR (50)  NOT NULL,
    [adresregel]     VARCHAR (255) NOT NULL,
    [postcode]       VARCHAR (7)   NOT NULL,
    [plaatsnaam]     VARCHAR (25)  NOT NULL,
    [Land]           VARCHAR (50)  NOT NULL,
	[kvkNummer]		 INT		   NOT NULL,
    [GeboorteDag]    DATE          NOT NULL,
    [Mailbox]        VARCHAR (50)  NOT NULL,
    [wachtwoord]     VARCHAR (30)  NOT NULL,
    [Vraag]          TINYINT       NOT NULL,
    [antwoordtekst]  VARCHAR (255) NOT NULL,
    [Verkoper]       BIT           NOT NULL,
    [Valid]          BIT           NOT NULL,
    CONSTRAINT [PK_Gebruiker_gebruikersnaam] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC),
    CONSTRAINT [FK_Gebruiker_Vraag_vraagnummer] FOREIGN KEY ([Vraag]) REFERENCES [dbo].[Vraag] ([vraagnummer]) ON UPDATE CASCADE
);

-- Gebruikerstelefoon tabel
CREATE TABLE [dbo].[Gebruikerstelefoon] (
    [volgnr]         INT          NOT NULL,
    [gebruikersnaam] VARCHAR (50) NOT NULL,
    [Telefoon]       VARCHAR (15) NOT NULL,
    CONSTRAINT [PK_Gebruikerstelefoon_volgnr_Gebruiker] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC, [volgnr] ASC),
    CONSTRAINT [FK_Gebruikerstelefoon_Gebruiker_gebruikersnaam] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Verkoper tabel
CREATE TABLE [dbo].[Verkoper] (
    [Gebruiker]     VARCHAR (50) NOT NULL,
    [Bank]          VARCHAR (25) NULL,
    [Bankrekening]  VARCHAR (18) NULL,
    [ControleOptie] VARCHAR (25) NOT NULL,
    [Creditcard]    VARCHAR (30) NULL,
    CONSTRAINT [PK_Verkoper_Gebruiker] PRIMARY KEY CLUSTERED ([Gebruiker] ASC),
    CONSTRAINT [FK_Verkoper_Gebruiker_gebruikersnaam] FOREIGN KEY ([Gebruiker]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]),
    CONSTRAINT [CHK_ControlePostOrCreditcard] CHECK ([ControleOptie]='Post' OR [ControleOptie]='Creditcard'),
    CONSTRAINT [CHK_BankOrCreditcard] CHECK ([Bankrekening] IS NOT NULL OR [Creditcard] IS NOT NULL),
    CONSTRAINT [CHK_CreditcardFilled] CHECK ([ControleOptie]='Creditcard' AND [Creditcard] IS NOT NULL OR [ControleOptie]<>'Creditcard' AND [Creditcard] IS NULL)
);

-- Voorwerp tabel
CREATE TABLE [dbo].[Voorwerp] (
    [voorwerpnummer]        NUMERIC (12)   IDENTITY (1, 1) NOT NULL,
    [titel]                 VARCHAR (200)  NOT NULL,
    [beschrijving]          VARCHAR (8000) NOT NULL,
    [startprijs]            NUMERIC (8, 2) NOT NULL,
    [betalingswijzenaam]    VARCHAR (10)   NOT NULL,
    [betalingsinstructie]   VARCHAR (30)   NULL,
    [plaatsnaam]            VARCHAR (25)   NOT NULL,
    [landnaam]              VARCHAR (50)   NOT NULL,
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
    [volgnr]        INT          NOT NULL,
    CONSTRAINT [PK_Rubriek_rubrieknummer] PRIMARY KEY CLUSTERED ([rubrieknummer] ASC),
    CONSTRAINT [FK_Rubriek_rubriek_rubrieknummer] FOREIGN KEY ([parent]) REFERENCES [dbo].[Rubriek] ([rubrieknummer])
);

--Voorwerp in rubriek tabel
CREATE TABLE [dbo].[Voorwerp_in_rubriek] (
    [voorwerpnummer]         NUMERIC (12) NOT NULL,
    [RubriekOpLaagsteNiveau] INT          NOT NULL,
    CONSTRAINT [PK_VoorwerpInRubriek_Voorwerp_Rubriek] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC, [RubriekOpLaagsteNiveau] ASC),
    CONSTRAINT [FK_VoorwerpInRubriek_Voorwerp_voorwerpnummer] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_VoorwerpInRubriek_RubriekOpLaagsteNiveau_rubrieknummer] FOREIGN KEY ([RubriekOpLaagsteNiveau]) REFERENCES [dbo].[Rubriek] ([rubrieknummer]) ON UPDATE CASCADE
);

-- Feedback tabel
CREATE TABLE [dbo].[Feedback] (
    [Voorwerp]        NUMERIC (12) NOT NULL,
    [Soort_Gebruiker] BIT          NOT NULL,
    [Feedbacksoort]   VARCHAR (10) NOT NULL,
    [Dag]             DATE         NOT NULL,
    [Tijdstip]        TIME (7)     NOT NULL,
    [commentaar]      VARCHAR (50) NOT NULL,
    CONSTRAINT [PK_Feedback_Voorwerp_Soort_Gebruiker] PRIMARY KEY CLUSTERED ([Voorwerp] ASC, [Soort_Gebruiker] ASC),
    CONSTRAINT [FK_Feedback_voorwerp_voorwerpnummer] FOREIGN KEY ([Voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE
);

-- Bod tabel
CREATE TABLE [dbo].[Bod] (
    [Voorwerp]    NUMERIC (12)   NOT NULL,
    [Bodbedrag]   NUMERIC (8, 2) NOT NULL,
    [Gebruiker]   VARCHAR (50)   NOT NULL,
    [BodDag]      DATE           NOT NULL,
    [BodTijdstip] TIME (7)       NOT NULL,
    CONSTRAINT [PK_Bod_Voorwerp_Bodbedrag] PRIMARY KEY CLUSTERED ([Voorwerp] ASC, [Bodbedrag] ASC),
    CONSTRAINT [FK_Bod_voorwerp_voorwerpnummer] FOREIGN KEY ([Voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON UPDATE CASCADE,
    CONSTRAINT [FK_Bod_gebruiker_gebruikersnaam] FOREIGN KEY ([Gebruiker]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON UPDATE CASCADE
);

-- Bestand tabel
CREATE TABLE [dbo].[Bestand] (
    [filenaam] VARCHAR (50) NOT NULL,
    [Voorwerp] NUMERIC (12) NOT NULL,
    CONSTRAINT [PK_Bestand_filenaam] PRIMARY KEY CLUSTERED ([filenaam] ASC),
    CONSTRAINT [FK_Bestand_voorwerp_voorwerpnummer] FOREIGN KEY ([Voorwerp]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE CASCADE ON UPDATE CASCADE
);









