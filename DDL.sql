-- Data Definition Queries for the Pokemon Management System --
-- Team members: Elena Parapounsky & Amy Xu  --

-- Citation for setting foreign key checks/autocommits:
-- Date: 10/28/2024
-- Based on recommendation to disable commits and foreign checks from CS340 - Project Step 2 Draft: Normalized Schema + DDL with Sample Data
-- Source URL: https://canvas.oregonstate.edu/courses/1976520/assignments/9783693?module_item_id=24719057

SET FOREIGN_KEY_CHECKS=0;
SET AUTOCOMMIT = 0;

-- =====================
-- DEFINE TABLES
-- =====================

-- Citation for the following function(s):
-- Date: 10/28/2024
-- Based on CREATE/INSERT table function from CS340 - Activity 1: Creating a Customer Object Table
-- Source URL: https://canvas.oregonstate.edu/courses/1976520/pages/activity-1-creating-a-customer-object-table?module_item_id=24719034

CREATE OR REPLACE TABLE Pokemon (
    pokeID int AUTO_INCREMENT UNIQUE NOT NULL,
    pokeName varchar(255) NOT NULL,
    isBaby boolean NOT NULL,
    pokeRarity varchar(255) NOT NULL,
	PRIMARY KEY (pokeID)
);

CREATE OR REPLACE TABLE Affiliations (
    affiliationID int AUTO_INCREMENT UNIQUE NOT NULL,
    affiliationType varchar(255) NOT NULL,
    affiliationRank varchar(255) NOT NULL,
    typeDescription varchar(255),
	PRIMARY KEY (affiliationID)
);

CREATE OR REPLACE TABLE People (
    peopleID int AUTO_INCREMENT UNIQUE NOT NULL,
    peopleName varchar(255) NOT NULL,
    affiliationID int,
	PRIMARY KEY (peopleID), 
	FOREIGN KEY (affiliationID) REFERENCES Affiliations(affiliationID) ON DELETE SET NULL
);

CREATE OR REPLACE TABLE Types (
    typeID int AUTO_INCREMENT UNIQUE NOT NULL,
    typeName varchar(255) NOT NULL,
    weakAgainst varchar(255) NOT NULL, 
	strongAgainst varchar(255) NOT NULL, 
	PRIMARY KEY (typeID)
);

CREATE OR REPLACE TABLE Battles (
    battleID int AUTO_INCREMENT UNIQUE NOT NULL,
    battleDate date NOT NULL,
    battleSetting varchar(255) NOT NULL,
	PRIMARY KEY (battleID)
);

