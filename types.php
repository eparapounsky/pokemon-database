<?php
require_once 'config.php';

// Handle form submissions
$message = '';
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("INSERT INTO Types (typeName, weakAgainst, strongAgainst) VALUES (?, ?, ?)");
                    $stmt->execute([$_POST['typeName'], $_POST['weakAgainst'], $_POST['strongAgainst']]);
                    $message = "<div style='color: green;'>Type added successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error adding type: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'update':
                try {
                    $stmt = $pdo->prepare("UPDATE Types SET typeName = ?, weakAgainst = ?, strongAgainst = ? WHERE typeID = ?");
                    $stmt->execute([$_POST['typeName'], $_POST['weakAgainst'], $_POST['strongAgainst'], $_POST['typeID']]);
                    $message = "<div style='color: green;'>Type updated successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error updating type: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM Types WHERE typeID = ?");
                    $stmt->execute([$_POST['typeID']]);
                    $message = "<div style='color: green;'>Type deleted successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error deleting type: " . $e->getMessage() . "</div>";
                }
                break;
        }
    }
}

// Fetch all Types for display
try {
    $stmt = $pdo->query("SELECT typeID, typeName, weakAgainst, strongAgainst FROM Types ORDER BY typeID");
    $types = $stmt->fetchAll();
} catch (Exception $e) {
    $types = [];
    $message = "<div style='color: red;'>Error fetching types: " . $e->getMessage() . "</div>";
}

