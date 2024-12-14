/* Citations:
Date: 11/16/2024
Structure of app.js is adapated from Node.js Starter Guide Steps 0-3.
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app

Date: 11/16/2024
Read functions in app.js adapted from Step 4.
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app

Date: 11/16/2024
Create/add functions in app.js adapted from Step 5.
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app

Date: 11/16/2024
Delete functions in app.js adapted from Step 7.
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app

Date: 11/16/2024
Update functions in app.js adapted from Step 8.
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app

Date: 11/25/2024
Adapted dynamic dropdowns from Node.js Starter Guide Step 6 (Affiliations dropdown in People table, Owner and Type dropdowns in Pokemon Types table, Person Name and Battle Date in Player Battles Table). 
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app

Date: 11/20/2024
Adapted default to NULL strategy for optional values (affiliationID in People table).
Source URL: https://phillcode.io/the-ultimate-guide-to-default-values-in-javascript-and-operators-explained

Date: 11/21/2024
Adapted default to NULL strategy for optional values (typeDescription in Affiliations table).
Source URL: https://phillcode.io/the-ultimate-guide-to-default-values-in-javascript-and-operators-explained

Date: 11/20/2024
Method for formatting date output is adapted from MDN Web Docs (Battles table). 
Source URL: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/toISOString

Citations:
Date: 11/25/2024
Method for formatting date output is adapted from MDN Web Docs (Player Battles table). 
Source URL: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/toISOString
*/

/*-----------
    SETUP
-----------*/
var express = require('express');   // We are using the express library for the web server
var app     = express();            // We need to instantiate an express object to interact with the server in our code
app.use(express.json())
app.use(express.urlencoded({extended: true}))
app.use(express.static('public'))
PORT        = 19966;                 // Set a port number at the top so it's easy to change in the future
// Database
var db = require('./database/db-connector')

const { engine } = require('express-handlebars');
var exphbs = require('express-handlebars');     // Import express-handlebars
app.engine('.hbs', engine({extname: ".hbs"}));  // Create an instance of the handlebars engine to process templates
app.set('view engine', '.hbs');                 // Tell express to use the handlebars engine whenever it encounters a *.hbs file.
app.set('views', './views');

// handlebar helper
var hlp = require('handlebars-helpers')();

/*-----------
    ROUTES
-----------*/
//---------- INDEX PAGE ----------//
app.get('/', function(req, res)
    {
        res.render('index');                    // Note the call to render() and not send(). Using render() ensures the templating engine
    });   


//---------- POKEMON PAGE ----------//
app.get('/pokemon', function(req, res)
{   
    let query1;
    if (req.query.pokemonSearch === undefined){
        query1 = "SELECT *  FROM Pokemon;"
    } else {
        let pokeSearch = req.query.pokemonSearch + '%'
        query1 = `SELECT * FROM Pokemon WHERE pokeName LIKE "${req.query.pokemonSearch}%" OR pokeRarity LIKE "${req.query.pokemonSearch}%"`;
    }             // Define our query

    db.pool.query(query1, function(error, rows, fields){    // Execute the query

        res.render('pokemon', {data: rows});                  // Render the index.hbs file, and also send the renderer
    })                                                      // an object where 'data' is equal to the 'rows' we
});   

app.post('/addPokemon', function(req, res){
    // Capture the incoming data and parse it back to a JS object
    let data = req.body;
 
    let pokeName = data.pokeName;
    let isBaby = parseInt(data.isBaby);
    let pokeRarity = data.pokeRarity;

    // Create the query and run it on the database
    query1 = `INSERT INTO Pokemon (pokeName, isBaby, pokeRarity) VALUES (?, ?, ?)`;
    db.pool.query(query1,[pokeName, isBaby, pokeRarity] ,function(error, rows, fields){

        // Check to see if there was an error
        if (error) {

            // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
            console.log(error)
            res.sendStatus(400);
        }

        // If there was no error, we redirect back to our root route, which automatically runs the SELECT * FROM bsg_people and
        // presents it on the screen
        else
        {
            res.redirect('/pokemon');
        }
    })
});

