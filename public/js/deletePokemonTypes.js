/*
Citations:
Date: 11/26/2024
Structure of deletePokemonTypes.js copied from Step 8. 
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app
*/

function deletePokemonTypes(pokeTypeID) {
    // confirmation message on delete
    if(confirm("Are you sure you want to delete this entry?")){
        // Put our data we want to send in a javascript object
        let data = {
            pokeID: pokeTypeID[0], 
            typeID: pokeTypeID[1]
        };

        // Setup our AJAX request
        var xhttp = new XMLHttpRequest();
        xhttp.open("DELETE", "/delete-pokemontypes", true);
        xhttp.setRequestHeader("Content-type", "application/json");

        // Tell our AJAX request how to resolve
        xhttp.onreadystatechange = () => {
            if (xhttp.readyState == 4 && xhttp.status == 204) {

                // Add the new data to the table
                deleteRow(pokeTypeID);

            }
            else if (xhttp.readyState == 4 && xhttp.status != 204) {
                console.log("There was an error with the input.")
            }
        }
        // Send the request and wait for the response
        xhttp.send(JSON.stringify(data));
    } else {
        res.redirect('/pokemontypes');
    }
}

function deleteRow(pokeTypeID){
 
    let table = document.getElementById("pokemontypes-table");
    for (let i = 0, row; row = table.rows[i]; i++) {
       //iterate through rows
       //rows would be accessed using the "row" variable assigned in the for loop
       if (table.rows[i].getAttribute("data-value") == pokeTypeID[0]) {
            table.deleteRow(i);
            res.redirect('/pokemontypes');
            break;
       }

    }
}