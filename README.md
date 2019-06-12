# I-Project groep 41

## Casus EenmaalAndermaal, veilingwebsite

Installeer instructies

Download een ftp applicatie om toegang te krijgen tot de server, bijvoorbeeld FileZilla of cyberduck.
-	FileZilla download pagina https://filezilla-project.org/
-	Cyberduck download pagina https://cyberduck.io/
Na de installatie van een ftp applicatie naar keuze, start de applicatie en log in met de juiste server gegevens.
In Cyberduck krijg je dit overzicht van de inhoud van de betrekkelijke server te zien.
 
Afbeelding 1: Cyberduck server
Upload nu de geleverde website bestanden uit de zip. Dit zijn alle benodigde HTML, CSS en PHP onderdelen. Download nu SQL Server Management Studio (SSMS) hier:  https://docs.microsoft.com/en-us/sql/ssms/download-sql-server-management-studio-ssms?view=sql-server-2017
Gebruik na het installeren van SSMS de meegeleverde sql create-scripts om de database te creëren. Vervolgens moeten de insert-script uitgevoerd worden om de database te voorzien van data.
Nu is de site operationeel maar er moeten nog een of meerdere beheerders worden gecreëerd. Dit kan worden gedaan door het aanmaken van een normaal account op de site, en daarna in SSMS een Maak_gebruiker_beheerder sql script uit te voeren. Vul hier de gebruikersnaam van de account in en voer de query uit om de account beheersfuncties te geven.





## Test accounts
gebruiker	    wachtwoord	Antwoord vraag	Status						
ProductOwner1	Wachtwoord1	leraar	        gebruiker						
ProductOwner1	Wachtwoord2	leraar	        Verkoper						
ProductOwner3	Wachtwoord3	leraar	        Beheerder						
