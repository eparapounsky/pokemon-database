# Pokemon Management Database

A comprehensive web-based database management system for monitoring the Pokemon universe.

## Live Demo

See the project [here](https://pokemon-database.infinityfreeapp.com/)

## Features

### Core Entities

- **Pokemon**: Track species, rarity, baby status
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
- **Database**: MySQL hosted on InfinityFree
- **Management**: phpMyAdmin for database administration

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
