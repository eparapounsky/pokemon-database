<?php
// filepath: c:\git\pokemon-database\affiliations.php
<?php
require_once 'config.php';

// Handle form submissions
$message = '';
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("INSERT INTO Affiliations (affiliationType, affiliationRank, typeDescription) VALUES (?, ?, ?)");
                    $stmt->execute([$_POST['affiliationType'], $_POST['affiliationRank'], $_POST['typeDescription']]);
                    $message = "<div style='color: green;'>Affiliation added successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error adding affiliation: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'update':
                try {
                    $stmt = $pdo->prepare("UPDATE Affiliations SET affiliationType = ?, affiliationRank = ?, typeDescription = ? WHERE affiliationID = ?");
                    $stmt->execute([$_POST['affiliationType'], $_POST['affiliationRank'], $_POST['typeDescription'], $_POST['affiliationID']]);
                    $message = "<div style='color: green;'>Affiliation updated successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error updating affiliation: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM Affiliations WHERE affiliationID = ?");
                    $stmt->execute([$_POST['affiliationID']]);
                    $message = "<div style='color: green;'>Affiliation deleted successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error deleting affiliation: " . $e->getMessage() . "</div>";
                }
                break;
        }
    }
}

// Fetch all Affiliations for display
try {
    $stmt = $pdo->query("SELECT affiliationID, affiliationType, affiliationRank, typeDescription FROM Affiliations ORDER BY affiliationID");
    $affiliations = $stmt->fetchAll();
} catch (Exception $e) {
    $affiliations = [];
    $message = "<div style='color: red;'>Error fetching affiliations: " . $e->getMessage() . "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Affiliations</title>
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
    function newAffiliation() { showform('insert'); }
    function updateAffiliation(affiliationID, affiliationType, affiliationRank, typeDescription) { 
        showform('update'); 
        document.getElementById('updateAffiliationID').value = affiliationID;
        document.getElementById('updateAffiliationIDDisplay').textContent = affiliationID;
        document.getElementById('updateAffiliationType').value = affiliationType;
        document.getElementById('updateAffiliationRank').value = affiliationRank;
        document.getElementById('updateTypeDescription').value = typeDescription;
    }
    function deleteAffiliation(affiliationID, affiliationType, affiliationRank) { 
        showform('delete'); 
        document.getElementById('deleteAffiliationID').value = affiliationID;
        document.getElementById('deleteAffiliationIDDisplay').textContent = affiliationID;
        document.getElementById('deleteAffiliationTypeDisplay').textContent = affiliationType;
        document.getElementById('deleteAffiliationRankDisplay').textContent = affiliationRank;
    }
    function browseAffiliation() { showform('browse'); }
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
        <a href="pokemontypes.php">Pokemon Types</a>
        <a href="pokemonpeople.php">Owned Pokemon</a>
        <a href="peoplebattles.php">Player Battles</a>
    </nav>

    <main>
        <section>
            <h2>Affiliations</h2>
            <?php echo $message; ?>

            <article id="affiliations">
                <p id="center">
                    Affiliations and ranks of people
                </p>

                <div id="browse">
                    <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
                            onClick="browseAffiliation()">Hide forms</a><br>
                        <a href="#" onClick="newAffiliation()">New</a>
                    </p>

                    <table border="1" cellpadding="5" id="centerMargin">
                        <tr>
                            <th>Affiliation ID</th>
                            <th>Affiliation Type</th>
                            <th>Affiliation Rank</th>
                            <th>Type Description</th>
                            <th>Edit Entry</th>
                            <th>Delete Entry</th>
                        </tr>
                        <?php foreach ($affiliations as $affiliation): ?>
                        <tr>
                            <td align="right"><?php echo htmlspecialchars($affiliation['affiliationID']); ?></td>
                            <td><?php echo htmlspecialchars($affiliation['affiliationType']); ?></td>
                            <td><?php echo htmlspecialchars($affiliation['affiliationRank']); ?></td>
                            <td><?php echo htmlspecialchars($affiliation['typeDescription'] ?? ''); ?></td>
                            <td><a href="#" onClick="updateAffiliation(<?php echo $affiliation['affiliationID']; ?>, '<?php echo htmlspecialchars($affiliation['affiliationType']); ?>', '<?php echo htmlspecialchars($affiliation['affiliationRank']); ?>', '<?php echo htmlspecialchars($affiliation['typeDescription'] ?? ''); ?>')">Edit</a></td>
                            <td><a href="#" onclick="deleteAffiliation(<?php echo $affiliation['affiliationID']; ?>, '<?php echo htmlspecialchars($affiliation['affiliationType']); ?>', '<?php echo htmlspecialchars($affiliation['affiliationRank']); ?>')">Delete</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div> <!-- browse -->
                <p>&nbsp;</p>
                <div id="insert" style="display: none">
                    <form method="POST" id="addAffiliation">
                        <input type="hidden" name="action" value="add">
                        <legend><strong>Add Affiliation</strong></legend>
                        <fieldset class="fields">
                            <label>* Affiliation Type </label> <input type="text" name="affiliationType"
                                style="margin-bottom: 10px" required>
                            <br>
                            <label>* Affiliation Rank </label> <input type="text" name="affiliationRank"
                                style="margin-bottom: 10px" required>
                            <br>
                            <label> Type Description </label> <input type="text" name="typeDescription"
                                style="margin-bottom: 10px">
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="addAffiliation">Add Affiliation</button>
                            <button type="button" onClick="browseAffiliation()">Cancel</button>
                        </p>
                    </form>
                </div><!-- insert -->
                <p>&nbsp;</p>
                <div id="update" style="display: none">
                    <form method="POST" id="updateAffiliation">
                        <input type="hidden" name="action" value="update">
                        <legend><strong>Update Affiliation</strong></legend>
                        <fieldset class="fields">
                            <input type="hidden" name="affiliationID" id="updateAffiliationID">
                            <label> ID#: </label> <span id="updateAffiliationIDDisplay"></span>
                            <br>
                            <label> Affiliation Type </label> <input type="text" name="affiliationType" id="updateAffiliationType"
                                style="margin-bottom: 10px">
                            <br>
                            <label> Affiliation Rank </label> <input type="text" name="affiliationRank" id="updateAffiliationRank"
                                style="margin-bottom: 10px">
                            <br>
                            <label> Type Description </label> <input type="text" name="typeDescription" id="updateTypeDescription"
                                style="margin-bottom: 10px">
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="UpdateSaveAffiliation">Save Affiliation Updates</button>
                            <button type="button" onClick="browseAffiliation()">Cancel</button>
                        </p>
                    </form>
                </div><!-- update -->
                <p>&nbsp;</p>
                <div id="delete" style="display: none">
                    <form method="POST" id="deleteAffiliation">
                        <input type="hidden" name="action" value="delete">
                        <legend><strong>Delete Affiliation</strong></legend>
                        <fieldset class="fields">
                            <p>Are you sure you want to delete this Affiliation from the database?</p>
                            <input type="hidden" name="affiliationID" id="deleteAffiliationID">
                            <label><strong>ID#: </strong></label> <span id="deleteAffiliationIDDisplay"></span>
                            <br>
                            <label> <strong>Affiliation Type: </strong> </label> <span id="deleteAffiliationTypeDisplay"></span>
                            <br>
                            <label> <strong>Affiliation Rank: </strong> </label> <span id="deleteAffiliationRankDisplay"></span>
                            <br><br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="deleteAffiliation">Delete Affiliation</button>
                            <button type="button" onClick="browseAffiliation()">Cancel</button>
                        </p>
                    </form>
                </div><!-- delete -->
            </article>
        </section>
    </main>
    <footer>© 2025 - Elena Parapounsky & Amy Xu</footer>
</body>

</html>