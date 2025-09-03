<?php
// filepath: c:\git\pokemon-database\pokemontypes.php
require_once 'config.php';

// Handle form submissions
$message = '';
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("INSERT INTO Pokemon_Types (pokeID, typeID) VALUES (?, ?)");
                    $stmt->execute([$_POST['pokemon'], $_POST['type']]);
                    $message = "<div style='color: green;'>Pokemon Type added successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error adding pokemon type: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'update':
                try {
                    $stmt = $pdo->prepare("UPDATE Pokemon_Types SET pokeID = ?, typeID = ? WHERE pokeID = ? AND typeID = ?");
                    $stmt->execute([$_POST['pokemon'], $_POST['type'], $_POST['originalPokeID'], $_POST['originalTypeID']]);
                    $message = "<div style='color: green;'>Pokemon Type updated successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error updating pokemon type: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM Pokemon_Types WHERE pokeID = ? AND typeID = ?");
                    $stmt->execute([$_POST['pokeID'], $_POST['typeID']]);
                    $message = "<div style='color: green;'>Pokemon Type deleted successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error deleting pokemon type: " . $e->getMessage() . "</div>";
                }
                break;
        }
    }
}

// Fetch all Pokemon_Types with names for display
try {
    $stmt = $pdo->query("
        SELECT pt.pokeID, pt.typeID, p.pokeName, t.typeName
        FROM Pokemon_Types pt 
        JOIN Pokemon p ON pt.pokeID = p.pokeID 
        JOIN Types t ON pt.typeID = t.typeID 
        ORDER BY p.pokeName, t.typeName
    ");
    $pokemonTypes = $stmt->fetchAll();
} catch (Exception $e) {
    $pokemonTypes = [];
    $message = "<div style='color: red;'>Error fetching pokemon types: " . $e->getMessage() . "</div>";
}

// Fetch all pokemon for dropdowns
try {
    $stmt = $pdo->query("SELECT pokeID, pokeName FROM Pokemon ORDER BY pokeName");
    $pokemon = $stmt->fetchAll();
} catch (Exception $e) {
    $pokemon = [];
}

// Fetch all types for dropdowns
try {
    $stmt = $pdo->query("SELECT typeID, typeName FROM Types ORDER BY typeName");
    $types = $stmt->fetchAll();
} catch (Exception $e) {
    $types = [];
}
?>
<html>

<head>
  <title>Pokemon Management Database - Pokemon Types</title>
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
    function newPokeType() { showform('insert'); }
    function updatePokeType(pokeID, typeID, pokeName, typeName) { 
      showform('update'); 
      document.getElementById('updateOriginalPokeID').value = pokeID;
      document.getElementById('updateOriginalTypeID').value = typeID;
      document.getElementById('updatePokemon').value = pokeID;
      document.getElementById('updateType').value = typeID;
      document.getElementById('updateDisplayInfo').innerHTML = 
        '<strong>Current:</strong> ' + pokeName + ' - ' + typeName;
    }
    function deletePokeType(pokeID, typeID, pokeName, typeName) { 
      showform('delete'); 
      document.getElementById('deletePokeID').value = pokeID;
      document.getElementById('deleteTypeID').value = typeID;
      document.getElementById('deletePokemonNameDisplay').textContent = pokeName;
      document.getElementById('deleteTypeNameDisplay').textContent = typeName;
    }
    function showAll() { showform('all'); }
    function browsePokeType() { showform('browse'); }
  </script>
</head>

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
      <h2>Pokemon Typing</h2>
      <?php echo $message; ?>
      <article id="pokemontypes">
        <p id="center">
          List of Pokemon and their associated typing.
        </p>

        <div id="browse">
          <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
              onClick="browsePokeType()">Hide forms</a><br>
            <a href="#" onClick="newPokeType()">New</a>
          </p>
          <table border="1" cellpadding="5" id="centerMargin">
            <tr>
              <th>Pokemon</th>
              <th>Type</th>
              <th>Edit Entry</th>
              <th>Delete Entry</th>
            </tr>
            <?php foreach ($pokemonTypes as $pt): ?>
            <tr>
              <td><?php echo htmlspecialchars($pt['pokeName']); ?></td>
              <td><?php echo htmlspecialchars($pt['typeName']); ?></td>
              <td><a href="#" onClick="updatePokeType(<?php echo $pt['pokeID']; ?>, <?php echo $pt['typeID']; ?>, '<?php echo htmlspecialchars($pt['pokeName']); ?>', '<?php echo htmlspecialchars($pt['typeName']); ?>')">Edit</a></td>
              <td><a href="#" onclick="deletePokeType(<?php echo $pt['pokeID']; ?>, <?php echo $pt['typeID']; ?>, '<?php echo htmlspecialchars($pt['pokeName']); ?>', '<?php echo htmlspecialchars($pt['typeName']); ?>')">Delete</a></td>
            </tr>
            <?php endforeach; ?>
          </table>
          <p>&nbsp;</p>
        </div> <!-- browse -->

        <div id="insert" style="display: none">
          <form method="POST" id="addPokeType">
            <input type="hidden" name="action" value="add">
            <legend><strong>Add a Pokemon and their typing</strong></legend>
            <fieldset class="fields">
              <label>* Pokemon </label> <select name="pokemon" style="margin-bottom: 10px" required>
                <option value="">Select Pokemon...</option>
                <?php foreach ($pokemon as $poke): ?>
                <option value="<?php echo $poke['pokeID']; ?>">
                  <?php echo htmlspecialchars($poke['pokeName']); ?>
                </option>
                <?php endforeach; ?>
              </select>
              <br>
              <label>* Type </label> <select name="type" style="margin-bottom: 10px" required>
                <option value="">Select Type...</option>
                <?php foreach ($types as $type): ?>
                <option value="<?php echo $type['typeID']; ?>">
                  <?php echo htmlspecialchars($type['typeName']); ?>
                </option>
                <?php endforeach; ?>
              </select>
              <br>
              <button type="submit" id="addPokeType">Add Typing to Pokemon</button>
              <button type="button" onClick="browsePokeType()">Cancel</button>
              </p>
          </form>
        </div><!-- insert -->

        <p>&nbsp;</p>
        <div id="update" style="display: none">
          <form method="POST" id="updatePokeType">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="originalPokeID" id="updateOriginalPokeID">
            <input type="hidden" name="originalTypeID" id="updateOriginalTypeID">
            <legend><strong>Update Pokemon and their Typing</strong></legend>
            <fieldset class="fields">
              <div id="updateDisplayInfo" style="margin-bottom: 10px; font-weight: bold;"></div>
              <label> Pokemon </label> <select name="pokemon" id="updatePokemon" style="margin-bottom: 10px">
                <?php foreach ($pokemon as $poke): ?>
                <option value="<?php echo $poke['pokeID']; ?>">
                  <?php echo htmlspecialchars($poke['pokeName']); ?>
                </option>
                <?php endforeach; ?>
              </select>
              <br>
              <label> Type </label> <select name="type" id="updateType" style="margin-bottom: 10px">
                <?php foreach ($types as $type): ?>
                <option value="<?php echo $type['typeID']; ?>">
                  <?php echo htmlspecialchars($type['typeName']); ?>
                </option>
                <?php endforeach; ?>
              </select>
              <br><br>
            </fieldset>
            <p id="center">
              <button type="submit" id="updateSavePokeType">Save Pokemon Updates</button>
              <button type="button" onClick="browsePokeType()">Cancel</button>
            </p>
          </form>
        </div><!-- update -->

        <p>&nbsp;</p>
        <div id="delete" style="display: none">
          <form method="POST" id="deletePokeType">
            <input type="hidden" name="action" value="delete">
            <legend><strong>Delete Pokemon Typing</strong></legend>
            <fieldset class="fields">
              <p>Are you sure you want to delete this Pokemon Type pairing from the database?</p>
              <input type="hidden" name="pokeID" id="deletePokeID">
              <input type="hidden" name="typeID" id="deleteTypeID">
              <label><strong>Pokemon: </strong></label> <span id="deletePokemonNameDisplay"></span>
              <br>
              <label><strong>Type: </strong></label> <span id="deleteTypeNameDisplay"></span>
              <br><br>
            </fieldset>
            <p id="center">
              <button type="submit" id="deletePokeType">Delete Pokemon Typing</button>
              <button type="button" onClick="browsePokeType()">Cancel</button>
            </p>
          </form>
        </div><!-- delete -->
      </article>
    </section>
  </main>
  <footer>© 2025 - Elena Parapounsky & Amy Xu</footer>
</body>

</html>