app.delete('/delete-Pokemon/', function(req,res,next){
    let data = req.body;
    let pokeID = parseInt(data.id);
    let deleteQuery = `DELETE FROM Pokemon WHERE pokeID = ?`;
  
  
          // Run the 1st query
          db.pool.query(deleteQuery, [pokeID], function(error, rows, fields){
              if (error) {
  
              // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
              console.log(error);
              res.sendStatus(400);
              } else {
                res.sendStatus(204);
            }
  
  })});


app.post('/updatePokemon', function(req, res){
    // Capture the incoming data and parse it back to a JS object
    let data = req.body;

    let pokeID = parseInt(data.pokeID); 
    let pokeName = data.pokeName;
    let isBaby = parseInt(data.isBaby);
    let pokeRarity = data.pokeRarity;

    // Create the query and run it on the database
    query1 = `UPDATE Pokemon SET pokeName = ?, isBaby = ?, pokeRarity = ? WHERE pokeID = ?`;
    db.pool.query(query1, [pokeName, isBaby, pokeRarity, pokeID], function(error, rows, fields){

        // Check to see if there was an error
        if (error) {

            // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
            console.log(error)
            res.sendStatus(400);
        }

        // If there was no error, we redirect back to our root route
        else
        {
            res.redirect('/pokemon');
        }
    })
});

//---------- PEOPLE PAGE ----------//
app.get('/people', function(req, res) {
    let query1; 

    if (req.query.peopleSearch === undefined) {
        query1 = "SELECT * FROM People;" // SQL to show all People
    } else {
        query1 = `SELECT * FROM People WHERE peopleName LIKE "${req.query.peopleSearch}%"`; // SQL to search by peopleName
    }

    // concatenate affiliationType and affiliationRank for display
    let query2 = "SELECT affiliationID, CONCAT(Affiliations.affiliationType,' ',Affiliations.affiliationRank) AS affiliationType FROM Affiliations"; 

    db.pool.query(query1, function(error, rows, fields) { // run first query
        let people = rows; // save the people

        db.pool.query(query2, (error, rows, fields) => { // run second query
            let affiliations = rows; // save the affiliations 

            // dynamically express IDs & construct an object for reference in the table
            let affiliationMap = {};
            affiliations.forEach(affiliation => {
                let affiliationID = parseInt(affiliation.affiliationID, 10); // convert affiliationID to int
                affiliationMap[affiliationID] = affiliation.affiliationType; // map affiliationID to affiliationType
            });

            // add affiliationType to all People and keep affiliationID 
            people = people.map(person => {
                let affiliationType = affiliationMap[person.affiliationID] || null; // null if no affiliation 
                return Object.assign(person, {affiliationType: affiliationType});
            });

            // render people.hbs template with People and Affiliations
            res.render('people', {data: people, Affiliations: affiliations});
        });
    });
});

app.post('/addPeople', function(req, res) {
    let data = req.body;
 
    let peopleName = data.peopleName;
    let affiliationID = data.affiliationID || null; // default is NULL if left empty

    query1 = `INSERT INTO People (peopleName, affiliationID) VALUES (?, ?)`; // SQL to add a new Person

    db.pool.query(query1,[peopleName, affiliationID], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error)
            res.sendStatus(400);
        } else {
            res.redirect('/people'); // go back to People page (read)
        };
    });
});

app.post('/updatePeople', function(req, res) {
    let data = req.body;

    let peopleID = data.peopleID;
    let peopleName = data.peopleName;
    let affiliationID = data.affiliationID || null; // default is NULL if left empty;

    let query = `UPDATE People SET peopleName = ?, affiliationID = ? WHERE peopleID = ?`; // SQL to update a Person

    db.pool.query(query, [peopleName, affiliationID, peopleID], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error);
            res.sendStatus(400);
        } else {
            res.redirect('/people'); // go back to People page (read)
        }
    });
});

app.delete('/delete-People/', function(req,res,next){
    let data = req.body;
    let peopleID = parseInt(data.id);

    let deletePersonQuery = `DELETE FROM People WHERE peopleID = ?`; // SQL to delete a Person
  
    db.pool.query(deletePersonQuery, [peopleID], function(error, rows, fields){ // run the query
        if (error) {
            console.log(error);
            res.sendStatus(400);
        } else {
            res.sendStatus(204);
        }
  })});

