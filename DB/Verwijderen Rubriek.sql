ALTER procedure [dbo].[Verwijderen_rubriek]

 @parent_id int,
 @rubrieknummer int
as 

BEGIN
	DECLARE @hoogste_rubriek int
IF  EXISTS (SELECT * FROM Rubriek WHERE rubrieknummer = @rubrieknummer)
	BEGIN
		IF  EXISTS (SELECT * FROM Rubriek WHERE parent = @parent_id and rubrieknaam like '%Overig%' )
		BEGIN
			DECLARE @tempRubriekNummer int
			SET @tempRubriekNummer = (SELECT top 1 rubrieknummer FROM Rubriek WHERE parent = @parent_id and rubrieknaam like '%Overig%')
			BEGIN TRY
			
			UPDATE Voorwerp_in_rubriek
				set RubriekOpLaagsteNiveau = @tempRubriekNummer
				 WHERE voorwerpnummer in (select v1.voorwerpnummer 
					from Rubriek as node
						left outer 
						  join Rubriek as up1 
							on up1.rubrieknummer = node.parent  
						left outer 
						  join Rubriek as up2
							on up2.rubrieknummer = up1.parent  
						left outer 
						  join Rubriek as up3
							on up3.rubrieknummer = up2.parent
						left outer 
						  join Rubriek as up4
							on up4.rubrieknummer = up3.parent
						left outer 
						  join Rubriek as up5
							on up5.rubrieknummer = up4.parent
						inner join 
							 Voorwerp_in_rubriek as V1
							 on node.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where node.rubrieknummer  = @rubrieknummer 
							or up1.rubrieknummer = @rubrieknummer 
							or up2.rubrieknummer = @rubrieknummer 
							or up3.rubrieknummer = @rubrieknummer 
							or up4.rubrieknummer = @rubrieknummer)


			END TRY  
			BEGIN CATCH
			EXECUTE usp_GetErrorInfo;    
			END CATCH 
		END
		ELSE
			BEGIN
				SET @hoogste_rubriek = (SELECT MAX(rubrieknummer) FROM Rubriek)
				INSERT INTO Rubriek(rubrieknummer, rubrieknaam, parent, volgNr) 
					VALUES(@hoogste_rubriek+1, 'Overig', @parent_id, @hoogste_rubriek+1)
			BEGIN TRY
				UPDATE Voorwerp_in_rubriek
				set RubriekOpLaagsteNiveau = @hoogste_rubriek+1
				 WHERE voorwerpnummer in (select v1.voorwerpnummer 
					from Rubriek as node
						left outer 
						  join Rubriek as up1 
							on up1.rubrieknummer = node.parent  
						left outer 
						  join Rubriek as up2
							on up2.rubrieknummer = up1.parent  
						left outer 
						  join Rubriek as up3
							on up3.rubrieknummer = up2.parent
						left outer 
						  join Rubriek as up4
							on up4.rubrieknummer = up3.parent
						left outer 
						  join Rubriek as up5
							on up5.rubrieknummer = up4.parent
						inner join 
							 Voorwerp_in_rubriek as V1
							 on node.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where node.rubrieknummer  = @rubrieknummer 
							or up1.rubrieknummer = @rubrieknummer 
							or up2.rubrieknummer = @rubrieknummer 
							or up3.rubrieknummer = @rubrieknummer 
							or up4.rubrieknummer = @rubrieknummer)
							-----------------
							-- delete node
							-----------------
					BEGIN TRY
						DELETE FROM Rubriek WHERE rubrieknummer in (select 
							node.rubrieknummer as node_name 
						  from Rubriek as node
						left outer 
						  join Rubriek as up1 
							on up1.rubrieknummer = node.parent  
						left outer 
						  join Rubriek as up2
							on up2.rubrieknummer = up1.parent  
						left outer 
						  join Rubriek as up3
							on up3.rubrieknummer = up2.parent
						left outer 
						  join Rubriek as up4
							on up4.rubrieknummer = up3.parent
						left outer 
						  join Rubriek as up5
							on up5.rubrieknummer = up4.parent
						inner join 
							 Voorwerp_in_rubriek as V1
							 on node.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where node.rubrieknummer = @rubrieknummer 
							or up1.rubrieknummer = @rubrieknummer 
							or up2.rubrieknummer = @rubrieknummer 
							or up3.rubrieknummer = @rubrieknummer 
							or up4.rubrieknummer = @rubrieknummer)
						END TRY  
						BEGIN CATCH  
							EXECUTE usp_GetErrorInfo;  
						END CATCH 
							-----------------
							-- delete up1
							-----------------
						BEGIN TRY
							DELETE FROM Rubriek WHERE rubrieknummer in (select 
							up1.rubrieknummer as node_name 
						  from Rubriek as up1
						
						left outer 
						  join Rubriek as up2
							on up2.rubrieknummer = up1.parent  
						left outer 
						  join Rubriek as up3
							on up3.rubrieknummer = up2.parent
						left outer 
						  join Rubriek as up4
							on up4.rubrieknummer = up3.parent
						left outer 
						  join Rubriek as up5
							on up5.rubrieknummer = up4.parent
						inner join 
							 Voorwerp_in_rubriek as V1
							 on up1.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where up1.rubrieknummer = @rubrieknummer 
							or up2.rubrieknummer = @rubrieknummer 
							or up3.rubrieknummer = @rubrieknummer 
							or up4.rubrieknummer = @rubrieknummer)
						END TRY  
						BEGIN CATCH
						EXECUTE usp_GetErrorInfo;    
						END CATCH 
							-----------------
							-- delete up2
							-----------------
						BEGIN TRY
							DELETE FROM Rubriek WHERE rubrieknummer in (select 
							up2.rubrieknummer as node_name 
						  from Rubriek as up2						 
						left outer 
						  join Rubriek as up3
							on up3.rubrieknummer = up2.parent
						left outer 
						  join Rubriek as up4
							on up4.rubrieknummer = up3.parent
						left outer 
						  join Rubriek as up5
							on up5.rubrieknummer = up4.parent
						inner join 
							 Voorwerp_in_rubriek as V1
							 on up2.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where up2.rubrieknummer = @rubrieknummer 
							or up3.rubrieknummer = @rubrieknummer 
							or up4.rubrieknummer = @rubrieknummer)
						END TRY 
						BEGIN CATCH  
						EXECUTE usp_GetErrorInfo;  
						END CATCH  
							-----------------
							-- delete up3
							-----------------
						BEGIN TRY
							DELETE FROM Rubriek WHERE rubrieknummer in (select 
							up3.rubrieknummer as node_name 
						  from Rubriek as up3
						left outer 
						  join Rubriek as up4
							on up4.rubrieknummer = up3.parent
						left outer 
						  join Rubriek as up5
							on up5.rubrieknummer = up4.parent
						inner join 
							 Voorwerp_in_rubriek as V1
							 on up3.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where up3.rubrieknummer = @rubrieknummer 
							or up3.rubrieknummer = @rubrieknummer 
							or up4.rubrieknummer = @rubrieknummer)
						END TRY 
						BEGIN CATCH 
						EXECUTE usp_GetErrorInfo;   
						END CATCH  
							-----------------
							-- delete up4
							-----------------
						BEGIN TRY
							DELETE FROM Rubriek WHERE rubrieknummer in (select 
							up4.rubrieknummer as node_name 
						  from Rubriek as up4
					
						left outer 
						  join Rubriek as up5
							on up5.rubrieknummer = up4.parent
						inner join 
							 Voorwerp_in_rubriek as V1
							 on up4.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where up4.rubrieknummer = @rubrieknummer 
							or up4.rubrieknummer = @rubrieknummer)
						END TRY  
						BEGIN CATCH  
						EXECUTE usp_GetErrorInfo;  
						END CATCH 
							-----------------
							-- delete up5
							-----------------
						BEGIN TRY
							DELETE FROM Rubriek WHERE rubrieknummer in (select 
							up5.rubrieknummer as node_name 
						  from Rubriek as up5
					
						inner join 
							 Voorwerp_in_rubriek as V1
							 on up5.rubrieknummer = v1.RubriekOpLaagsteNiveau
							where up5.rubrieknummer = @rubrieknummer)
							DELETE FROM Rubriek where rubrieknummer = @rubrieknummer
						END TRY
						BEGIN CATCH 
							EXECUTE usp_GetErrorInfo;  
						END CATCH 
			END TRY  
			BEGIN CATCH
				EXECUTE usp_GetErrorInfo;    
			END CATCH
			END
	END
END

exec Verwijderen_rubriek 220, 2570