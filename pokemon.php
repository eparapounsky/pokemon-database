<?php
require_once 'config.php';

// Handle form submissions
$message = '';
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("INSERT INTO Pokemon (pokeName, isBaby, pokeRarity) VALUES (?, ?, ?)");
                    $stmt->execute([
                        $_POST['pokeName'],
                        $_POST['isBaby'],
                        $_POST['pokeRarity']
                    ]);
                    $message = "<div style='color: green;'>Pokemon added successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'update':
                try {
                    $stmt = $pdo->prepare("UPDATE Pokemon SET pokeName = ?, isBaby = ?, pokeRarity = ? WHERE pokeID = ?");
                    $stmt->execute([
                        $_POST['pokeName'],
                        $_POST['isBaby'],
                        $_POST['pokeRarity'],
                        $_POST['pokeID']
                    ]);
                    $message = "<div style='color: green;'>Pokemon updated successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
                }
                break;
                
            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM Pokemon WHERE pokeID = ?");
                    $stmt->execute([$_POST['pokeID']]);
                    $message = "<div style='color: green;'>Pokemon deleted successfully!</div>";
                } catch (Exception $e) {
                    $message = "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
                }
                break;
        }
    }
}

// Fetch all Pokemon for display
try {
    $stmt = $pdo->query("SELECT pokeID, pokeName, isBaby, pokeRarity FROM Pokemon ORDER BY pokeID");
    $pokemon = $stmt->fetchAll();
} catch (Exception $e) {
    $pokemon = [];
    $message = "<div style='color: red;'>Error fetching Pokemon: " . $e->getMessage() . "</div>";
}
?>
<html>

<head>
  <title>Pokemon Management Database - Pokemon</title>
  <link href="main.css" rel="stylesheet" type="text/css" />
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  
  <script language="JavaScript">
    function showform(dowhat) {
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
      else {
        document.getElementById('browse').style.display = 'block';
        document.getElementById('insert').style.display = 'none';
        document.getElementById('update').style.display = 'none';
        document.getElementById('delete').style.display = 'none';
      }
    }
    function newPokemon() { showform('insert'); }
    function updatePokemon(pokeID, pokeName, isBaby, pokeRarity) { 
      document.getElementById('updatePokemonID').value = pokeID;
      document.getElementById('updatePokeName').value = pokeName;
      document.getElementById('updateIsBaby').value = isBaby;
      document.getElementById('updatePokeRarity').value = pokeRarity;
      showform('update'); 
    }
    function deletePokemon(pokeID, pokeName) { 
      document.getElementById('deletePokemonID').value = pokeID;
      document.getElementById('deletePokemonName').textContent = pokeName;
      showform('delete'); 
    }
    function showAll() { showform('all'); }
    function browsePokemon() { showform('browse'); }
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
      <h2>Pokemon</h2>
      <?php echo $message; ?>
      <article id="pokemon">
        <p id="center">
          List of known Pokemon.
        </p>

        <div id="browse">
          <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
              onClick="browsePokemon()">Hide forms</a><br>
            <a href="#" onClick="newPokemon()">New</a>
          </p>
          <table border="1" cellpadding="5" id="centerMargin">
            <tr>
              <th>Pokemon ID</th>
              <th>Pokemon Name</th>
              <th>Is Baby?</th>
              <th>Pokemon Rarity</th>
              <th>Edit Entry</th>
              <th>Delete Entry</th>
            </tr>
            <?php foreach ($pokemon as $poke): ?>
            <tr>
              <td align="right"><?php echo htmlspecialchars($poke['pokeID']); ?></td>
              <td><?php echo htmlspecialchars($poke['pokeName']); ?></td>
              <td><?php echo $poke['isBaby'] ? 'True' : 'False'; ?></td>
              <td><?php echo htmlspecialchars($poke['pokeRarity']); ?></td>
              <td><a href="#" onClick="updatePokemon(<?php echo $poke['pokeID']; ?>, '<?php echo htmlspecialchars($poke['pokeName']); ?>', <?php echo $poke['isBaby']; ?>, '<?php echo htmlspecialchars($poke['pokeRarity']); ?>')">Edit</a></td>
              <td><a href="#" onclick="deletePokemon(<?php echo $poke['pokeID']; ?>, '<?php echo htmlspecialchars($poke['pokeName']); ?>')">Delete</a></td>
            </tr>
            <?php endforeach; ?>
          </table>
          <p>&nbsp;</p>
        </div>

        <div id="insert" style="display: none">
          <form method="POST" action="pokemon.php">
            <input type="hidden" name="action" value="add">
            <legend><strong>Add Pokemon</strong></legend>
            <fieldset class="fields">
              <label>* Pokemon Name </label> <input type="text" name="pokeName" style="margin-bottom: 10px" required>
              <br>
              <label>* Is it a baby Pokemon? </label> <select name="isBaby" style="margin-bottom: 10px" required>
                <option value="">Select...</option>
                <option value="1">True</option>
                <option value="0">False</option>
              </select>
              <br>
              <label>* Rarity </label> <select name="pokeRarity" style="margin-bottom: 10px" required>
                <option value="">Select...</option>
                <option value="Common">Common</option>
                <option value="Rare">Rare</option>
                <option value="Legendary">Legendary</option>
                <option value="Mythical">Mythical</option>
              </select>
            </fieldset>
            <p id="center">
              <button type="submit">Add Pokemon</button>
              <button type="button" onClick="browsePokemon()">Cancel</button>
            </p>
          </form>
        </div>

        <div id="update" style="display: none">
          <form method="POST" action="pokemon.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="pokeID" id="updatePokemonID">
            <legend><strong>Update Pokemon</strong></legend>
            <fieldset class="fields">
              <label> Pokemon Name </label> <input type="text" name="pokeName" id="updatePokeName" style="margin-bottom: 10px">
              <br>
              <label> Is it a baby Pokemon? </label> <select name="isBaby" id="updateIsBaby" style="margin-bottom: 10px">
                <option value="0">False</option>
                <option value="1">True</option>
              </select>
              <br>
              <label> Rarity </label> <select name="pokeRarity" id="updatePokeRarity" style="margin-bottom: 10px">
                <option value="Common">Common</option>
                <option value="Rare">Rare</option>
                <option value="Legendary">Legendary</option>
                <option value="Mythical">Mythical</option>
              </select>
            </fieldset>
            <p id="center">
              <button type="submit">Save Pokemon Updates</button>
              <button type="button" onClick="browsePokemon()">Cancel</button>
            </p>
          </form>
        </div>

        <div id="delete" style="display: none">
          <form method="POST" action="pokemon.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="pokeID" id="deletePokemonID">
            <legend><strong>Delete Pokemon</strong></legend>
            <fieldset class="fields">
              <p>Are you sure you want to delete this Pokemon from the database?</p>
              <label><strong>Pokemon Name: </strong></label> <span id="deletePokemonName"></span>
            </fieldset>
            <p id="center">
              <button type="submit">Delete Pokemon</button>
              <button type="button" onClick="browsePokemon()">Cancel</button>
            </p>
          </form>
        </div>
      </article>
    </section>
  </main>
  <footer>© 2025 - Elena Parapounsky & Amy Xu</footer>
</body>

</html>
