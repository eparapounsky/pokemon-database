/*
Citations:
Date: 11/21/2024
Structure of deleteAffiliations.js copied from Step 8. 
Source URL: https://github.com/osu-cs340-ecampus/nodejs-starter-app
*/

function deleteAffiliation(affiliationID) {
    // confirmation message on delete
    if(confirm("Are you sure you want to delete this entry?")){
        // Put our data we want to send in a javascript object
        let data = {
            id: affiliationID
        };

        // Setup our AJAX request
        var xhttp = new XMLHttpRequest();
        xhttp.open("DELETE", "/delete-Affiliations", true);
        xhttp.setRequestHeader("Content-type", "application/json");

        // Tell our AJAX request how to resolve
        xhttp.onreadystatechange = () => {
            if (xhttp.readyState == 4 && xhttp.status == 204) {

                // Add the new data to the table
                deleteRow(affiliationID);

            }
            else if (xhttp.readyState == 4 && xhttp.status != 204) {
                console.log("There was an error with the input.")
            }
        }
        // Send the request and wait for the response
        xhttp.send(JSON.stringify(data));
    } else {
        res.redirect('/affiliations');
    }
}

function deleteRow(affiliationID){

    let table = document.getElementById("affiliations-table");
    for (let i = 0, row; row = table.rows[i]; i++) {

       if (table.rows[i].getAttribute("data-value") == affiliationID) {
            table.deleteRow(i);
            break;
       }
    }
}