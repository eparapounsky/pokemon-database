<?php
require_once 'config.php';

// Handle form submissions
$message = '';
if ($_POST) {
  if (isset($_POST['action'])) {
    switch ($_POST['action']) {
      case 'add':
        try {
          $stmt = $pdo->prepare("INSERT INTO People (peopleName, affiliationID) VALUES (?, ?)");
          $stmt->execute([$_POST['peopleName'], $_POST['affiliation']]);
          $message = "<div style='color: green;'>Person added successfully!</div>";
        } catch (Exception $e) {
          $message = "<div style='color: red;'>Error adding person: " . $e->getMessage() . "</div>";
        }
        break;

      case 'update':
        try {
          $stmt = $pdo->prepare("UPDATE People SET peopleName = ?, affiliationID = ? WHERE peopleID = ?");
          $stmt->execute([$_POST['peopleName'], $_POST['affiliation'], $_POST['peopleID']]);
          $message = "<div style='color: green;'>Person updated successfully!</div>";
        } catch (Exception $e) {
          $message = "<div style='color: red;'>Error updating person: " . $e->getMessage() . "</div>";
        }
        break;

      case 'delete':
        try {
          $stmt = $pdo->prepare("DELETE FROM People WHERE peopleID = ?");
          $stmt->execute([$_POST['peopleID']]);
          $message = "<div style='color: green;'>Person deleted successfully!</div>";
        } catch (Exception $e) {
          $message = "<div style='color: red;'>Error deleting person: " . $e->getMessage() . "</div>";
        }
        break;
    }
  }
}

