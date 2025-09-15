<?php
require_once 'config.php';

// Handle form submissions
$message = '';
if ($_POST) {
  if (isset($_POST['action'])) {
    switch ($_POST['action']) {
      case 'add':
        try {
          $stmt = $pdo->prepare("INSERT INTO Pokemon_People (pokeID, peopleID, pokeNickname, caughtDate, caughtAt) VALUES (?, ?, ?, ?, ?)");
          $stmt->execute([$_POST['pokemon'], $_POST['people'], $_POST['pokeNickname'], $_POST['caughtDate'], $_POST['caughtAt']]);
          $message = "<div style='color: green;'>Owned Pokemon added successfully!</div>";
        } catch (Exception $e) {
          $message = "<div style='color: red;'>Error adding owned pokemon: " . $e->getMessage() . "</div>";
        }
        break;

      case 'update':
        try {
          $stmt = $pdo->prepare("UPDATE Pokemon_People SET pokeID = ?, peopleID = ?, pokeNickname = ?, caughtDate = ?, caughtAt = ? WHERE pokeID = ? AND peopleID = ?");
          $stmt->execute([$_POST['pokemon'], $_POST['people'], $_POST['pokeNickname'], $_POST['caughtDate'], $_POST['caughtAt'], $_POST['originalPokeID'], $_POST['originalPeopleID']]);
          $message = "<div style='color: green;'>Owned Pokemon updated successfully!</div>";
        } catch (Exception $e) {
          $message = "<div style='color: red;'>Error updating owned pokemon: " . $e->getMessage() . "</div>";
        }
        break;

      case 'delete':
        try {
          $stmt = $pdo->prepare("DELETE FROM Pokemon_People WHERE pokeID = ? AND peopleID = ?");
          $stmt->execute([$_POST['pokeID'], $_POST['peopleID']]);
          $message = "<div style='color: green;'>Owned Pokemon deleted successfully!</div>";
        } catch (Exception $e) {
          $message = "<div style='color: red;'>Error deleting owned pokemon: " . $e->getMessage() . "</div>";
        }
        break;
    }
  }
}

