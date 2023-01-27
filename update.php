<?php 
  require "./config.php";
  require "./auth/helpers/check_user.php";
  
  session_start();
  
  // Check if the user is loged in
  $is_loggedin = check_user();
  if ($is_loggedin){ 
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
      $form_errors = [];
      
      // Ensure that distance, height_difference and duration are numbers 
      $dist = filter_var($_POST["distance"], FILTER_SANITIZE_NUMBER_INT);
      if (!$dist){
        $form_errors["distance"] = "Distance must be a number";
      }
  
      $dur = filter_var($_POST["duration"], FILTER_SANITIZE_NUMBER_INT);
      if(!$dur){
        $form_errors["duration"] = "Duration must be a number";
      }
  
      $height_difference = filter_var($_POST["height_difference"], FILTER_SANITIZE_NUMBER_INT);
      if(!$height_difference){
        $form_errors["height_diff"] = "Height difference must be a number";
      }

      if (count($form_errors) == 0){
  
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
     }
  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mettre une randonnée a jour</title>
	<link rel="stylesheet" href="css/basics.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<a href="./read.php">Liste des données</a>
	<h1>Mettre à jour</h1>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
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