CREATE OR REPLACE TABLE Pokemon_People (
	pokeID int NOT NULL, 
	peopleID int NOT NULL, 
	pokeNickname varchar(255), 
	caughtDate date NOT NULL, 
	caughtAt varchar(255) NOT NULL,
	PRIMARY KEY (pokeID, peopleID),
	FOREIGN KEY (pokeID) REFERENCES Pokemon(pokeID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (peopleID) REFERENCES People(peopleID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE OR REPLACE TABLE Pokemon_Types (
	pokeID int NOT NULL, 
	typeID int NOT NULL, 
	PRIMARY KEY (pokeID, typeID),
	FOREIGN KEY (pokeID) REFERENCES Pokemon(pokeID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (typeID) REFERENCES Types(typeID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE OR REPLACE TABLE People_Battles (
	peopleBattleID INT AUTO_INCREMENT UNIQUE NOT NULL,
	peopleID int, 
	battleID int NOT NULL, 
	battleResult varchar(255) NOT NULL, 
	UNIQUE (peopleID, battleID), -- make sure the same person isn't in the same battle more than once
	PRIMARY KEY (peopleBattleID),
	FOREIGN KEY (peopleID) REFERENCES People(peopleID) ON DELETE SET NULL ON UPDATE CASCADE, -- battles can remain even if a person is deleted
	FOREIGN KEY (battleID) REFERENCES Battles(battleID) ON DELETE CASCADE ON UPDATE CASCADE -- if a battle is deleted, the associated people should also be deleted from this table
);

-- =====================
-- INSERT SAMPLE DATA 
-- =====================

-- Citation for the following function(s):
-- Date: 10/24/2024
-- Pokemon data based on Pokemon website Pokedex
-- Source URL: https://www.pokemon.com/us/pokedex
-- Trainer title/ranks based on Pokemon Gold/Silver and Team Rocket Ranks
--	Source referenced is Full Trainer Info List - Guide and Walkthrough (GBC) by Eevee-Trainer / _Cecilia_ v1.13
--  Source URL: https://gamefaqs.gamespot.com/gbc/446340-pokemon-silver-version/faqs/75211/full-trainer-info-list
--  Source referenced is Ranks on Team Rocket Fandom
--  Source URL: https://teamrocket.fandom.com/wiki/Ranks

INSERT INTO Pokemon (pokeName, isBaby, pokeRarity)
VALUES 
	("Pikachu", 0, "Common"),
	("Charizard", 0, "Rare"),
	("Budew", 1, "Common"),
	("Mewtwo", 0, "Legendary"),
	("Arceus", 0, "Mythical"); 

INSERT INTO Affiliations (affiliationType, affiliationRank, typeDescription)
VALUES 
	("Team Rocket", "Agent", "Villanious organization"), 
	("Trainer", "Gym Leader", "Elite trainers"), 
	("Trainer", "Apprentice", "Standard trainers");

INSERT INTO People (peopleName, affiliationID)
VALUES 
	("Jessie", (SELECT affiliationID
				FROM Affiliations
				WHERE affiliationType = "Team Rocket"
				AND affiliationRank = "Agent")), 
	("Misty", (SELECT affiliationID
				FROM Affiliations
				WHERE affiliationType = "Trainer"
				AND affiliationRank = "Gym Leader")), 
	("Ash", (SELECT affiliationID
				FROM Affiliations
				WHERE affiliationType = "Trainer"
				AND affiliationRank = "Apprentice")); 

INSERT INTO Types (typeName, weakAgainst, strongAgainst)
VALUES 
	("Fire", "Water", "Grass"), 
	("Water", "Electric", "Fire"), 
	("Grass", "Fire", "Water"),
	("Ghost", "Dark", "Psychic"), 
	("Electric", "Ground", "Water"), 
	("Psychic", "Bug", "Poison"), 
	("Normal", "Rock", "Ghost");

INSERT INTO Battles (battleDate, battleSetting)
VALUES 
	('2024-10-10', "Forest"), 
	('2024-10-11', "Gym"), 
	('2024-10-12', "Cave");

INSERT INTO Pokemon_Types (pokeID, typeID)
VALUES 
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Pikachu"), (SELECT typeID FROM Types WHERE typeName = "Electric")), 
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Charizard"), (SELECT typeID FROM Types WHERE typeName = "Fire")), 
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Budew"), (SELECT typeID FROM Types WHERE typeName = "Grass")),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Mewtwo"), (SELECT typeID FROM Types WHERE typeName = "Psychic")),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Arceus"), (SELECT typeID FROM Types WHERE typeName = "Normal"));

INSERT INTO Pokemon_People (pokeID, peopleID, pokeNickname, caughtDate, caughtAt)
VALUES 
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Pikachu"), (SELECT peopleID FROM People WHERE peopleName = "Ash"), NULL, '2024-09-07', "Pallet Town"),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Charizard"), (SELECT peopleID FROM People WHERE peopleName = "Misty"), NULL, '2024-07-01', "Cerulean City"),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Budew"), (SELECT peopleID FROM People WHERE peopleName = "Jessie"), "Buddy", '2024-09-21', "Pinwheel Forest");

INSERT INTO People_Battles (peopleID, battleID, battleResult)
VALUES 
	(1, 1, "Won"), 
	(2, 1, "Lost"), 
	(1, 2, "Lost"), 
	(3, 2, "Won");
			
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
