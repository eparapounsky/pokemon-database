---------------------------------------------------------------
-- Data Definition Queries for the Pokemon Management System --
---------------------------------------------------------------
SET
    FOREIGN_KEY_CHECKS = 0;

SET
    AUTOCOMMIT = 0;

---------------------------------------------------------------
--               CRUD operations for Entity Tables           --
---------------------------------------------------------------
--                    Pokemon Table                          --
---------------------------------------------------------------
-- Create
INSERT INTO
    Pokemon (pokeName, isBaby, pokeRarity)
VALUES
    (:pokeNameInput, :isBabySelect, :pokeRaritySelect);

-- Read
SELECT
    pokeID AS "Pokemon ID",
    pokeName AS "Pokemon Name",
    isBaby AS "Is Baby?",
    pokeRarity AS "Pokemon Rarity"
FROM
    Pokemon;

-- Update
UPDATE
    Pokemon
SET
    pokeName = :pokeNameInput,
    isBaby = :isBabySelect,
    pokeRarity = :pokeRaritySelect
WHERE
    pokeID = :pokeID_selected_from_browse_Pokemon_page;

-- Delete
DELETE FROM
    Pokemon
WHERE
    pokeID = :pokeID_selected_from_browse_Pokemon_page;

--                    People Table                          --
---------------------------------------------------------------
-- Create
--      get affialitionID and "Type Rank" affiliations for Affiliation dropdown
SELECT
    affiliationID,
    CONCAT(affilationType, ' ', affiliationRank) AS affiliation
FROM
    Affiliations;

INSERT INTO
    People (peopleName, affiliation)
VALUES
    (
        :peopleNameInput,
        :affiliation_from_dropdown_Input
    );

-- Read
SELECT
    peopleID AS "Person ID",
    peopleName AS "Person Name",
    CONCAT(
        Affiliations.affiliationType,
        ' ',
        Affiliations.affiliationRank
    ) AS "Person Affiliation"
FROM
    People
    INNER JOIN Affiliations ON People.affiliationID = Affiliations.affiliationID;

-- Update
UPDATE
    People
SET
    peopleName = :peopleNameInput,
    Affiliation = :affiliationID_from_dropdown_Input
WHERE
    peopleID = :peopleID_selected_from_browse_People_page;

-- Delete
DELETE FROM
    People
WHERE
    peopleID = :peopleID_selected_from_browse_People_page;

--                    Affiliations Table                          --
---------------------------------------------------------------
-- Create
INSERT INTO
    Affiliations (
        affiliationType,
        affiliationRank,
        typeDescription
    )
VALUES
    (
        :affiliationTypeInput,
        :affiliationRankInput,
        :typeDescriptionInput
    );

-- Read
SELECT
    affiliationID AS "Affiliation ID",
    affiliationType AS "Affiliation Type",
    affiliationRank AS "Affiliation Rank",
    typeDescription AS "Type Description"
FROM
    Affiliations;

-- Update
UPDATE
    Affiliations
SET
    affiliationType = :affiliationTypeInput,
    affiliationRank = :affiliationRankInput,
    typeDescription = :typeDescriptionInput
WHERE
    affliationID = :affiliationID_selected_from_browse_Affiliations_page;

-- Delete
DELETE FROM
    Affiliations
WHERE
    affiliationID = :affiliationID_selected_from_browse_Affiliations_page;

--                      Types Table                          --
---------------------------------------------------------------
-- Create
INSERT INTO
    Types (typeName, weakAgainst, strongAgainst)
VALUES
    (
        :typeNameInput,
        :weakAgainstInput,
        :strongAgainstInput
    );

-- Read
SELECT
    typeID AS "Type ID",
    typeName AS "Type Name",
    weakAgainst AS "Weak Against",
    strongAgainst AS "Strong Against"
FROM
    Types;

-- Update
UPDATE
    Types
SET
    typeName = :typeNameInput,
    weakAgainst = :weakAgainstInput,
    strongAgainst = :strongAgainstInput
WHERE
    typeID = :typeID_selected_from_browse_Types_page;

-- Delete
DELETE FROM
    Types
WHERE
    typeID = :typeID_selected_from_browse_Types_page;

--                      Battles Table                        --
---------------------------------------------------------------
-- Create
INSERT INTO
    Battles (battleDate, battleSetting)
VALUES
    (:battleDateInput, :battleSettingInput);

