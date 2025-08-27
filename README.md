# Pokemon Management Database

A comprehensive web-based database management system for monitoring the Pokemon universe.

## Live Demo

See the project [here](https://web.engr.oregonstate.edu/~xua/cs340/index.html)

## Features

### Core Entities
- **Pokemon**: Track species, rarity (Mythical/Legendary), baby status
- **People**: Manage trainers and their affiliations  
- **Types**: 18 elemental types with strengths/weaknesses
- **Battles**: Record battle outcomes and participants
- **Affiliations**: Track organizations, teams, and ranks

### Relationship Management
- **Pokemon-Types**: Many-to-many Pokemon type assignments
- **Pokemon-People**: Track Pokemon ownership
- **People-Battles**: Record battle participation

### CRUD Operations
- Full Create, Read, Update, Delete functionality for main entities
- NOTE: Pokemon-Types, being a junction table with only foreign keys and no other attributes, does not support Update
- Interactive web interface with dynamic forms
- Relationship management between entities

## Tech Stack

- **Frontend**: HTML/CSS, JavaScript
- **Backend**: PHP (with PDO for database connections)
- **Database**: MySQL with foreign key constraints
- **Server**: Apache (via XAMPP)

## Database Schema

The database includes 8 main tables:
- **Pokemon** (pokeID, pokeName, isBaby, pokeRarity)
- **People** (peopleID, peopleName, affiliationID)
- **Types** (typeID, typeName, weakAgainst, strongAgainst)
- **Battles** (battleID, battleDate, battleLocation)
- **Affiliations** (affiliationID, affiliationType, affiliationRank)
- **PokemonTypes** (Junction table)
- **PokemonPeople** (Junction table) 
- **PeopleBattles** (Junction table)

## Setup Instructions

### Prerequisites
- **XAMPP** (Apache + PHP + MySQL) or separate installations
- **MySQL Server** running on localhost

### Database Setup
1. Create MySQL database: `CREATE DATABASE pokemon_db;`
2. Import schema: `mysql -u root -p pokemon_db < DDL.sql`

### Web Application Setup
1. Copy project files to your web server directory (e.g., `C:\xampp\htdocs\pokemon-database\`)
2. Copy `config.example.php` to `config.php`
3. Update `config.php` with your MySQL credentials:
   ```php
   $password = 'your_mysql_password_here';
   ```
4. Start Apache server
5. Visit `http://localhost/pokemon-database/pokemon.php`

### Security Note
- `config.php` contains sensitive database credentials and is excluded from version control
- Always use `config.example.php` as a template for new installations

