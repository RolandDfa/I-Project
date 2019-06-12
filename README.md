# I-Project groep 41

*Casus EenmaalAndermaal, veilingwebsite*

## Installeer instructies
Download een ftp applicatie om toegang te krijgen tot de server, bijvoorbeeld FileZilla of cyberduck.
-	FileZilla download pagina https://filezilla-project.org/
-	Cyberduck download pagina https://cyberduck.io/
Na de installatie van een ftp applicatie naar keuze, start de applicatie en log in met de juiste server gegevens.
In Cyberduck krijg je dit overzicht van de inhoud van de betrekkelijke server te zien.
 
![alt text](https://github.com/RolandDfa/I-Project/blob/master/Images/Cyberduck_Preview.png "Cyberduck server")

Afbeelding 1: Cyberduck server

Upload nu de geleverde website bestanden uit de zip. Dit zijn alle benodigde HTML, CSS en PHP onderdelen. Download nu SQL Server Management Studio (SSMS) hier:  https://docs.microsoft.com/en-us/sql/ssms/download-sql-server-management-studio-ssms?view=sql-server-2017
Gebruik na het installeren van SSMS de meegeleverde sql create-scripts om de database te creëren. Vervolgens moeten de insert-script uitgevoerd worden om de database te voorzien van data.
Nu is de site operationeel maar er moeten nog een of meerdere beheerders worden gecreëerd. Dit kan worden gedaan door het aanmaken van een normaal account op de site, en daarna in SSMS een Maak_gebruiker_beheerder sql script uit te voeren. Vul hier de gebruikersnaam van de account in en voer de query uit om de account beheersfuncties te geven.

## Test accounts					
| Gebruiker         | Wachtwoord    | Gebruikersstatus |
| :-----------------|:--------------| :----------------|
| **ProductOwner1** | *Wachtwoord1* | `gebruiker`      |
| **ProductOwner2** | *Wachtwoord2* | `Verkoper`       |
| **ProductOwner3** | *Wachtwoord3* | `Beheerder`      |

## Gebruik beheerdersapplicatie
De beheerdersapplicatie is als volgt te gebruiken:
1. Log in met een beheerdersaccount.
2. Klik op het dropdown-menu met uw gebruikersnaam (zie plaatje hieronder)
![alt text](https://github.com/RolandDfa/I-Project/blob/master/Images/Open_Dropdown.gif "Opening dropdown menu")
3. Hier krijgt u de keuze voor de verschillende beheerdersfuncties
  * Klik op 'Beheerdersdashboard' om de website prestaties te bekijken
  * Klik op 'Accounts beheren' om account te beheren
  * Klik op 'Veilingen beheren' om veilingen te beheren
  * Klik op 'Rubrieken beheren' om rubrieken te beheren

### Beheerdersdashboard
Het beheerdersdashboard ziet er als volgt uit:
![alt text](https://github.com/RolandDfa/I-Project/blob/master/Images/Beheerdersdashboard.png "Beheerdersdashboard")
### Accounts beheren
Het accounts beheren ziet er als volgt uit:
![alt text](https://github.com/RolandDfa/I-Project/blob/master/Images/Accounts_beheren.png "Accounts beheren")
### Veilingen beheren
Het veilingen beheren ziet er als volgt uit:
![alt text](https://github.com/RolandDfa/I-Project/blob/master/Images/Veilingen_beheren.png "Veilingen beheren")
### Rubrieken beheren
Het rubrieken beheren ziet er als volgt uit:
![alt text](https://github.com/RolandDfa/I-Project/blob/master/Images/Hoofdrubrieken_beheren.png "Hoofdrubrieken beheren")
![alt text](https://github.com/RolandDfa/I-Project/blob/master/Images/Subrubrieken_beheren.png "Subrubrieken beheren")
