<?php
/**** Supprimer une randonnée ****/
  require "./config.php";
  require "./auth/helpers/check_user.php";
  session_start();

  $is_loggedin = check_user();
  if ($is_loggedin){
    if (isset($_GET['id'])){
      $db_conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
      $db_conn->query("DELETE FROM hiking WHERE id=$_GET[id]");
    } 
    
  }
  
  $redirect = "http://".$_SERVER["HTTP_HOST"]."/sql/randonnee/read.php";    
  header("Location:$redirect");
?>