-- Read
SELECT
    battleID AS "Battle ID",
    battleDate AS "Battle Date",
    battleSetting AS "Battle Setting"
FROM
    Battles;

-- Update
UPDATE
    Battles
SET
    battleDate = :battleDateInput,
    battleSetting = :battleSettingInput
WHERE
    battleID = :battleID_selected_from_browse_Battles_page;

-- Delete
DELETE FROM
    Battles
WHERE
    battleID = :battleID_selected_from_browse_Battles_page;

---------------------------------------------------------------
--         CRUD operations for Intersecting Tables           --
---------------------------------------------------------------
--                 Pokemon_People Table                      --
---------------------------------------------------------------
-- Create
--      get pokeID and pokeName for Pokemon dropdown
SELECT
    pokeID,
    pokeName
FROM
    Pokemon;

--      get peopleID and peopleName for Owner dropdown
SELECT
    peopleID,
    peopleName
FROM
    People;

INSERT INTO
    Pokemon_People (
        Pokemon,
        Owner,
        pokeNickname,
        caughtDate,
        caughtAt
    )
VALUES
    (
        :pokeID_from_dropdown_Input,
        :peopleID_from_dropdown_Input,
        :pokeNicknameInput,
        :caughtDateInput,
        :caughtAtInput
    );

-- Read
SELECT
    Pokemon.pokeName AS Pokemon,
    People.peopleName AS Owner,
    pokeNickname AS "Pokemon Nickname",
    caughtDate AS "Caught Date",
    caughtAt AS "Caught Location"
FROM
    Pokemon_People
    INNER JOIN Pokemon ON Pokemon_People.pokeID = Pokemon.pokeID
    INNER JOIN People ON Pokemon_People.peopleID = People.peopleID;

-- Update
UPDATE
    Pokemon_People
SET
    pokeNickname = :pokeNicknameInput,
    caughtDate = :caughtDateInput,
    caughtAt = :caughtAtInput
WHERE
    pokeID = :pokeID_selected_from_browse_Pokemon_People_page
    AND peopleID = :peopleID_selected_from_browse_Pokemon_People_page;

-- Delete
DELETE FROM
    Pokemon_People
WHERE
    pokeID = :pokeID_selected_from_browse_Pokemon_People_page
    AND peopleID = :peopleID_selected_from_browse_Pokemon_People_page;

--                  Pokemon_Types Table                      --
---------------------------------------------------------------
-- Create
--      get pokeID and pokeName for Pokemon dropdown
SELECT
    pokeID,
    pokeName
FROM
    Pokemon;

--      get typeID and typeName for Type dropdown
SELECT
    typeID,
    typeName
FROM
    Types;

INSERT INTO
    Pokemon_Types (Pokemon, Type)
VALUES
    (
        :pokeID_from_dropdown_Input,
        :typeID_from_dropdown_Input
    );

-- Read
SELECT
    Pokemon.pokeName AS Pokemon,
    Types.typeName AS Type
FROM
    Pokemon_Types
    INNER JOIN Pokemon ON Pokemon_Types.pokeID = Pokemon.pokeID
    INNER JOIN Types ON Pokemon_Types.typeID = Types.typeID;

-- Update
-- Unable to update as in intersecting table with no other attributes other than Primary keys
-- Delete
DELETE FROM
    Pokemon_Types
WHERE
    pokeID = :pokeID_selected_from_browse_Pokemon_Types_page
    AND typeID = :typeID_selected_from_browse_Pokemon_Types_page;

--                 People_Battles Table                      --
---------------------------------------------------------------
-- Create
INSERT INTO
    People_Battles (PersonID, battleID, battleResult)
VALUES
    (
        :personIDInput,
        :battleIDInput,
        :battleResultSelect
    );

-- Read
SELECT
    peopleID AS "Person ID",
    battleID AS "Battle ID",
    battleResult AS "Battle Result"
FROM
    People_Battles;

-- Update
UPDATE
    People_Battles
SET
    battleResult = :battleResultSelect;

WHERE
    peopleID = :peopleID_selected_from_browse_People_Battles_page
    AND battleID = :battleID_selected_from_browse_People_Battles_page;

-- Delete
DELETE FROM
    People_Battles
WHERE
    peopleID = :peopleID_selected_from_browse_People_Battles_page
    AND battleID = :battleID_selected_from_browse_People_Battles_page;

SET
    FOREIGN_KEY_CHECKS = 1;

COMMIT;