//---------- POKEMONPEOPLE PAGE ----------//
app.get('/pokemonpeople', function(req, res)
{   
    let query1;
    if (req.query.pokemonpeopleSearch === undefined){
        query1 = "SELECT * FROM Pokemon_People;"
    } else {
        query1 = `SELECT distinct pp.* FROM Pokemon_People pp, Pokemon po, People pe
        WHERE (po.pokeName LIKE "${req.query.pokemonpeopleSearch}%" AND po.pokeID = pp.pokeID) 
        OR (pe.peopleName LIKE "${req.query.pokemonpeopleSearch}%" AND pe.peopleID = pp.peopleID) 
        OR pp.pokeNickname LIKE "${req.query.pokemonpeopleSearch}%" 
        OR pp.caughtAt LIKE "${req.query.pokemonpeopleSearch}%" 
        OR pp.caughtDate LIKE "${req.query.pokemonpeopleSearch}%"`;
    }             // Define our query

    let query2 = "SELECT * FROM Pokemon";
    let query3 = "SELECT * FROM People";

    db.pool.query(query1, function(error, rows, fields){    // Execute the query
        let pokemonpeople = rows;

        db.pool.query(query2, (error, rows, fields) => {
            let pokemon = rows;
            let pokemonmap = {}
            pokemon.map(poke => {
                let pokeID = parseInt(poke.pokeID, 10);
                pokemonmap[pokeID] = [poke["pokeName"], poke["pokeID"]]; // sending array to retain ID if needed
            })

            pokemonpeople = pokemonpeople.map(pokepeop => {
                return Object.assign(pokepeop, {pokeID: pokemonmap[pokepeop.pokeID]})
            })

            db.pool.query(query3, (error, rows, fields)=>{
                let people = rows;

                let peoplemap ={}
                people.map(person => {
                    let peopleID = parseInt(person.peopleID, 10);
                    peoplemap[peopleID] = [person["peopleName"], person["peopleID"]]; // sending array to retain ID if needed
                })

                pokemonpeople = pokemonpeople.map(pokepeop => {
                    return Object.assign(pokepeop, {peopleID: peoplemap[pokepeop.peopleID]})
                })

                res.render('pokemonpeople', {data: pokemonpeople, pokemon: pokemon, people: people});                  // Render the index.hbs file, and also send the renderer

            })
        })
    })                                                      // an object where 'data' is equal to the 'rows' we
});   

app.post('/addPokemonPeople', function(req, res){
    // Capture the incoming data and parse it back to a JS object
    let data = req.body;

    let pokeID = parseInt(data.pokemon);
    let peopleID = parseInt(data.people);
    let pokeNickname = data.pokeNickname;
    if (!pokeNickname){ pokeNickname = null};  // check for NULLs
    let caughtDate = data.caughtDate;
    let caughtAt = data.caughtAt;

    // Create the query and run it on the database
    query1 = `INSERT INTO Pokemon_People (pokeID, peopleID, pokeNickname, caughtDate, caughtAt) VALUES (?, ?, ?, ?, ?)`;
    db.pool.query(query1,[pokeID, peopleID, pokeNickname, caughtDate, caughtAt] ,function(error, rows, fields){

        // Check to see if there was an error
        if (error) {

            // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
            console.log(error)
            res.sendStatus(400);
        }

        // If there was no error, we redirect back to our root route, which automatically runs the SELECT * FROM bsg_people and
        // presents it on the screen
        else
        {
            res.redirect('/pokemonpeople');
        }
    })
});

