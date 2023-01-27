<?php 
    require "./helpers/check_user.php";
    session_start();

    $is_loggedout = logout();
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
		if ($is_loggedout){
			echo "
				<h1> Vous etes deconnceter </h1>
				<ul>
				 <li> <a href='../read.php'>List de randonnee</a></li>
				 <li> <a href='login.php'>Se Connecter</a></li>
				</ul>
			";
		} else {
            echo "<h1> Something went wrong try again later</h1>";
        }
	?>
</body>
</html>