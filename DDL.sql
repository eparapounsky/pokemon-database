-- ---------------------------------------------------------------
-- Data Definition Queries for the Pokemon Management System 
-- ---------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;
SET AUTOCOMMIT = 0;

-- =====================
-- DROP TABLES IF THEY EXIST
-- =====================
DROP TABLE IF EXISTS People_Battles;
DROP TABLE IF EXISTS Pokemon_People;
DROP TABLE IF EXISTS Pokemon_Types;
DROP TABLE IF EXISTS Pokemon;
DROP TABLE IF EXISTS People;
DROP TABLE IF EXISTS Affiliations;
DROP TABLE IF EXISTS Types;
DROP TABLE IF EXISTS Battles;

-- =====================
-- CREATE TABLES
-- =====================
CREATE TABLE Pokemon (
	pokeID int AUTO_INCREMENT UNIQUE NOT NULL,
	pokeName varchar(255) NOT NULL,
	isBaby boolean NOT NULL,
	pokeRarity varchar(255) NOT NULL,
	PRIMARY KEY (pokeID)
);

CREATE TABLE Affiliations (
	affiliationID int AUTO_INCREMENT UNIQUE NOT NULL,
	affiliationType varchar(255) NOT NULL,
	affiliationRank varchar(255) NOT NULL,
	typeDescription varchar(255),
	PRIMARY KEY (affiliationID)
);

CREATE TABLE People (
	peopleID int AUTO_INCREMENT UNIQUE NOT NULL,
	peopleName varchar(255) NOT NULL,
	affiliationID int,
	PRIMARY KEY (peopleID),
	FOREIGN KEY (affiliationID) REFERENCES Affiliations(affiliationID) ON DELETE SET NULL
);

CREATE TABLE Types (
	typeID int AUTO_INCREMENT UNIQUE NOT NULL,
	typeName varchar(255) NOT NULL,
	weakAgainst varchar(255) NOT NULL,
	strongAgainst varchar(255) NOT NULL,
	PRIMARY KEY (typeID)
);

CREATE TABLE Battles (
	battleID int AUTO_INCREMENT UNIQUE NOT NULL,
	battleDate date NOT NULL,
	battleSetting varchar(255) NOT NULL,
	PRIMARY KEY (battleID)
);

CREATE TABLE Pokemon_People (
	pokeID int NOT NULL,
	peopleID int NOT NULL,
	pokeNickname varchar(255),
	caughtDate date NOT NULL,
	caughtAt varchar(255) NOT NULL,
	PRIMARY KEY (pokeID, peopleID),
	FOREIGN KEY (pokeID) REFERENCES Pokemon(pokeID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (peopleID) REFERENCES People(peopleID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Pokemon_Types (
	pokeID int NOT NULL,
	typeID int NOT NULL,
	PRIMARY KEY (pokeID, typeID),
	FOREIGN KEY (pokeID) REFERENCES Pokemon(pokeID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (typeID) REFERENCES Types(typeID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE People_Battles (
	peopleBattleID INT AUTO_INCREMENT UNIQUE NOT NULL,
	peopleID int,
	battleID int NOT NULL,
	battleResult varchar(255) NOT NULL,
	UNIQUE (peopleID, battleID),
	PRIMARY KEY (peopleBattleID),
	FOREIGN KEY (peopleID) REFERENCES People(peopleID) ON DELETE SET NULL ON UPDATE CASCADE,
	FOREIGN KEY (battleID) REFERENCES Battles(battleID) ON DELETE CASCADE ON UPDATE CASCADE
);

-- =====================
-- INSERT SAMPLE DATA 
-- =====================
INSERT INTO Pokemon (pokeName, isBaby, pokeRarity) VALUES
	("Pikachu", 0, "Common"),
	("Charizard", 0, "Rare"),
	("Budew", 1, "Common"),
	("Mewtwo", 0, "Legendary"),
	("Arceus", 0, "Mythical");

INSERT INTO Affiliations (affiliationType, affiliationRank, typeDescription) VALUES
	("Team Rocket", "Agent", "Villanious organization"),
	("Trainer", "Gym Leader", "Elite trainers"),
	("Trainer", "Apprentice", "Standard trainers");

INSERT INTO People (peopleName, affiliationID) VALUES
	("Jessie", (SELECT affiliationID FROM Affiliations WHERE affiliationType = "Team Rocket" AND affiliationRank = "Agent")),
	("Misty", (SELECT affiliationID FROM Affiliations WHERE affiliationType = "Trainer" AND affiliationRank = "Gym Leader")),
	("Ash", (SELECT affiliationID FROM Affiliations WHERE affiliationType = "Trainer" AND affiliationRank = "Apprentice"));

INSERT INTO Types (typeName, weakAgainst, strongAgainst) VALUES
	("Fire", "Water", "Grass"),
	("Water", "Electric", "Fire"),
	("Grass", "Fire", "Water"),
	("Ghost", "Dark", "Psychic"),
	("Electric", "Ground", "Water"),
	("Psychic", "Bug", "Poison"),
	("Normal", "Rock", "Ghost");

INSERT INTO Battles (battleDate, battleSetting) VALUES
	('2024-10-10', "Forest"),
	('2024-10-11', "Gym"),
	('2024-10-12', "Cave");

INSERT INTO Pokemon_Types (pokeID, typeID) VALUES
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Pikachu"), (SELECT typeID FROM Types WHERE typeName = "Electric")),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Charizard"), (SELECT typeID FROM Types WHERE typeName = "Fire")),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Budew"), (SELECT typeID FROM Types WHERE typeName = "Grass")),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Mewtwo"), (SELECT typeID FROM Types WHERE typeName = "Psychic")),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Arceus"), (SELECT typeID FROM Types WHERE typeName = "Normal"));

INSERT INTO Pokemon_People (pokeID, peopleID, pokeNickname, caughtDate, caughtAt) VALUES
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Pikachu"), (SELECT peopleID FROM People WHERE peopleName = "Ash"), NULL, '2024-09-07', "Pallet Town"),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Charizard"), (SELECT peopleID FROM People WHERE peopleName = "Misty"), NULL, '2024-07-01', "Cerulean City"),
	((SELECT pokeID FROM Pokemon WHERE pokeName = "Budew"), (SELECT peopleID FROM People WHERE peopleName = "Jessie"), "Buddy", '2024-09-21', "Pinwheel Forest");

INSERT INTO People_Battles (peopleID, battleID, battleResult) VALUES
	(1, 1, "Won"),
	(2, 1, "Lost"),
	(1, 2, "Lost"),
	(3, 2, "Won");

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