// Fetch all type names for dropdowns (excluding the "0" option)
try {
    $stmt = $pdo->query("SELECT DISTINCT typeName FROM Types ORDER BY typeName");
    $typeNames = $stmt->fetchAll();
} catch (Exception $e) {
    $typeNames = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Types</title>
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
    function newType() { showform('insert'); }
    function updateType(typeID, typeName, weakAgainst, strongAgainst) { 
        showform('update'); 
        document.getElementById('updateTypeID').value = typeID;
        document.getElementById('updateTypeIDDisplay').textContent = typeID;
        document.getElementById('updateTypeName').value = typeName;
        document.getElementById('updateWeakAgainst').value = weakAgainst.toLowerCase();
        document.getElementById('updateStrongAgainst').value = strongAgainst.toLowerCase();
    }
    function deleteType(typeID, typeName, weakAgainst, strongAgainst) { 
        showform('delete'); 
        document.getElementById('deleteTypeID').value = typeID;
        document.getElementById('deleteTypeIDDisplay').textContent = typeID;
        document.getElementById('deleteTypeNameDisplay').textContent = typeName;
        document.getElementById('deleteWeakAgainstDisplay').textContent = weakAgainst;
        document.getElementById('deleteStrongAgainstDisplay').textContent = strongAgainst;
    }
    function browseType() { showform('browse'); }
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
            <h2>Types</h2>
            <?php echo $message; ?>

            <article id="types">
                <p id="center">
                    Categories of Pokemon types
                </p>

                <div id="browse">
                    <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
                            onClick="browseType()">Hide forms</a><br>
                        <a href="#" onClick="newType()">New</a>
                    </p>

                    <table border="1" cellpadding="5" id="centerMargin">
                        <tr>
                            <th>Type ID</th>
                            <th>Type Name</th>
                            <th>Weak Against</th>
                            <th>Strong Against</th>
                            <th>Edit Entry</th>
                            <th>Delete Entry</th>
                        </tr>
                        <?php foreach ($types as $type): ?>
                        <tr>
                            <td align="right"><?php echo htmlspecialchars($type['typeID']); ?></td>
                            <td><?php echo htmlspecialchars($type['typeName']); ?></td>
                            <td><?php echo htmlspecialchars($type['weakAgainst']); ?></td>
                            <td><?php echo htmlspecialchars($type['strongAgainst']); ?></td>
                            <td><a href="#" onClick="updateType(<?php echo $type['typeID']; ?>, '<?php echo htmlspecialchars($type['typeName']); ?>', '<?php echo htmlspecialchars($type['weakAgainst']); ?>', '<?php echo htmlspecialchars($type['strongAgainst']); ?>')">Edit</a></td>
                            <td><a href="#" onclick="deleteType(<?php echo $type['typeID']; ?>, '<?php echo htmlspecialchars($type['typeName']); ?>', '<?php echo htmlspecialchars($type['weakAgainst']); ?>', '<?php echo htmlspecialchars($type['strongAgainst']); ?>')">Delete</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div> <!-- browse -->
                <p>&nbsp;</p>
                <div id="insert" style="display: none">
                    <form method="POST" id="addType">
                        <input type="hidden" name="action" value="add">
                        <legend><strong>Add Type</strong></legend>
                        <fieldset class="fields">
                            <label>* Type Name </label> <input type="text" name="typeName" style="margin-bottom: 10px"
                                required>
                            <br>
                            <label>* Weak Against </label>
                            <select name="weakAgainst" style="margin-bottom: 10px" required>
                                <option value="">Select Type...</option>
                                <?php foreach ($typeNames as $typeName): ?>
                                <option value="<?php echo strtolower($typeName['typeName']); ?>">
                                    <?php echo htmlspecialchars($typeName['typeName']); ?>
                                </option>
                                <?php endforeach; ?>
                                <option value="bug">Bug</option>
                                <option value="dark">Dark</option>
                                <option value="dragon">Dragon</option>
                                <option value="electric">Electric</option>
                                <option value="fairy">Fairy</option>
                                <option value="fighting">Fighting</option>
                                <option value="fire">Fire</option>
                                <option value="flying">Flying</option>
                                <option value="ghost">Ghost</option>
                                <option value="grass">Grass</option>
                                <option value="ground">Ground</option>
                                <option value="ice">Ice</option>
                                <option value="normal">Normal</option>
                                <option value="poison">Poison</option>
                                <option value="psychic">Psychic</option>
                                <option value="rock">Rock</option>
                                <option value="steel">Steel</option>
                                <option value="water">Water</option>
                            </select>
                            <br>
                            <label>* Strong Against </label>
                            <select name="strongAgainst" style="margin-bottom: 10px" required>
                                <option value="">Select Type...</option>
                                <?php foreach ($typeNames as $typeName): ?>
                                <option value="<?php echo strtolower($typeName['typeName']); ?>">
                                    <?php echo htmlspecialchars($typeName['typeName']); ?>
                                </option>
                                <?php endforeach; ?>
                                <option value="bug">Bug</option>
                                <option value="dark">Dark</option>
                                <option value="dragon">Dragon</option>
                                <option value="electric">Electric</option>
                                <option value="fairy">Fairy</option>
                                <option value="fighting">Fighting</option>
                                <option value="fire">Fire</option>
                                <option value="flying">Flying</option>
                                <option value="ghost">Ghost</option>
                                <option value="grass">Grass</option>
                                <option value="ground">Ground</option>
                                <option value="ice">Ice</option>
                                <option value="normal">Normal</option>
                                <option value="poison">Poison</option>
                                <option value="psychic">Psychic</option>
                                <option value="rock">Rock</option>
                                <option value="steel">Steel</option>
                                <option value="water">Water</option>
                            </select>
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="addType">Add Type</button>
                            <button type="button" onClick="browseType()">Cancel</button>
                        </p>
                    </form>
                </div><!-- insert -->
                <p>&nbsp;</p>
                <div id="update" style="display: none">
                    <form method="POST" id="updateType">
                        <input type="hidden" name="action" value="update">
                        <legend><strong>Update Type</strong></legend>
                        <fieldset class="fields">
                            <input type="hidden" name="typeID" id="updateTypeID">
                            <label> ID#: </label> <span id="updateTypeIDDisplay"></span>
                            <br>
                            <label> Type Name </label> <input type="text" name="typeName" id="updateTypeName" style="margin-bottom: 10px">
                            <br>
                            <label> Weak Against </label>
                            <select name="weakAgainst" id="updateWeakAgainst" style="margin-bottom: 10px">
                                <option value="">Select Type...</option>
                                <?php foreach ($typeNames as $typeName): ?>
                                <option value="<?php echo strtolower($typeName['typeName']); ?>">
                                    <?php echo htmlspecialchars($typeName['typeName']); ?>
                                </option>
                                <?php endforeach; ?>
                                <option value="bug">Bug</option>
                                <option value="dark">Dark</option>
                                <option value="dragon">Dragon</option>
                                <option value="electric">Electric</option>
                                <option value="fairy">Fairy</option>
                                <option value="fighting">Fighting</option>
                                <option value="fire">Fire</option>
                                <option value="flying">Flying</option>
                                <option value="ghost">Ghost</option>
                                <option value="grass">Grass</option>
                                <option value="ground">Ground</option>
                                <option value="ice">Ice</option>
                                <option value="normal">Normal</option>
                                <option value="poison">Poison</option>
                                <option value="psychic">Psychic</option>
                                <option value="rock">Rock</option>
                                <option value="steel">Steel</option>
                                <option value="water">Water</option>
                            </select>
                            <br>
                            <label> Strong Against </label>
                            <select name="strongAgainst" id="updateStrongAgainst" style="margin-bottom: 10px">
                                <option value="">Select Type...</option>
                                <?php foreach ($typeNames as $typeName): ?>
                                <option value="<?php echo strtolower($typeName['typeName']); ?>">
                                    <?php echo htmlspecialchars($typeName['typeName']); ?>
                                </option>
                                <?php endforeach; ?>
                                <option value="bug">Bug</option>
                                <option value="dark">Dark</option>
                                <option value="dragon">Dragon</option>
                                <option value="electric">Electric</option>
                                <option value="fairy">Fairy</option>
                                <option value="fighting">Fighting</option>
                                <option value="fire">Fire</option>
                                <option value="flying">Flying</option>
                                <option value="ghost">Ghost</option>
                                <option value="grass">Grass</option>
                                <option value="ground">Ground</option>
                                <option value="ice">Ice</option>
                                <option value="normal">Normal</option>
                                <option value="poison">Poison</option>
                                <option value="psychic">Psychic</option>
                                <option value="rock">Rock</option>
                                <option value="steel">Steel</option>
                                <option value="water">Water</option>
                            </select>
                            <br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="UpdateSaveType">Save Type Updates</button>
                            <button type="button" onClick="browseType()">Cancel</button>
                        </p>
                    </form>
                </div><!-- update -->
                <p>&nbsp;</p>
                <div id="delete" style="display: none">
                    <form method="POST" id="deleteType">
                        <input type="hidden" name="action" value="delete">
                        <legend><strong>Delete Type</strong></legend>
                        <fieldset class="fields">
                            <p>Are you sure you want to delete this Type from the database?</p>
                            <input type="hidden" name="typeID" id="deleteTypeID">
                            <label><strong>ID#: </strong></label> <span id="deleteTypeIDDisplay"></span>
                            <br>
                            <label> <strong>Type Name: </strong> </label> <span id="deleteTypeNameDisplay"></span>
                            <br>
                            <label> <strong>Weak Against: </strong> </label> <span id="deleteWeakAgainstDisplay"></span>
                            <br>
                            <label> <strong>Strong Against: </strong> </label> <span id="deleteStrongAgainstDisplay"></span>
                            <br><br>
                        </fieldset>
                        <p id="center">
                            <button type="submit" id="deleteType">Delete Type</button>
                            <button type="button" onClick="browseType()">Cancel</button>
                        </p>
                    </form>
                </div><!-- delete -->
            </article>
        </section>
    </main>
    <footer>© 2025 - Elena Parapounsky & Amy Xu</footer>
</body>

</html>