app.delete('/delete-PokemonPeople/', function(req,res,next){
    let data = req.body;

    let pokePeopleID = parseInt(data.id);
    let deleteQuery = `DELETE FROM Pokemon_People WHERE pokePeopleID = ?`;
  
  
          // Run the 1st query
          db.pool.query(deleteQuery, [pokePeopleID], function(error, rows, fields){
              if (error) {
  
              // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
              console.log(error);
              res.sendStatus(400);
              } else {
                res.sendStatus(204);
            }
  
  })});

  app.post('/updatePokemonPeople', function(req, res){
    // Capture the incoming data and parse it back to a JS object
    let data = req.body;

    let pokePeopleID = parseInt(data.pokePeopleID);
    let pokeID = parseInt(data.pokemon);
    let peopleID = parseInt(data.people);
    let pokeNickname = data.pokeNickname;
    if (!pokeNickname){ pokeNickname = null};  // check for NULLs
    let caughtDate = data.caughtDate;
    let caughtAt = data.caughtAt;

    // Create the query and run it on the database
    query1 = `UPDATE Pokemon_People SET pokeID = ?, peopleID = ?, pokeNickname = ?, caughtDate = ?, caughtAt = ? WHERE pokePeopleID = ?`;
    db.pool.query(query1, [pokeID, peopleID, pokeNickname, caughtDate, caughtAt, pokePeopleID], function(error, rows, fields){

        // Check to see if there was an error
        if (error) {

            // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
            console.log(error)
            res.sendStatus(400);
        }

        // If there was no error, we redirect back to our root route
        else
        {
            res.redirect('/pokemonpeople');
        }
    })
});

//---------- AFFILIATIONS PAGE ----------//
app.get('/affiliations', function(req, res) {   
    let query1;

    if (req.query.affiliationsSearch === undefined) {
        query1 = "SELECT * FROM Affiliations;" // SQL to show all Affiliations
    } else {
        query1 = `SELECT * FROM Affiliations WHERE affiliationType LIKE "${req.query.affiliationsSearch}%"`; // SQL to search by affiliationType
    }             

    db.pool.query(query1, function(error, rows, fields){ // run the query
        res.render('affiliations', {data: rows}); // render affiliations.hbs template                  
    });                                                      
});   

app.post('/addAffiliations', function(req, res) {
    let data = req.body;
 
    let affiliationType = data.affiliationType;
    let affiliationRank = data.affiliationRank; 
    let typeDescription = data.typeDescription || null; // default is NULL if left empty

    query1 = `INSERT INTO Affiliations (affiliationType, affiliationRank, typeDescription) VALUES (?, ?, ?)`; // SQL to add a new Affiliation

    db.pool.query(query1,[affiliationType, affiliationRank, typeDescription], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error)
            res.sendStatus(400);
        } else {
            res.redirect('/affiliations'); // go back to Affiliations page (read)
        };
    });
});

app.post('/updateAffiliations', function(req, res) {
    let data = req.body;

    let affiliationID = parseInt(data.affiliationID); 
    let affiliationType = data.affiliationType;
    let affiliationRank = data.affiliationRank;
    let typeDescription = data.typeDescription || null; // default is NULL if left empty

    query1 = `UPDATE Affiliations SET affiliationType = ?, affiliationRank = ?, typeDescription = ? WHERE affiliationID = ?`; // SQL to update an Affiliation

    db.pool.query(query1, [affiliationType, affiliationRank, typeDescription, affiliationID], function(error, rows, fields){ // run the query
        if (error) {
            console.log(error)
            res.sendStatus(400);
        } else {
            res.redirect('/affiliations'); // go back to Affiliations page (read)
        };
    });
});

app.delete('/delete-Affiliations/', function(req, res, next) {
    let data = req.body;
    let affiliationID = parseInt(data.id);
    let deleteQuery = `DELETE FROM Affiliations WHERE affiliationID = ?`; // SQL to delete an Affiliation
  
    db.pool.query(deleteQuery, [affiliationID], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error);
            res.sendStatus(400);
        } else {
            res.sendStatus(204);
        }
  })});

//---------- BATTLES PAGE ----------//
app.get('/battles', function(req, res) {   
    let query1;

    if (req.query.battlesSearch === undefined) {
        query1 = "SELECT * FROM Battles;" // SQL to show all Battles
    } else {
        query1 = `SELECT * FROM Battles WHERE battleSetting LIKE "${req.query.battlesSearch}%"`; // SQL to search by battleSetting
    }                                                              

    db.pool.query(query1, function(error, rows, fields){ // run the query    

        // turns battleBate into JS Date object, converts it to a string, splits the string in two (on 'T'), uses only the part with the date ([0])
        rows.forEach(row => {
            row.battleDate = new Date(row.battleDate).toISOString().split('T')[0]; 
        });

        res.render('battles', {data: rows}); // render battles.hbs template                 
    });                                          
});  

