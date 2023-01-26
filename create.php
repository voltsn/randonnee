<?php 
	require "./config.php";
	
	if (isset($_POST["button"])){
		$name = $_POST["name"];
		$diff = $_POST["difficulty"];
		$dist = $_POST["distance"];
		$dur = $_POST["duration"];
		$height_diff = $_POST["height_difference"];

		// Convert to meters
		$dist = intval($dist) * 1000;
			
		$query_status;
		try{

			// Connect to the database
			$db_conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpassword);
			
			// Prepare query
			$insert_query = "INSERT INTO hiking (name, difficulty, distance, duration, height_difference) VALUES(:name, :diff, :dist, FROM_UNIXTIME(:dur, '%H:%i:%s'), :height_diff)";
			$prepared_query = $db_conn->prepare($insert_query);

			// Execute query
			$query_status = $prepared_query->execute([
				"name" => $name,
				"diff" => $diff,
				"dist" => $dist,
				"dur" => strtotime($dur),
				"height_diff" => $height_diff
			]);

		} catch (PDOException $e) {
			$query_status = FALSE;
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
			<label for="distance">Distance</label>
			<input type="text" name="distance" value="">
		</div>
		<div>
			<label for="duration">Durée</label>
			<input type="time" name="duration" value="">
		</div>
		<div>
			<label for="height_difference">Dénivelé</label>
			<input type="text" name="height_difference" value="">
		</div>
		<button type="submit" name="button">Envoyer</button>
	</form>
</body>
</html>
