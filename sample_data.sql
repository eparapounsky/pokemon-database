-- Sample Data for Pokemon Management System
SET FOREIGN_KEY_CHECKS = 0;
SET AUTOCOMMIT = 0;

-- Clear existing data first (in case of partial import)
DELETE FROM People_Battles;
DELETE FROM Pokemon_People;
DELETE FROM Pokemon_Types;
DELETE FROM Battles;
DELETE FROM People;
DELETE FROM Pokemon;
DELETE FROM Types;
DELETE FROM Affiliations;

-- Insert sample Affiliations first (needed for People foreign keys)
INSERT INTO Affiliations (affiliationType, affiliationRank, typeDescription) VALUES
('Trainer', 'Beginner', 'New Pokemon trainers starting their journey'),
('Trainer', 'Gym Leader', 'Elite trainers who run Pokemon gyms'),
('Team Rocket', 'Grunt', 'Low-level members of the criminal organization'),
('Team Rocket', 'Executive', 'High-ranking members of Team Rocket'),
('Professor', 'Researcher', 'Pokemon researchers and academics'),
('Elite Four', 'Champion', 'The strongest trainers in the region');

-- Insert sample Types
INSERT INTO Types (typeName, weakAgainst, strongAgainst) VALUES
('Fire', 'water', 'grass'),
('Water', 'grass', 'fire'),
('Grass', 'fire', 'water'),
('Electric', 'ground', 'water'),
('Ground', 'water', 'electric'),
('Flying', 'electric', 'grass'),
('Normal', 'fighting', 'none'),
('Fighting', 'flying', 'normal'),
('Psychic', 'dark', 'fighting'),
('Dark', 'fighting', 'psychic');

-- Insert sample Pokemon
INSERT INTO Pokemon (pokeName, isBaby, pokeRarity) VALUES
('Pikachu', FALSE, 'Common'),
('Charizard', FALSE, 'Rare'),
('Blastoise', FALSE, 'Rare'),
('Venusaur', FALSE, 'Rare'),
('Mew', FALSE, 'Mythical'),
('Mewtwo', FALSE, 'Legendary'),
('Pichu', TRUE, 'Common'),
('Magikarp', FALSE, 'Common'),
('Gyarados', FALSE, 'Uncommon'),
('Dragonite', FALSE, 'Rare');

-- Insert sample People (using affiliationID from above)
INSERT INTO People (peopleName, affiliationID) VALUES
('Ash Ketchum', 1),
('Misty', 2),
('Brock', 2),
('Jessie', 3),
('James', 3),
('Professor Oak', 5),
('Gary Oak', 1),
('Team Rocket Boss', 4),
('Lance', 6),
('Cynthia', 6);

-- Insert sample Battles
INSERT INTO Battles (battleDate, battleSetting) VALUES
('2024-01-15', 'Pewter City Gym'),
('2024-01-20', 'Cerulean City Gym'),
('2024-02-01', 'Viridian Forest'),
('2024-02-14', 'Pokemon League'),
('2024-03-01', 'Team Rocket Hideout');

-- Insert sample Pokemon_Types (many-to-many relationships) - FIXED
INSERT INTO Pokemon_Types (pokeID, typeID) VALUES
(1, 4), -- Pikachu is Electric
(2, 1), -- Charizard is Fire
(2, 6), -- Charizard is also Flying (dual-type)
(3, 2), -- Blastoise is Water
(4, 3), -- Venusaur is Grass
(5, 9), -- Mew is Psychic
(6, 9), -- Mewtwo is Psychic
(7, 4), -- Pichu is Electric
(8, 2), -- Magikarp is Water
(9, 2), -- Gyarados is Water
(9, 6), -- Gyarados is also Flying (dual-type)
(10, 6); -- Dragonite is Flying

-- Insert sample Pokemon_People (ownership relationships)
INSERT INTO Pokemon_People (pokeID, peopleID, pokeNickname, caughtDate, caughtAt) VALUES
(1, 1, 'Buddy', '2024-01-01', 'Pallet Town'),
(7, 1, NULL, '2023-12-25', 'Viridian Forest'),
(8, 1, 'Splash', '2024-01-10', 'Route 1'),
(2, 7, 'Blaze', '2024-01-05', 'Charicific Valley'),
(3, 2, NULL, '2024-01-08', 'Cerulean Cave'),
(4, 3, 'Ivy', '2024-01-12', 'Viridian Forest'),
(6, 6, 'Experiment', '2023-01-01', 'Cinnabar Lab'),
(9, 9, 'Stormy', '2024-02-01', 'Lake of Rage');

-- Insert sample People_Battles (who participated in which battles)
INSERT INTO People_Battles (peopleID, battleID, battleResult) VALUES
(1, 1, 'Won'),    -- Ash won at Pewter Gym
(3, 1, 'Lost'),   -- Brock lost to Ash
(1, 2, 'Won'),    -- Ash won at Cerulean Gym
(2, 2, 'Lost'),   -- Misty lost to Ash
(1, 3, 'Won'),    -- Ash won in Viridian Forest
(4, 3, 'Lost'),   -- Jessie lost to Ash
(5, 3, 'Lost'),   -- James lost to Ash
(1, 4, 'Lost'),   -- Ash lost at Pokemon League
(9, 4, 'Won'),    -- Lance won at Pokemon League
(7, 5, 'Won'),    -- Gary won at Team Rocket Hideout
(8, 5, 'Lost');   -- Team Rocket Boss lost

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;