app.post('/addBattles', function(req, res) {
    let data = req.body;
 
    let battleDate = data.battleDate;
    let battleSetting = data.battleSetting; 

    query1 = `INSERT INTO Battles (battleDate, battleSetting) VALUES (?, ?)`; // SQL to add a new Battle

    db.pool.query(query1, [battleDate, battleSetting], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error)
            res.sendStatus(400);
        } else {
            res.redirect('/battles'); // go back to Battles page (read)
        };
    });
});

app.post('/updateBattles', function(req, res) {
    let data = req.body;

    let battleID = parseInt(data.battleID); 
    let battleDate = data.battleDate;
    let battleSetting = data.battleSetting;

    query1 = `UPDATE Battles SET battleDate = ?, battleSetting = ? WHERE battleID = ?`; // SQL to update a Battle

    db.pool.query(query1, [battleDate, battleSetting, battleID], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error)
            res.sendStatus(400);
        } else {
            res.redirect('/battles'); // go back to Battles page (read)
        };
    });
});

app.delete('/delete-Battles/', function(req, res, next) {
    let data = req.body;
    
    let battleID = parseInt(data.id);
    let deleteQuery = `DELETE FROM Battles WHERE battleID = ?`; // SQL to delete a Battle
  
    db.pool.query(deleteQuery, [battleID], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error);
            res.sendStatus(400);
        } else {
            res.sendStatus(204);
        }
})});

//---------- PLAYER BATTLES PAGE ----------//
app.get('/peoplebattles', function(req, res) {   
    let query1;

    // get all required data for dynamic dropdowns
    let query2 = "SELECT peopleID, peopleName FROM People"; // SQL to get a Player's ID and Name
    let query3 = "SELECT battleID, battleDate FROM Battles"; // SQL to get a Battle's ID and Date

    if (req.query.peopleBattlesSearch === undefined) {
        // SQL to join People_Battles, People, and Battles and display all their data
        query1 = `SELECT pb.peopleBattleID, pb.peopleID, pb.battleID, pb.battleResult, pe.peopleName, ba.battleDate 
            FROM People_Battles pb
            JOIN People pe ON pb.peopleID = pe.peopleID
            JOIN Battles ba ON pb.battleID = ba.battleID;
        `;
    } else {
        // SQL to search by peopleName, battleID, or battleResult
        query1 = `SELECT pb.peopleBattleID, pb.peopleID, pb.battleID, pb.battleResult, pe.peopleName, ba.battleDate 
            FROM People_Battles pb
            JOIN People pe ON pb.peopleID = pe.peopleID
            JOIN Battles ba ON pb.battleID = ba.battleID
            WHERE pe.peopleName LIKE "${req.query.peopleBattlesSearch}%"
            OR ba.battleID LIKE "${req.query.peopleBattlesSearch}%"
            OR pb.battleResult LIKE "${req.query.peopleBattlesSearch}%";
        `;
    }

    db.pool.query(query1, function(error, rows, fields) { // run the first query
        // turns battleBate into JS Date object, converts it to a string, splits the string in two (on 'T'), uses only the part with the date ([0])
        rows.forEach(row => {
            row.battleDate = new Date(row.battleDate).toISOString().split('T')[0];
        });

        let peopleBattlesData = rows; // save People_Battles data

        // get data for People dropdown
        db.pool.query(query2, function(error, rows, fields) { // run the second query

            let peopleData = rows; // save People data

            // get data for Battles dropdown
            db.pool.query(query3, function(error, rows, fields) { // run the third query

                // turns battleBate into JS Date object, converts it to a string, splits the string in two (on 'T'), uses only the part with the date ([0])
                rows.forEach(row => {
                    row.battleDate = new Date(row.battleDate).toISOString().split('T')[0];
                });

                let battlesData = rows; // save Battles data

                // render peoplebattles.hbs template with People_Battles, People, and Battles
                res.render('peoplebattles', {
                    data: peopleBattlesData, 
                    People: peopleData, // for People dropdown
                    Battles: battlesData // for Battles dropdown
                });
            });
        });
    });
});