// Fetch all People with their affiliations for display
try {
  $stmt = $pdo->query("
        SELECT p.peopleID, p.peopleName, p.affiliationID,
               CONCAT(a.affiliationType, ' ', a.affiliationRank) AS affiliationName
        FROM People p 
        LEFT JOIN Affiliations a ON p.affiliationID = a.affiliationID 
        ORDER BY p.peopleID
    ");
  $people = $stmt->fetchAll();
} catch (Exception $e) {
  $people = [];
  $message = "<div style='color: red;'>Error fetching people: " . $e->getMessage() . "</div>";
}

// Fetch all affiliations for dropdown
try {
  $stmt = $pdo->query("
        SELECT affiliationID, 
               CONCAT(affiliationType, ' ', affiliationRank) AS affiliationName 
        FROM Affiliations 
        ORDER BY affiliationType, affiliationRank
    ");
  $affiliations = $stmt->fetchAll();
} catch (Exception $e) {
  $affiliations = [];
}
?>

<html>

<head>
  <title>People</title>
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
    function newPerson() { showform('insert'); }
    function updatePerson(peopleID, peopleName, affiliationID, affiliationName) {
      showform('update');
      document.getElementById('updatePersonID').value = peopleID;
      document.getElementById('updatePersonIDDisplay').textContent = peopleID;
      document.getElementById('updatePersonName').value = peopleName;
      document.getElementById('updatePersonAffiliation').value = affiliationID;
    }
    function deletePerson(peopleID, peopleName, affiliationName) {
      showform('delete');
      document.getElementById('deletePersonID').value = peopleID;
      document.getElementById('deletePersonIDDisplay').textContent = peopleID;
      document.getElementById('deletePersonNameDisplay').textContent = peopleName;
      document.getElementById('deletePersonAffiliationDisplay').textContent = affiliationName;
    }
    function showAll() { showform('all'); }
    function browsePerson() { showform('browse'); }
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
      <h2>People</h2>
      <?php echo $message; ?>
      <article id="people">
        <p id="center">
          List of people (Trainers, Gym Leaders, Team Rocket, Professors)
        </p>

        <div id="browse">
          <p id="center"><a href="#" onClick="showAll()">Display all forms</a> | <a href="#"
              onClick="browsePerson()">Hide forms</a><br>
            <a href="#" onClick="newPerson()">New</a>
          </p>
          <table border="1" cellpadding="5" id="centerMargin">
            <tr>
              <th>Person ID</th>
              <th>Person Name</th>
              <th>Person Affiliation</th>
              <th>Edit Entry</th>
              <th>Delete Entry</th>
            </tr>
            <?php foreach ($people as $person): ?>
              <tr>
                <td align="right"><?php echo htmlspecialchars($person['peopleID']); ?></td>
                <td><?php echo htmlspecialchars($person['peopleName']); ?></td>
                <td><?php echo htmlspecialchars($person['affiliationName'] ?? 'No Affiliation'); ?></td>
                <td><a href="#"
                    onClick="updatePerson(<?php echo $person['peopleID']; ?>, '<?php echo htmlspecialchars($person['peopleName']); ?>', <?php echo $person['affiliationID'] ?? 0; ?>, '<?php echo htmlspecialchars($person['affiliationName'] ?? ''); ?>')">Edit</a>
                </td>
                <td><a href="#"
                    onclick="deletePerson(<?php echo $person['peopleID']; ?>, '<?php echo htmlspecialchars($person['peopleName']); ?>', '<?php echo htmlspecialchars($person['affiliationName'] ?? 'No Affiliation'); ?>')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
          <p>&nbsp;</p>
        </div> <!-- browse -->

        <div id="insert" style="display: none">
          <form method="POST" id="addPerson">
            <input type="hidden" name="action" value="add">
            <legend><strong>Add Person</strong></legend>
            <fieldset class="fields">
              <label>* Name </label> <input type="text" name="peopleName" style="margin-bottom: 10px" required>
              <br>
              <label>* Affiliation </label> <select name="affiliation" style="margin-bottom: 10px" required>
                <option value="">Select Affiliation...</option>
                <?php foreach ($affiliations as $affiliation): ?>
                  <option value="<?php echo $affiliation['affiliationID']; ?>">
                    <?php echo htmlspecialchars($affiliation['affiliationName']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <br>
              <button type="submit" id="addPerson">Add Person</button>
              <button type="button" onClick="browsePerson()">Cancel</button>
            </fieldset>
          </form>
        </div><!-- insert -->
        <p>&nbsp;</p>
        <div id="update" style="display: none">
          <form method="POST" id="updatePerson">
            <input type="hidden" name="action" value="update">
            <legend><strong>Update Person</strong></legend>
            <fieldset class="fields">
              <input type="hidden" name="peopleID" id="updatePersonID" style="margin-bottom: 10px">
              <label> ID#: </label> <span id="updatePersonIDDisplay"></span>
              <br>
              <label> Name </label> <input type="text" name="peopleName" id="updatePersonName"
                style="margin-bottom: 10px">
              <br>
              <label> Affiliation </label><select name="affiliation" id="updatePersonAffiliation">
                <?php foreach ($affiliations as $affiliation): ?>
                  <option value="<?php echo $affiliation['affiliationID']; ?>">
                    <?php echo htmlspecialchars($affiliation['affiliationName']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <br><br>
            </fieldset>
            <p id="center">
              <button type="submit" id="UpdateSavePerson">Save Person Updates</button>
              <button type="button" onClick="browsePerson()">Cancel</button>
            </p>
          </form>
        </div><!-- update -->
        <p>&nbsp;</p>
        <div id="delete" style="display: none">
          <form method="POST" id="deletePerson">
            <input type="hidden" name="action" value="delete">
            <legend><strong>Delete Person</strong></legend>
            <fieldset class="fields">
              <p>Are you sure you want to delete this Person from the database?</p>
              <input type="hidden" name="peopleID" id="deletePersonID">
              <label><strong>ID#: </strong></label> <span id="deletePersonIDDisplay"></span>
              <br>
              <label> <strong>Name: </strong> </label> <span id="deletePersonNameDisplay"></span>
              <br>
              <label> <strong>Affiliation: </strong> </label> <span id="deletePersonAffiliationDisplay"></span>
              <br><br>
            </fieldset>
            <p id="center">
              <button type="submit" id="deletePerson">Delete Person</button>
              <button type="button" onClick="browsePerson()">Cancel</button>
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