// Fetch all Pokemon_People with names for display
try {
  $stmt = $pdo->query("
        SELECT pp.pokeID, pp.peopleID, pp.pokeNickname, pp.caughtDate, pp.caughtAt,
               p.pokeName, pe.peopleName
        FROM Pokemon_People pp 
        JOIN Pokemon p ON pp.pokeID = p.pokeID 
        JOIN People pe ON pp.peopleID = pe.peopleID 
        ORDER BY pp.peopleID, pp.pokeID
    ");
  $ownedPokemon = $stmt->fetchAll();
} catch (Exception $e) {
  $ownedPokemon = [];
  $message = "<div style='color: red;'>Error fetching owned pokemon: " . $e->getMessage() . "</div>";
}

// Fetch all people for dropdowns
try {
  $stmt = $pdo->query("SELECT peopleID, peopleName FROM People ORDER BY peopleName");
  $people = $stmt->fetchAll();
} catch (Exception $e) {
  $people = [];
}

// Fetch all pokemon for dropdowns
try {
  $stmt = $pdo->query("SELECT pokeID, pokeName FROM Pokemon ORDER BY pokeName");
  $pokemon = $stmt->fetchAll();
} catch (Exception $e) {
  $pokemon = [];
}
?>
<html>

<head>
  <title>Owned Pokemon</title>
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
    function newPokePers() { showform('insert'); }
    function updatePokePers(pokeID, peopleID, pokeName, peopleName, pokeNickname, caughtDate, caughtAt) {
      showform('update');
      document.getElementById('updateOriginalPokeID').value = pokeID;
      document.getElementById('updateOriginalPeopleID').value = peopleID;
      document.getElementById('updatePokemon').value = pokeID;
      document.getElementById('updatePeople').value = peopleID;
      document.getElementById('updatePokeNickname').value = pokeNickname || '';
      document.getElementById('updateCaughtDate').value = caughtDate;
      document.getElementById('updateCaughtAt').value = caughtAt;
      document.getElementById('updateDisplayInfo').innerHTML =
        '<strong>Current:</strong> ' + pokeName + ' owned by ' + peopleName;
    }
    function deletePokePers(pokeID, peopleID, pokeName, peopleName, pokeNickname, caughtAt) {
      showform('delete');
      document.getElementById('deletePokeID').value = pokeID;
      document.getElementById('deletePeopleID').value = peopleID;
      document.getElementById('deletePokemonNameDisplay').textContent = pokeName;
      document.getElementById('deleteOwnerNameDisplay').textContent = peopleName;
      document.getElementById('deleteNicknameDisplay').textContent = pokeNickname || 'None';
      document.getElementById('deleteCaughtLocationDisplay').textContent = caughtAt;
    }
    function showAll() { showform('all'); }
    function browsePokePers() { showform('browse'); }
  </script>
</head>

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
      <h2>Owned Pokemon</h2>
      <?php echo $message; ?>
      <article id="pokemonpeople">
        <p id="center">
          List of Pokemon that are owned by trainers.
        </p>

        <div id="browse">
          <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
              onClick="browsePokePers()">Hide forms</a><br>
            <a href="#" onClick="newPokePers()">New</a>
          </p>
          <table border="1" cellpadding="5" id="centerMargin">
            <tr>
              <th>Pokemon</th>
              <th>Owner</th>
              <th>Pokemon Nickname</th>
              <th>Caught Date</th>
              <th>Caught Location</th>
              <th>Edit Entry</th>
              <th>Delete Entry</th>
            </tr>
            <?php foreach ($ownedPokemon as $owned): ?>
              <tr>
                <td><?php echo htmlspecialchars($owned['pokeName']); ?></td>
                <td><?php echo htmlspecialchars($owned['peopleName']); ?></td>
                <td><?php echo htmlspecialchars($owned['pokeNickname'] ?? ''); ?></td>
                <td align="right"><?php echo htmlspecialchars($owned['caughtDate']); ?></td>
                <td><?php echo htmlspecialchars($owned['caughtAt']); ?></td>
                <td><a href="#"
                    onClick="updatePokePers(<?php echo $owned['pokeID']; ?>, <?php echo $owned['peopleID']; ?>, '<?php echo htmlspecialchars($owned['pokeName']); ?>', '<?php echo htmlspecialchars($owned['peopleName']); ?>', '<?php echo htmlspecialchars($owned['pokeNickname'] ?? ''); ?>', '<?php echo $owned['caughtDate']; ?>', '<?php echo htmlspecialchars($owned['caughtAt']); ?>')">Edit</a>
                </td>
                <td><a href="#"
                    onclick="deletePokePers(<?php echo $owned['pokeID']; ?>, <?php echo $owned['peopleID']; ?>, '<?php echo htmlspecialchars($owned['pokeName']); ?>', '<?php echo htmlspecialchars($owned['peopleName']); ?>', '<?php echo htmlspecialchars($owned['pokeNickname'] ?? ''); ?>', '<?php echo htmlspecialchars($owned['caughtAt']); ?>')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
          <p>&nbsp;</p>
        </div> <!-- browse -->

        <div id="insert" style="display: none">
          <form method="POST" id="addPokePers">
            <input type="hidden" name="action" value="add">
            <legend><strong>Add an owned Pokemon</strong></legend>
            <fieldset class="fields">
              <label>* Owner </label> <select name="people" style="margin-bottom: 10px" required>
                <option value="">Select Owner...</option>
                <?php foreach ($people as $person): ?>
                  <option value="<?php echo $person['peopleID']; ?>">
                    <?php echo htmlspecialchars($person['peopleName']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <br>
              <label>* Pokemon </label> <select name="pokemon" style="margin-bottom: 10px" required>
                <option value="">Select Pokemon...</option>
                <?php foreach ($pokemon as $poke): ?>
                  <option value="<?php echo $poke['pokeID']; ?>">
                    <?php echo htmlspecialchars($poke['pokeName']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <br>
              <label> Nickname </label> <input type="text" name="pokeNickname" style="margin-bottom: 10px">
              <br>
              <label>* Caught Date</label> <input type="date" name="caughtDate" style="margin-bottom: 10px" required>
              <br>
              <label>* Caught Location </label> <input type="text" name="caughtAt" style="margin-bottom: 10px" required>
              <br>
              <button type="submit" id="addPokePers">Add Pokemon</button>
              <button type="button" onClick="browsePokePers()">Cancel</button>
              </p>
          </form>
        </div><!-- insert -->

        <p>&nbsp;</p>
        <div id="update" style="display: none">
          <form method="POST" id="updatePokePers">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="originalPokeID" id="updateOriginalPokeID">
            <input type="hidden" name="originalPeopleID" id="updateOriginalPeopleID">
            <legend><strong>Update Owned Pokemon</strong></legend>
            <fieldset class="fields">
              <div id="updateDisplayInfo" style="margin-bottom: 10px; font-weight: bold;"></div>
              <label> Owner </label> <select name="people" id="updatePeople" style="margin-bottom: 10px">
                <?php foreach ($people as $person): ?>
                  <option value="<?php echo $person['peopleID']; ?>">
                    <?php echo htmlspecialchars($person['peopleName']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <br>
              <label> Pokemon </label> <select name="pokemon" id="updatePokemon" style="margin-bottom: 10px">
                <?php foreach ($pokemon as $poke): ?>
                  <option value="<?php echo $poke['pokeID']; ?>">
                    <?php echo htmlspecialchars($poke['pokeName']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <br>
              <label> Nickname </label> <input type="text" name="pokeNickname" id="updatePokeNickname"
                style="margin-bottom: 10px">
              <br>
              <label>Caught Date</label> <input type="date" name="caughtDate" id="updateCaughtDate"
                style="margin-bottom: 10px">
              <br>
              <label> Caught Location </label> <input type="text" name="caughtAt" id="updateCaughtAt"
                style="margin-bottom: 10px">
              <br><br>
            </fieldset>
            <p id="center">
              <button type="submit" id="updateSavePokePers">Save Pokemon Updates</button>
              <button type="button" onClick="browsePokePers()">Cancel</button>
            </p>
          </form>
        </div><!-- update -->

        <p>&nbsp;</p>
        <div id="delete" style="display: none">
          <form method="POST" id="deletePokePers">
            <input type="hidden" name="action" value="delete">
            <legend><strong>Delete Owned Pokemon</strong></legend>
            <fieldset class="fields">
              <p>Are you sure you want to delete this Pokemon ownership from the database?</p>
              <input type="hidden" name="pokeID" id="deletePokeID">
              <input type="hidden" name="peopleID" id="deletePeopleID">
              <label><strong>Pokemon: </strong></label> <span id="deletePokemonNameDisplay"></span>
              <br>
              <label><strong>Nickname: </strong></label> <span id="deleteNicknameDisplay"></span>
              <br>
              <label><strong>Owner: </strong></label> <span id="deleteOwnerNameDisplay"></span>
              <br>
              <label><strong>Caught Location: </strong></label> <span id="deleteCaughtLocationDisplay"></span>
              <br><br>
            </fieldset>
            <p id="center">
              <button type="submit" id="deletePokePers">Delete Pokemon</button>
              <button type="button" onClick="browsePokePers()">Cancel</button>
            </p>
          </form>
        </div><!-- delete -->

      </article>
    </section>
  </main>
  <footer>© 2025 - Elena Parapounsky & Amy Xu
        <br>
        <a href="https://github.com/eparapounsky/pokemon-database">🔍 Source on GitHub</a>
    </footer>
</body>

</html>