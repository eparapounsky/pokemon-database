-- ---------------------------------------------------------------
-- Data Manipulation Queries for the Pokemon Management System --
-- ---------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;
SET AUTOCOMMIT = 0;

-- ---------------------------------------------------------------
--               CRUD operations for Entity Tables           --
-- ---------------------------------------------------------------
--                    Pokemon Table                          --
-- ---------------------------------------------------------------
-- Create
INSERT INTO Pokemon (pokeName, isBaby, pokeRarity)
VALUES (?, ?, ?);

-- Read
SELECT 
    pokeID AS "Pokemon ID",
    pokeName AS "Pokemon Name", 
    isBaby AS "Is Baby?",
    pokeRarity AS "Pokemon Rarity"
FROM Pokemon;

-- Update
UPDATE Pokemon 
SET 
    pokeName = ?,
    isBaby = ?,
    pokeRarity = ?
WHERE pokeID = ?;

-- Delete
DELETE FROM Pokemon 
WHERE pokeID = ?;

-- ---------------------------------------------------------------
--                    People Table                          --
-- ---------------------------------------------------------------
-- Create
-- Get affiliationID and "Type Rank" affiliations for Affiliation dropdown
SELECT 
    affiliationID,
    CONCAT(affiliationType, ' ', affiliationRank) AS affiliation
FROM Affiliations;

INSERT INTO People (peopleName, affiliationID)
VALUES (?, ?);

-- Read
SELECT 
    peopleID AS "Person ID",
    peopleName AS "Person Name",
    CONCAT(
        Affiliations.affiliationType, 
        ' ', 
        Affiliations.affiliationRank
    ) AS "Person Affiliation"
FROM People
INNER JOIN Affiliations ON People.affiliationID = Affiliations.affiliationID;

-- Update
UPDATE People 
SET 
    peopleName = ?,
    affiliationID = ?
WHERE peopleID = ?;

-- Delete
DELETE FROM People 
WHERE peopleID = ?;

-- ---------------------------------------------------------------
--                    Affiliations Table                          --
-- ---------------------------------------------------------------
-- Create
INSERT INTO Affiliations (
    affiliationType,
    affiliationRank, 
    typeDescription
)
VALUES (?, ?, ?);

-- Read
SELECT 
    affiliationID AS "Affiliation ID",
    affiliationType AS "Affiliation Type",
    affiliationRank AS "Affiliation Rank",
    typeDescription AS "Type Description"
FROM Affiliations;

-- Update
UPDATE Affiliations 
SET 
    affiliationType = ?,
    affiliationRank = ?,
    typeDescription = ?
WHERE affiliationID = ?;

-- Delete
DELETE FROM Affiliations 
WHERE affiliationID = ?;

-- ---------------------------------------------------------------
--                      Types Table                          --
-- ---------------------------------------------------------------
-- Create
INSERT INTO Types (typeName, weakAgainst, strongAgainst)
VALUES (?, ?, ?);

-- Read
SELECT 
    typeID AS "Type ID",
    typeName AS "Type Name",
    weakAgainst AS "Weak Against",
    strongAgainst AS "Strong Against"
FROM Types;

-- Update
UPDATE Types 
SET 
    typeName = ?,
    weakAgainst = ?,
    strongAgainst = ?
WHERE typeID = ?;

-- Delete
DELETE FROM Types 
WHERE typeID = ?;

-- ---------------------------------------------------------------
--                      Battles Table                        --
-- ---------------------------------------------------------------
-- Create
INSERT INTO Battles (battleDate, battleSetting)
VALUES (?, ?);

-- Read
SELECT 
    battleID AS "Battle ID",
    battleDate AS "Battle Date",
    battleSetting AS "Battle Setting"
FROM Battles;

-- Update
UPDATE Battles 
SET 
    battleDate = ?,
    battleSetting = ?
WHERE battleID = ?;

-- Delete
DELETE FROM Battles 
WHERE battleID = ?;

-- ---------------------------------------------------------------
--         CRUD operations for Intersecting Tables           --
-- ---------------------------------------------------------------
--                 Pokemon_People Table                      --
-- ---------------------------------------------------------------
-- Create
-- Get pokeID and pokeName for Pokemon dropdown
SELECT pokeID, pokeName FROM Pokemon;

-- Get peopleID and peopleName for Owner dropdown  
SELECT peopleID, peopleName FROM People;

INSERT INTO Pokemon_People (
    pokeID,
    peopleID,
    pokeNickname,
    caughtDate,
    caughtAt
)
VALUES (?, ?, ?, ?, ?);

-- Read
SELECT 
    Pokemon.pokeName AS Pokemon,
    People.peopleName AS Owner,
    pokeNickname AS "Pokemon Nickname",
    caughtDate AS "Caught Date", 
    caughtAt AS "Caught Location"
FROM Pokemon_People
INNER JOIN Pokemon ON Pokemon_People.pokeID = Pokemon.pokeID
INNER JOIN People ON Pokemon_People.peopleID = People.peopleID;

-- Update
UPDATE Pokemon_People 
SET 
    pokeNickname = ?,
    caughtDate = ?,
    caughtAt = ?
WHERE pokeID = ? AND peopleID = ?;

-- Delete
DELETE FROM Pokemon_People 
WHERE pokeID = ? AND peopleID = ?;

-- ---------------------------------------------------------------
--                  Pokemon_Types Table                      --
-- ---------------------------------------------------------------
-- Create
-- Get pokeID and pokeName for Pokemon dropdown
SELECT pokeID, pokeName FROM Pokemon;

-- Get typeID and typeName for Type dropdown
SELECT typeID, typeName FROM Types;

INSERT INTO Pokemon_Types (pokeID, typeID)
VALUES (?, ?);

-- Read
SELECT 
    Pokemon.pokeName AS Pokemon,
    Types.typeName AS Type
FROM Pokemon_Types
INNER JOIN Pokemon ON Pokemon_Types.pokeID = Pokemon.pokeID  
INNER JOIN Types ON Pokemon_Types.typeID = Types.typeID;

-- Update
-- Unable to update as this is an intersecting table with only Primary keys

-- Delete
DELETE FROM Pokemon_Types 
WHERE pokeID = ? AND typeID = ?;

-- ---------------------------------------------------------------
--                 People_Battles Table                      --
-- ---------------------------------------------------------------
-- Create
INSERT INTO People_Battles (peopleID, battleID, battleResult)
VALUES (?, ?, ?);

-- Read
SELECT 
    peopleBattleID AS "Battle Entry ID",
    peopleID AS "Person ID",
    battleID AS "Battle ID", 
    battleResult AS "Battle Result"
FROM People_Battles;

-- Update
UPDATE People_Battles 
SET battleResult = ?
WHERE peopleID = ? AND battleID = ?;

-- Delete
DELETE FROM People_Battles 
WHERE peopleID = ? AND battleID = ?;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
