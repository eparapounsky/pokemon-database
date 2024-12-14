/*
Citations:
Date: 11/16/2024
Structure of deletePokemon.js copied from Step 8. 
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app
*/

function deletePokemon(pokeID) {
    // confirmation message on delete
    if(confirm("Are you sure you want to delete this entry?")){
        // Put our data we want to send in a javascript object
        let data = {
            id: pokeID
        };

        // Setup our AJAX request
        var xhttp = new XMLHttpRequest();
        xhttp.open("DELETE", "/delete-Pokemon", true);
        xhttp.setRequestHeader("Content-type", "application/json");

        // Tell our AJAX request how to resolve
        xhttp.onreadystatechange = () => {
            if (xhttp.readyState == 4 && xhttp.status == 204) {

                // Add the new data to the table
                deleteRow(pokeID);

            }
            else if (xhttp.readyState == 4 && xhttp.status != 204) {
                console.log("There was an error with the input.")
            }
        }
        // Send the request and wait for the response
        xhttp.send(JSON.stringify(data));
    } else {
        res.redirect('/pokemon');
    }
}

function deleteRow(pokeID){

    let table = document.getElementById("pokemon-table");
    for (let i = 0, row; row = table.rows[i]; i++) {
       //iterate through rows
       //rows would be accessed using the "row" variable assigned in the for loop
       if (table.rows[i].getAttribute("data-value") == pokeID) {
            table.deleteRow(i);
            break;
       }
    }
}