<?php 
	require "./config.php";
  require "./auth/helpers/check_user.php";

  session_start();

  // Check if the user is loged in
  $is_loggedin = check_user();
  if ($is_loggedin){

    // Process form if it was submitted
    if (isset($_POST["button"])){
      $form_errors = [];
      $name = $_POST["name"];
      $diff = $_POST["difficulty"];
      $available = $_POST["available"] == "yes" ? 1 : 0;
  
      
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
  
      $query_status;
      if (count($form_errors) == 0){
          try{
            // Connect to the database
            $db_conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpassword);
            
            // Prepare query
            $insert_query = "INSERT INTO hiking (name, difficulty, distance, duration, height_difference, available) VALUES(:name, :diff, :dist, FROM_UNIXTIME(:dur, '%H:%i:%s'), :height_diff, :available)";
            $prepared_query = $db_conn->prepare($insert_query);
  
            // Execute query
            $query_status = $prepared_query->execute([
              "name" => $name,
              "diff" => $diff,
              "dist" => $dist * 1000,
              "dur" => strtotime($dur),
              "height_diff" => $height_difference,
              "available" => $available
            ]);
  
          } catch (PDOException $e) {
            $query_status = FALSE;
          }
      }
    }

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
	<?php 
		if (isset($query_status)){
			if ($query_status){
				echo "<p style='color: green;'>La randonnée a été ajoutée avec succès. </p>"; 
			} else {
				echo "<p style='color: red;'>L'ajout de la randonnée a echoue..";
			}
		}

    if (!$is_loggedin) {
      echo "<p>Vous devez <a href='./auth/login.php'>vous connecter</a> </p>";
    }
		?>
	<form action="" method="post">
		<div>
			<label for="name">Name</label>
			<input type="text" name="name" value="">
		</div>

		<div>
			<label for="difficulty">Difficulté</label>
			<select name="difficulty">
				<option value="très facile">Très facile</option>
				<option value="facile">Facile</option>
				<option value="moyen">Moyen</option>
				<option value="difficile">Difficile</option>
				<option value="très difficile">Très difficile</option>
			</select>
		</div>

		<div>
      <?php echo isset($form_errors["distance"]) ? "<p> $form_errors[distance] </p>" : "";?>
			<label for="distance">Distance</label>
      <input type="text" name="distance" value="" 
       <?php 
          if(isset($form_errors["distance"])){
            echo "style=outline:red;";
          }
       ?>
      >
		</div>
		<div>
			<label for="duration">Durée</label>
			<input type="time" name="duration" value="">
		</div>
		<div>
			<label for="height_difference">Dénivelé</label>
			<input type="text" name="height_difference" value="">
		</div>
    <div>
      Disponible:
      <label for="available-yes">Oui</label>
      <input type="radio" name="available" id="available-yes" value="yes">
      
      <label for="available-no">Non</label>
      <input type="radio" name="available" id="available-no" value="no">
    </div>
		<button type="submit" name="button">Envoyer</button>
	</form>
</body>
</html>