app.post('/addPeopleBattles', function(req, res) {
    let data = req.body;
 
    let peopleID = data.peopleID;
    let battleID = data.battleID; 
    let battleResult = data.battleResult;

    query1 = `INSERT INTO People_Battles (peopleID, battleID, battleResult) VALUES (?, ?, ?)`; // SQL to add a new Player Battle

    db.pool.query(query1, [peopleID, battleID, battleResult], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error)
            res.sendStatus(400);
        } else {
            res.redirect('/peoplebattles'); // go back to Player Battles page (read)
        };
    });
});

app.post('/updatePeopleBattles', function(req, res) {
    let data = req.body;

    let peopleBattleID = parseInt(data.peopleBattleID);
    let peopleID = parseInt(data.peopleID); 
    let battleID = parseInt(data.battleID);
    let battleResult = data.battleResult;

    query1 = `UPDATE People_Battles SET peopleID = ?, battleID = ?, battleResult = ? WHERE peopleBattleID = ?`; // SQL to update a Player Battle

    db.pool.query(query1, [peopleID, battleID, battleResult, peopleBattleID], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error)
            res.sendStatus(400);
        } else {
            res.redirect('/peoplebattles'); // go back to Player Battles page (read)
        };
    });
});

app.delete('/delete-People-Battles', function(req, res, next) {
    let data = req.body;
    let peopleBattleID = parseInt(data.id);

    let deleteQuery = `DELETE FROM People_Battles WHERE peopleBattleID = ?`; // SQL to delete a Player Battle
  
    db.pool.query(deleteQuery, [peopleBattleID], function(error, rows, fields) { // run the query
        if (error) {
            console.log(error);
            res.sendStatus(400);
        } else {
            res.sendStatus(204);
        };
})});

//---------- TYPES PAGE ----------//
app.get('/types', function(req, res)
{   
    let query1;
    if (req.query.typesSearch === undefined){
        query1 = "SELECT * FROM Types;"
    } else {
        query1 = `SELECT * FROM Types WHERE typeName LIKE "${req.query.typesSearch}%" OR weakAgainst LIKE "${req.query.typesSearch}%" OR strongAgainst LIKE "${req.query.typesSearch}%"`;
    }             // Define our query

    db.pool.query(query1, function(error, rows, fields){    // Execute the query
        let types = rows;

        res.render('types', {data: types});                  // Render the index.hbs file, and also send the renderer
    })                                                      // an object where 'data' is equal to the 'rows' we
});   

app.post('/addType', function(req, res){
    // Capture the incoming data and parse it back to a JS object
    let data = req.body;

    let typeName = data.typeName;
    let weakAgainst= data.weakAgainst;
    let strongAgainst = data.strongAgainst;

    // Create the query and run it on the database
    query1 = `INSERT INTO Types (typeName, weakAgainst, strongAgainst) VALUES (?, ?, ?)`;
    db.pool.query(query1,[typeName, weakAgainst, strongAgainst] ,function(error, rows, fields){

        // Check to see if there was an error
        if (error) {

            // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
            console.log(error)
            res.sendStatus(400);
        }

        // If there was no error, we redirect back to our root route, which automatically runs the SELECT * FROM bsg_people and
        // presents it on the screen
        else
        {
            res.redirect('/types');
        }
    })
});

app.delete('/delete-Types/', function(req,res,next){
    let data = req.body;

    let typeID = parseInt(data.id);
    let deleteQuery = `DELETE FROM Types WHERE typeID = ?`;
  
  
          // Run the 1st query
          db.pool.query(deleteQuery, [typeID], function(error, rows, fields){
              if (error) {
  
              // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
              console.log(error);
              res.sendStatus(400);
              } else {
                res.sendStatus(204);
            }
  
  })});

  app.post('/updateType', function(req, res){
    // Capture the incoming data and parse it back to a JS object
    let data = req.body;

    let typeID = parseInt(data.typeID);
    let typeName = data.typeName;
    let weakAgainst= data.weakAgainst;
    let strongAgainst = data.strongAgainst;

    // Create the query and run it on the database
    query1 = `UPDATE Types SET typeName = ?, weakAgainst = ?, strongAgainst = ? WHERE typeID = ?`;
    db.pool.query(query1, [typeName, weakAgainst, strongAgainst, typeID], function(error, rows, fields){

        // Check to see if there was an error
        if (error) {

            // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
            console.log(error)
            res.sendStatus(400);
        }

        // If there was no error, we redirect back to our root route
        else
        {
            res.redirect('/types');
        }
    })
});

