---------------------------------------------------------------
-- Data Definition Queries for the Pokemon Management System --
-- Team members: Elena Parapounsky & Amy Xu  --
---------------------------------------------------------------

/* Citation for setting foreign key checks/autocommits:
 Date: 11/05/2024
 Based on recommendation to disable commits and foreign checks from CS340 - Project Step 2 Draft: Normalized Schema + DDL with Sample Data
 Source URL: https://canvas.oregonstate.edu/courses/1976520/assignments/9783693?module_item_id=24719057
*/

SET FOREIGN_KEY_CHECKS=0;
SET AUTOCOMMIT = 0;

/* Citation for the following function(s):
 Date: 11/05/2024
 Based on CRUD table function from CS340 - Exploration: Database Application Design
 Source URL: https://canvas.oregonstate.edu/courses/1976520/pages/exploration-database-application-design?module_item_id=24719062
*/


---------------------------------------------------------------
--               CRUD operations for Entity Tables           --
---------------------------------------------------------------

--                    Pokemon Table                          --
---------------------------------------------------------------
-- Create
INSERT INTO Pokemon (pokeName, isBaby, pokeRarity)
VALUES (:pokeNameInput, :isBabySelect, :pokeRaritySelect); 

-- Read
SELECT *
FROM Pokemon;

-- Update
UPDATE Pokemon
SET pokeName = :pokeNameInput, isBaby = :isBabySelect, pokeRarity = :pokeRaritySelect
WHERE pokeID =  :pokeID_selected_from_browse_Pokemon_page;

-- Delete
DELETE 
FROM Pokemon 
WHERE pokeID = :pokeID_selected_from_browse_Pokemon_page;


--                    People Table                          --
---------------------------------------------------------------
-- Create
--      get affialitionID and "Type Rank" affiliations for Affiliation dropdown
SELECT affiliationID, CONCAT(affilationType,' ',affiliationRank) AS affiliation
FROM Affiliations;

INSERT INTO People (peopleName, affiliation)
VALUES (:peopleNameInput, :affiliation_from_dropdown_Input); 

-- Read
SELECT peopleID, CONCAT(Affiliations.affiliationType,' ',Affiliations.affiliationRank)
FROM People
INNER JOIN Affiliations ON People.affiliationID = Affiliations.affiliationID;

-- Update
UPDATE People
SET peopleName = :peopleNameInput, Affiliation = :affiliationID_from_dropdown_Input
WHERE peopleID =  :peopleID_selected_from_browse_People_page;

-- Delete
DELETE 
FROM People
WHERE peopleID = :peopleID_selected_from_browse_People_page;


--                    Affiliations Table                          --
---------------------------------------------------------------
-- Create
INSERT INTO Affiliations (affiliationType, affiliationRank, typeDescription)
VALUES (:affiliationTypeInput, :affiliationRankInput, :typeDescriptionInput); 

-- Read
SELECT *
FROM Affiliations;

-- Update
UPDATE Affiliations
SET affiliationType = :affiliationTypeInput, affiliationRank = :affiliationRankInput, typeDescription = :typeDescriptionInput
WHERE affliationID =  :affiliationID_selected_from_browse_Affiliations_page;

-- Delete
DELETE 
FROM Affiliations
WHERE affiliationID = :affiliationID_selected_from_browse_Affiliations_page;


--                      Types Table                          --
---------------------------------------------------------------
-- Create
INSERT INTO Types (typeName, weakAgainst, strongAgainst)
VALUES (:typeNameInput, :weakAgainstInput, :strongAgainstInput); 

-- Read
SELECT *
FROM Types;

-- Update
UPDATE Types
SET typeName = :typeNameInput, weakAgainst = :weakAgainstInput, strongAgainst = :strongAgainstInput
WHERE typeID =  :typeID_selected_from_browse_Types_page;

-- Delete
DELETE 
FROM Types
WHERE typeID = :typeID_selected_from_browse_Types_page;

--                      Battles Table                        --
---------------------------------------------------------------
-- Create
INSERT INTO Battles (battleDate, battleSetting)
VALUES (:battleDateInput, :battleSettingInput); 

