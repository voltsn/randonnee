<?php 
  require "./config.php";
  session_start();
  
  $name = $diff = $dist = $dur = $height_diff = $available = NULL;
  if (isset($_GET["id"])){
    // Get id from the URL 
    $id = intval($_GET["id"]);

    // Store id in session
    $_SESSION['id'] = $id;

    $error = FALSE;
    try{
      
      // Connect to the database 
      $db_conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);

      // Fetch the data using its unique id
      $query = $db_conn->query("SELECT * FROM hiking WHERE id=$id");
      $data = $query->fetch();

      $name = $data['name'];
      $diff = strtolower($data['difficulty']);
      $dist = $data['distance'];
      $dur = $data['duration'];
      $height_diff = $data['height_difference'];
      $available = (int) $data['available'];

    } catch(PDOExecption $e){
      $error = TRUE;
    }
  }

  // Update hike in the database
  if (isset($_POST['button']) && isset($_SESSION['id'])){
    $db_conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);

    $name = $_POST['name'];
    $diff = $_POST['difficulty'];
    $dist = $_POST['distance'];
    $dur = $_POST['duration'];
    $height_diff = $_POST['height_difference'];
    $available = ($_POST['available'] == "yes") ? 1 : 0;

    $update_query = "UPDATE hiking 
                     SET name = :name, difficulty = :diff, distance = :dist, duration = :dur, height_difference = :height_diff, available = :available 
                     WHERE id = :id";

    $query = $db_conn->prepare($update_query);

    // Execute query
    $result = $query->execute([
     'name' => $name,
     'diff' => $diff,
     'dist' => $dist,
     'dur' => $dur,
     'height_diff' => $height_diff, 
     'available' => $available,
     'id' => $_SESSION['id']
    ]);
  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter une randonnée</title>
	<link rel="stylesheet" href="css/basics.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<a href="./read.php">Liste des données</a>
	<h1>Ajouter</h1>
	<form action="." method="post">
    <div>
      <label for="name">Name</label>
      <input type="text" name="name" value="<?php echo $name ? $name : '';?> ">
    </div>

    <div>
      <label for="difficulty">Difficulté</label>
      <select name="difficulty">
      <option value="très facile" <?php echo $diff == 'très facile' ? 'selected' : ''  ?>>Très facile</option>
        <option value="facile" <?php echo $diff == 'facile' ? 'selected' : ''  ?>>Facile</option>
        <option value="moyen" <?php echo $diff == 'moyen' ? 'selected' : ''  ?>>Moyen</option>
        <option value="difficile" <?php echo $diff == 'difficile' ? 'selected' : ''  ?>>Difficile</option>
        <option value="très difficile" <?php echo $diff == 'très difficile' ? 'selected' : ''  ?>>Très difficile</option>
      </select>
    </div>
    
    <div>
      <label for="distance">Distance</label>
      <input type="text" name="distance" value="<?php echo $dist ? $dist : ''; ?>">
    </div>
    <div>
      <label for="duration">Durée</label>
      <input type="duration" name="duration" value="<?php echo $dur ? $dur : ''; ?>">
    </div>
    <div>
      <label for="height_difference">Dénivelé</label>
      <input type="text" name="height_difference" value="<?php echo $height_diff ? $height_diff : ''; ?>">
    </div>
    <div>
      Disponible:
      <label for="available-yes">Oui</label>
      <input type="radio" name="available" id="available-yes" value="yes" <?php echo ($available == 1) ? "checked" : ""?>>
      
      <label for="available-no">Non</label>
      <input type="radio" name="available" id="available-no" value="no" <?php echo ($available == 0) ? "checked" : ""?>>
    </div>
		<button type="submit" name="button">Envoyer</button>
	</form>
</body>
</html>