//---------- POKEMONTYPES PAGE ----------//
app.get('/pokemontypes', function(req, res)
{   
    let query1;
    if (req.query.pokemontypesSearch === undefined){
        query1 = "SELECT * FROM Pokemon_Types;"
    } else {
        query1 = `SELECT DISTINCT pt.* FROM Pokemon_Types pt, Pokemon p, Types t
                 WHERE (t.typeName LIKE "${req.query.pokemontypesSearch}%" AND pt.typeID = t.typeID)
                 OR (p.pokeName LIKE "${req.query.pokemontypesSearch}%" AND pt.pokeID = p.pokeID)`;
    }             // Define our query


    let query2 = "SELECT * FROM Pokemon";
    let query3 = "SELECT * FROM Types";

    db.pool.query(query1, function(error, rows, fields){    // Execute the query
        let pokemontypes = rows;

        db.pool.query(query2, (error, rows, fields) => {
            let pokemon = rows;
            let pokemonmap = {}
            pokemon.map(poke => {
                let pokeID = parseInt(poke.pokeID, 10);
                pokemonmap[pokeID] = [poke["pokeName"], poke["pokeID"]]; // sending array to retain ID if needed
            })

            pokemontypes = pokemontypes.map(poketype => {
                return Object.assign(poketype, {pokeID: pokemonmap[poketype.pokeID]})
            })

            db.pool.query(query3, (error, rows, fields)=>{
                let types = rows;
                let typemap ={}
                types.map(type => {
                    let typeID = parseInt(type.typeID, 10);
                    typemap[typeID] = [type["typeName"], type["typeID"]]; // sending array to retain ID if needed
                })

                pokemontypes = pokemontypes.map(poketype => {
                    return Object.assign(poketype, {typeID: typemap[poketype.typeID]})
                })

                res.render('pokemontypes', {data: pokemontypes, pokemon: pokemon, types:types});                  // Render the index.hbs file, and also send the renderer

            })
        })
        })                                                      // an object where 'data' is equal to the 'rows' we
});   

app.post('/addPokemonTypes', function(req, res){
    // Capture the incoming data and parse it back to a JS object
    let data = req.body;

    let pokeID = data.pokemon;
    let typeID= data.type;

    // Create the query and run it on the database
    query1 = `INSERT INTO Pokemon_Types (pokeID, typeID) VALUES (?, ?)`;
    db.pool.query(query1,[pokeID, typeID] ,function(error, rows, fields){

        // Check to see if there was an error
        if (error) {

            // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
            console.log(error)
            res.sendStatus(400);
        }

        // If there was no error, we redirect back to our root route, which automatically runs the SELECT * FROM bsg_people and
        // presents it on the screen
        else
        {
            res.redirect('/pokemontypes');
        }
    })
});

app.delete('/delete-pokemontypes/', function(req,res,next){
    let data = req.body;
    let pokeID = parseInt(data.pokeID);
    let typeID = parseInt(data.typeID);
    let deleteQuery = `DELETE FROM Pokemon_Types WHERE typeID = ? AND pokeID = ?`;
  
  
          // Run the 1st query
          db.pool.query(deleteQuery, [typeID, pokeID], function(error, rows, fields){
              if (error) {
  
              // Log the error to the terminal so we know what went wrong, and send the visitor an HTTP response 400 indicating it was a bad request.
              console.log(error);
              res.sendStatus(400);
              } else {
                res.sendStatus(204);
            }
  
  })});  

/*-----------
    LISTENER
-----------*/
app.listen(PORT, function(){            // This is the basic syntax for what is called the 'listener' which receives incoming requests on the specified PORT.
    console.log('Express started on http://localhost:' + PORT + '; press Ctrl-C to terminate.')
});
