<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Battles</title>
<!-- <meta name="viewport" content="width=device-width,initial-scale=1"> -->
<link href="main.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/x-icon" href="favicon.ico">

<script language="JavaScript">
    function showform(dowhat) {
        /*
        * four DIVS: browse, insert, update, delete
        * this function sets one visible the others not
        */
        if (dowhat == 'insert') {
            document.getElementById('browse').style.display = 'none';
            document.getElementById('insert').style.display = 'block';
            document.getElementById('update').style.display = 'none';
            document.getElementById('delete').style.display = 'none';
        }
        else if (dowhat == 'update') {
            document.getElementById('browse').style.display = 'none';
            document.getElementById('insert').style.display = 'none';
            document.getElementById('update').style.display = 'block';
            document.getElementById('delete').style.display = 'none';
        }
        else if (dowhat == 'delete') {
            document.getElementById('browse').style.display = 'none';
            document.getElementById('insert').style.display = 'none';
            document.getElementById('update').style.display = 'none';
            document.getElementById('delete').style.display = 'block';
        }
        else if (dowhat == 'all') {
            document.getElementById('browse').style.display = 'block';
            document.getElementById('insert').style.display = 'block';
            document.getElementById('update').style.display = 'block';
            document.getElementById('delete').style.display = 'block';
        }
        else { //by default display browse
            document.getElementById('browse').style.display = 'block';
            document.getElementById('insert').style.display = 'none';
            document.getElementById('update').style.display = 'none';
            document.getElementById('delete').style.display = 'none';
        }
    }
    function newBattle() { showform('insert'); }
    function updateBattle(pid) { showform('update'); }
    function deleteBattle(pid) { showform('delete'); }
    function browseBattle() { showform('browse'); }
    function showAll() { showform('all'); }
</script>

<body>
    <header>
        <h1>Pokemon Database</h1>
    </header>

    <nav>
        <a href="index.html">Home</a>
        <a href="pokemon.php">Pokemon</a>
        <a href="people.php">People</a>
        <a href="affiliations.php">Affiliations</a>
        <a href="types.php">Types</a>
        <a href="battles.php">Battles</a>
        <br><br>
        <a href="pokemontypes.php">Pokemon Types</a>
        <a href="pokemonpeople.php">Owned Pokemon</a>
        <a href="peoplebattles.php">Player Battles</a>
    </nav>

    <main>
        <section>
            <h2>Battles</h2>

            <article id="battles">
                <p id="center">
                    Battles that have taken place
                </p>

                <div id="browse">
                    <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
                            onClick="browseBattle()">Hide forms</a><br>
                        <a href="#" onClick="newBattle()">New</a>
                    </p>

                    <table border="1" cellpadding="5" id="centerMargin">
                        <tr>
                            <th>Battle ID</th>
                            <th>Battle Date</th>
                            <th>Battle Setting</th>
                            <th>Edit Entry</th>
                            <th>Delete Entry</th>
                        </tr>
                        <tr>
                            <td align="right">1</td>
                            <td>2024-10-10</td>
                            <td>Forest</td>
                            <td><a href="#" onClick="updateBattle('this.battleID')">Edit</a></td>
                            <td><a href="#" onclick="deleteBattle('this.battleID')">Delete</a></td>
                        </tr>
                        <tr>
                            <td align="right">2</td>
                            <td>2024-10-11</td>
                            <td>Gym</td>
                            <td><a href="#" onClick="updateBattle('this.battleID')">Edit</a></td>
                            <td><a href="#" onclick="deleteBattle('this.battleID')">Delete</a></td>
                        </tr>
                        <tr>
                            <td align="right">3</td>
                            <td>2024-10-12</td>
                            <td>Cave</td>
                            <td><a href="#" onClick="updateBattle('this.battleID')">Edit</a></td>
                            <td><a href="#" onclick="deleteBattle('this.battleID')">Delete</a></td>
                        </tr>
                    </table>
                </div> <!-- browse -->
                <p>&nbsp;</p>
                <div id="insert" style="display: none">
                    <form method="POST" id="addBattle">
                        <legend><strong>Add Battle</strong></legend>
                        <fieldset class="fields">
                            <label>* Battle Date </label> <input type="date" name="battleDate"
                                style="margin-bottom: 10px" required>
                            <br>
                            <label>* Battle Setting </label> <input type="text" name="battleSetting"
                                style="margin-bottom: 10px" required>
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="addBattle">Add Battle</button>
                            <button type="button" onClick="browseBattle()">Cancel</button>
                        </p>
                    </form>
                </div><!-- insert -->
                <p>&nbsp;</p>
                <div id="update" style="display: none">
                    <form method="POST" id="updateBattle">
                        <legend><strong>Update Battle</strong></legend>
                        <fieldset class="fields">
                            <input type="hidden" name="battleID" id="updateBattleID" value="1">
                            <label> ID#: </label> 1
                            <br>
                            <label> Battle Date </label> <input type="date" name="battleDate"
                                style="margin-bottom: 10px">
                            <br>
                            <label> Battle Setting </label> <input type="text" name="battleSetting"
                                style="margin-bottom: 10px">
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="UpdateSaveBattle">Save Battle Updates</button>
                            <button type="button" onClick="browseBattle()">Cancel</button>
                        </p>
                    </form>
                </div><!-- update -->
                <p>&nbsp;</p>
                <div id="delete" style="display: none">
                    <form method="POST" id="deleteBattle">
                        <legend><strong>Delete Battle</strong></legend>
                        <fieldset class="fields">
                            <p>Are you sure you want to delete this Battle from the database?</p>
                            <input type="hidden" name="battleID" id="deleteBattleID" value="1">
                            <label><strong>ID#: </strong></label> 1
                            <br>
                            <label> <strong>Batle Date: </strong> </label> 2024-10-10
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="deleteBattle">Delete Battle</button>
                            <button type="button" onClick="browseBattle()">Cancel</button>
                        </p>
                    </form>
                </div><!-- delete -->
        </section>
    </main>
    <footer>© 2025 - Elena Parapounsky & Amy Xu
        <br>
        <a href="https://github.com/eparapounsky/pokemon-database">🔍 Source on GitHub</a>
    </footer>
</body>

</html>