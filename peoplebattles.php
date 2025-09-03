<?php
// filepath: c:\git\pokemon-database\peoplebattles.php
require_once 'config.php';

// Handle form submissions
$message = '';
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    // Determine battle results based on winner
                    $player1Result = ($_POST['battleWinner'] == 'player1') ? 'Won' : 'Lost';
                    $player2Result = ($_POST['battleWinner'] == 'player2') ? 'Won' : 'Lost';
                    
                    // Insert both player battle records
                    $stmt = $pdo->prepare("INSERT INTO People_Battles (peopleID, battleID, battleResult) VALUES (?, ?, ?)");
                    $stmt->execute([$_POST['player1ID'], $_POST['battleID'], $player1Result]);
                    $stmt->execute([$_POST['player2ID'], $_POST['battleID'], $player2Result]);
                    
                    $message = "<div style='color: green;'>Player Battle added successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error adding player battle: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'update':
                try {
                    $stmt = $pdo->prepare("UPDATE People_Battles SET battleResult = ? WHERE peopleID = ? AND battleID = ?");
                    $stmt->execute([$_POST['battleResult'], $_POST['peopleID'], $_POST['battleID']]);
                    $message = "<div style='color: green;'>Player Battle updated successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error updating player battle: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM People_Battles WHERE peopleID = ? AND battleID = ?");
                    $stmt->execute([$_POST['peopleID'], $_POST['battleID']]);
                    $message = "<div style='color: green;'>Player Battle deleted successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error deleting player battle: " . $e->getMessage() . "</div>";
                }
                break;
        }
    }
}