-- Read
SELECT *
FROM Battles;

-- Update
UPDATE Battles
SET battleDate = :battleDateInput, battleSetting = :battleSettingInput
WHERE battleID =  :battleID_selected_from_browse_Battles_page;

-- Delete
DELETE 
FROM Battles
WHERE battleID = :battleID_selected_from_browse_Battles_page;

---------------------------------------------------------------
--         CRUD operations for Intersecting Tables           --
---------------------------------------------------------------

--                 Pokemon_People Table                      --
---------------------------------------------------------------
-- Create
--      get pokeID and pokeName for Pokemon dropdown
SELECT pokeID, pokeName
FROM Pokemon;

--      get peopleID and peopleName for Owner dropdown
SELECT peopleID, peopleName
FROM People;

INSERT INTO Pokemon_People (Pokemon, Owner, pokeNickname, caughtDate, caughtAt)
VALUES (:pokeID_from_dropdown_Input, :peopleID_from_dropdown_Input, :pokeNicknameInput, :caughtDateInput, :caughtAtInput); 

-- Read
SELECT Pokemon.pokeName, People.peopleName, pokeNickname, caughtDate, caughtAt
FROM Pokemon_People
INNER JOIN Pokemon ON Pokemon_People.pokeID = Pokemon.pokeID
INNER JOIN People ON Pokemon_People.peopleID = People.peopleID;

-- Update
UPDATE Pokemon_People
SET pokeNickname = :pokeNicknameInput, caughtDate = :caughtDateInput, caughtAt = :caughtAtInput
WHERE pokeID =  :pokeID_selected_from_browse_Pokemon_People_page
AND peopleID =  :peopleID_selected_from_browse_Pokemon_People_page;

-- Delete
DELETE 
FROM Pokemon_People
WHERE pokeID =  :pokeID_selected_from_browse_Pokemon_People_page
AND peopleID =  :peopleID_selected_from_browse_Pokemon_People_page;


--                  Pokemon_Types Table                      --
---------------------------------------------------------------
-- Create
--      get pokeID and pokeName for Pokemon dropdown
SELECT pokeID, pokeName
FROM Pokemon;

--      get typeID and typeName for Type dropdown
SELECT typeID, typeName
FROM Types;

INSERT INTO Pokemon_Types (Pokemon, Type)
VALUES (:pokeID_from_dropdown_Input, :typeID_from_dropdown_Input); 

-- Read
SELECT Pokemon.pokeName, Types.typeName
FROM Pokemon_Types
INNER JOIN Pokemon ON Pokemon_Types.pokeID = Pokemon.pokeID
INNER JOIN Types ON Pokemon_Types.typeID = Types.typeID;

-- Update
-- Unable to update as in intersecting table with no other attributes other than Primary keys


-- Delete
DELETE 
FROM Pokemon_Types
WHERE pokeID =  :pokeID_selected_from_browse_Pokemon_Types_page
AND typeID =  :typeID_selected_from_browse_Pokemon_Types_page;


--                 People_Battles Table                      --
---------------------------------------------------------------
-- Create
--      get peopleID and peopleName for Contender dropdown
SELECT peopleID, peopleName
FROM People;

INSERT INTO People_Battles (Contender, battleID, battleResult)
VALUES (:peopleID_from_dropdown_Input, :battleIDInput, :battleResultSelect); 

-- Read
SELECT People.peopleName, battleID, battleResult
FROM People_Battles
INNER JOIN Pokemon ON Pokemon_Types.pokeID = Pokemon.pokeID;

-- Update
UPDATE People_Battles
SET battleResult = :battleResultSelect;
WHERE peopleID =  :peopleID_selected_from_browse_People_Battles_page
AND battleID =  :battleID_selected_from_browse_People_Battles_page;

-- Delete
DELETE 
FROM People_Battles
WHERE peopleID =  :peopleID_selected_from_browse_People_Battles_page
AND battleID =  :battleID_selected_from_browse_People_Battles_page;


SET FOREIGN_KEY_CHECKS=1;
COMMIT;