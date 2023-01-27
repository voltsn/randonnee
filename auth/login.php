<?php
	require "../config.php";
	session_start();

	if (isset($_POST["submit"])){
		// Connect to the database
		$db_conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpassword);

		// Get user credentials from the database
		$query = "SELECT username, password FROM users WHERE username = :username AND password = :password";

		$prepared_query = $db_conn->prepare($query);

		$prepared_query->execute([
			"username" => $_POST["username"],
			"password" => sha1($_POST["password"])
		]);

		$db_data = $prepared_query->fetch();

		// Store user credentials in the current session
		if ($db_data) {
			$user = [
				'username' => $_POST["username"],
				'password' => $_POST["password"]
			];
	
			// Store user cridentials in the current session
			$_SESSION["user"] = $user;
		}
            
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Se Connecter</title>
	<link rel="stylesheet" href="css/basics.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<?php 
		if (isset($_SESSION['user'])){
			echo "
				<h1> Vous etes connceter </h1>
				<ul>
				 <li> <a href='../read.php'>List de randonnee</a></li>
				 <li> <a href='../create.php'>Ajouter une randonnee</a></li>
				 <li> <a href='logout.php'>Se deconnecter</a></li>
				</ul>
			";
		} else{
			$action = $_SERVER['PHP_SELF'];
            echo "
                <h1>Se Connecter</h1>
                <form action='$action' method='post'>
                    <div>
                        <label for='username'>username</label>
                        <input type='text' name='username' required>
                    </div>
                    <div>
                        <label for='password'>Mot de passe</label>
                        <input type='password' name='password' required>
                    </div>
                    <button type='submit' name='submit'>Se Connecter</button>
                </form>
            ";
		}
	?>
</body>
</html>