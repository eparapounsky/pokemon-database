/*
Citations:
Date: 11/16/2024
Copied structure of db-connector.js from Step 1.
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app
*/

// Get an instance of mysql we can use in the app
var mysql = require('mysql')

// Create a 'connection pool' using the provided credentials
var pool = mysql.createPool({
    connectionLimit : 10,
    host            : 'classmysql.engr.oregonstate.edu',
    user            : 'cs340_####',
    password        : '####',
    database        : 'cs340_####'
})

// Export it for use in our applicaiton
module.exports.pool = pool;