// Fetch all People_Battles with names for display
try {
    $stmt = $pdo->query("
        SELECT pb.peopleID, pb.battleID, pb.battleResult, p.peopleName, b.battleDate, b.battleSetting
        FROM People_Battles pb 
        JOIN People p ON pb.peopleID = p.peopleID 
        JOIN Battles b ON pb.battleID = b.battleID 
        ORDER BY pb.battleID, pb.peopleID
    ");
    $playerBattles = $stmt->fetchAll();
} catch (Exception $e) {
    $playerBattles = [];
    $message = "<div style='color: red;'>Error fetching player battles: " . $e->getMessage() . "</div>";
}

// Fetch all people for dropdowns
try {
    $stmt = $pdo->query("SELECT peopleID, peopleName FROM People ORDER BY peopleName");
    $people = $stmt->fetchAll();
} catch (Exception $e) {
    $people = [];
}

// Fetch all battles for dropdowns
try {
    $stmt = $pdo->query("SELECT battleID, battleDate, battleSetting FROM Battles ORDER BY battleID");
    $battles = $stmt->fetchAll();
} catch (Exception $e) {
    $battles = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Player Battles</title>
<!-- <meta name="viewport" content="width=device-width,initial-scale=1"> -->
<link href="main.css" rel="stylesheet" type="text/css" />

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
    function newPersBat() { showform('insert'); }
    function updatePersBat(peopleID, battleID, battleResult, peopleName, battleInfo) { 
        showform('update'); 
        document.getElementById('updatePeopleID').value = peopleID;
        document.getElementById('updateBattleID').value = battleID;
        document.getElementById('updatePeopleIDDisplay').textContent = peopleID;
        document.getElementById('updateBattleIDDisplay').textContent = battleID;
        document.getElementById('updatePeopleNameDisplay').textContent = peopleName;
        document.getElementById('updateBattleInfoDisplay').textContent = battleInfo;
        
        // Set radio button based on current result
        if (battleResult === 'Won') {
            document.getElementById('updateWon').checked = true;
        } else {
            document.getElementById('updateLost').checked = true;
        }
    }
    function deletePersBat(peopleID, battleID, peopleName, battleInfo) { 
        showform('delete'); 
        document.getElementById('deletePeopleID').value = peopleID;
        document.getElementById('deleteBattleID').value = battleID;
        document.getElementById('deletePeopleIDDisplay').textContent = peopleID;
        document.getElementById('deleteBattleIDDisplay').textContent = battleID;
        document.getElementById('deletePeopleNameDisplay').textContent = peopleName;
        document.getElementById('deleteBattleInfoDisplay').textContent = battleInfo;
    }
    function browsePersBat() { showform('browse'); }
    function showAll() { showform('all'); }
</script>

<body>
    <header>
        <h1>Pokemon Management System Database</h1>
    </header>

    <nav>
        <a href="index.html">Home</a>
        <a href="pokemon.php">Pokemon</a>
        <a href="people.php">People</a>
        <a href="affiliations.php">Affiliations</a>
        <a href="types.php">Types</a>
        <a href="battles.php">Battles</a>
        <br><br>
        <a href="pokemontypes.html">Pokemon Types</a>
        <a href="pokemonpeople.html">Owned Pokemon</a>
        <a href="peoplebattles.php">Player Battles</a>
    </nav>

    <main>
        <section>
            <h2>Player Battles</h2>
            <?php echo $message; ?>

            <article id="playerBattles">
                <p id="center">
                    List of battles that people have been in
                </p>

                <div id="browse">
                    <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
                            onClick="browsePersBat()">Hide forms</a><br>
                        <a href="#" onClick="newPersBat()">New</a>
                    </p>

                    <table border="1" cellpadding="5" id="centerMargin">
                        <tr>
                            <th>Person ID</th>
                            <th>Person Name</th>
                            <th>Battle ID</th>
                            <th>Battle Info</th>
                            <th>Battle Result</th>
                            <th>Edit Entry</th>
                            <th>Delete Entry</th>
                        </tr>
                        <?php foreach ($playerBattles as $pb): ?>
                        <tr>
                            <td align="right"><?php echo htmlspecialchars($pb['peopleID']); ?></td>
                            <td><?php echo htmlspecialchars($pb['peopleName']); ?></td>
                            <td align="right"><?php echo htmlspecialchars($pb['battleID']); ?></td>
                            <td><?php echo htmlspecialchars($pb['battleDate'] . ' - ' . $pb['battleSetting']); ?></td>
                            <td><?php echo htmlspecialchars($pb['battleResult']); ?></td>
                            <td><a href="#" onClick="updatePersBat(<?php echo $pb['peopleID']; ?>, <?php echo $pb['battleID']; ?>, '<?php echo htmlspecialchars($pb['battleResult']); ?>', '<?php echo htmlspecialchars($pb['peopleName']); ?>', '<?php echo htmlspecialchars($pb['battleDate'] . ' - ' . $pb['battleSetting']); ?>')">Edit</a></td>
                            <td><a href="#" onclick="deletePersBat(<?php echo $pb['peopleID']; ?>, <?php echo $pb['battleID']; ?>, '<?php echo htmlspecialchars($pb['peopleName']); ?>', '<?php echo htmlspecialchars($pb['battleDate'] . ' - ' . $pb['battleSetting']); ?>')">Delete</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div> <!-- browse -->
                <p>&nbsp;</p>
                <div id="insert" style="display: none">
                    <form method="POST" id="addPersBat">
                        <input type="hidden" name="action" value="add">
                        <legend><strong>Add Player Battle</strong></legend>
                        <fieldset class="fields">
                            <label>* Battle </label>
                            <select name="battleID" style="margin-bottom: 10px" required>
                                <option value="">Select Battle...</option>
                                <?php foreach ($battles as $battle): ?>
                                <option value="<?php echo $battle['battleID']; ?>">
                                    <?php echo htmlspecialchars($battle['battleID'] . ' - ' . $battle['battleDate'] . ' (' . $battle['battleSetting'] . ')'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <label>* Player 1 </label>
                            <select name="player1ID" style="margin-bottom: 10px" required>
                                <option value="">Select Player 1...</option>
                                <?php foreach ($people as $person): ?>
                                <option value="<?php echo $person['peopleID']; ?>">
                                    <?php echo htmlspecialchars($person['peopleName']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <label>* Player 2 </label>
                            <select name="player2ID" style="margin-bottom: 10px" required>
                                <option value="">Select Player 2...</option>
                                <?php foreach ($people as $person): ?>
                                <option value="<?php echo $person['peopleID']; ?>">
                                    <?php echo htmlspecialchars($person['peopleName']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <label>* Who won the battle? </label>
                            <br>
                            <input type="radio" id="player1" name="battleWinner" value="player1" required
                                style="margin-bottom: 10px;">
                            <label for="player1">Player 1</label>
                            <br>
                            <input type="radio" id="player2" name="battleWinner" value="player2" required
                                style="margin-bottom: 10px;">
                            <label for="player2">Player 2</label>
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="addPersBat">Add Player Battle</button>
                            <button type="button" onClick="browsePersBat()">Cancel</button>
                        </p>
                    </form>
                </div><!-- insert -->
                <p>&nbsp;</p>
                <div id="update" style="display: none">
                    <form method="POST" id="updatePersBat">
                        <input type="hidden" name="action" value="update">
                        <legend><strong>Update Player Battle</strong></legend>
                        <fieldset class="fields">
                            <input type="hidden" name="peopleID" id="updatePeopleID">
                            <input type="hidden" name="battleID" id="updateBattleID">
                            <label><strong>Person ID: </strong></label> <span id="updatePeopleIDDisplay"></span>
                            <br>
                            <label><strong>Person Name: </strong></label> <span id="updatePeopleNameDisplay"></span>
                            <br>
                            <label><strong>Battle ID: </strong></label> <span id="updateBattleIDDisplay"></span>
                            <br>
                            <label><strong>Battle Info: </strong></label> <span id="updateBattleInfoDisplay"></span>
                            <br>
                            <label>* Battle Result: </label>
                            <br>
                            <input type="radio" id="updateWon" name="battleResult" value="Won"
                                style="margin-bottom: 10px">
                            <label for="updateWon">Won</label>
                            <br>
                            <input type="radio" id="updateLost" name="battleResult" value="Lost"
                                style="margin-bottom: 10px">
                            <label for="updateLost">Lost</label>
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="UpdateSavePersBat">Save Player Battle Updates</button>
                            <button type="button" onClick="browsePersBat()">Cancel</button>
                        </p>
                    </form>
                </div><!-- update -->
                <p>&nbsp;</p>
                <div id="delete" style="display: none">
                    <form method="POST" id="deletePersBat">
                        <input type="hidden" name="action" value="delete">
                        <legend><strong>Delete Player Battle</strong></legend>
                        <fieldset class="fields">
                            <p>Are you sure you want to delete this Player Battle from the database?</p>
                            <input type="hidden" name="peopleID" id="deletePeopleID">
                            <input type="hidden" name="battleID" id="deleteBattleID">
                            <label><strong>Person ID: </strong></label> <span id="deletePeopleIDDisplay"></span>
                            <br>
                            <label><strong>Person Name: </strong></label> <span id="deletePeopleNameDisplay"></span>
                            <br>
                            <label><strong>Battle ID: </strong></label> <span id="deleteBattleIDDisplay"></span>
                            <br>
                            <label><strong>Battle Info: </strong></label> <span id="deleteBattleInfoDisplay"></span>
                            <br><br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="deletePersBat">Delete Player Battle</button>
                            <button type="button" onClick="browsePersBat()">Cancel</button>
                        </p>
                    </form>
                </div><!-- delete -->
            </article>
        </section>
    </main>
    <footer>© 2025 - Elena Parapounsky & Amy Xu</footer>
</body>

</html>