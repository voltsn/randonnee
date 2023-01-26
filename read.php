<?php 
  require "./config.php";

  $data;
  try {
    // Connect to the database
    $db_conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpassword);


    // Query database
    $data = $db_conn->query("SELECT * FROM hiking");
  } catch (PDOException $e) {
    $db_conn = NULL;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Randonnées</title>
    <link rel="stylesheet" href="css/basics.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <h1>Liste des randonnées</h1>
    <table>
      <!-- Afficher la liste des randonnées -->
      <?php 
        if ($db_conn == NULL){
          echo "<p>Something went wrong please try again later...</p>";
        }else{
          echo "
                  <theader> 
                   <th> Nom </th>
                   <th> Difficulté </th>
                   <th> Distance </th>
                   <th> Duree </th>
                   <th> Déniv.+ </th>
                  </theader>
                  <tbody>      
          ";
          foreach ($data as $row) {
            $distance = number_format($row["distance"] / 1000, 2);
            $duration = date('H:i',strtotime($row["duration"])); 
            echo "
                  <tr>
                    <td>$row[name]</td>
                    <td>$row[difficulty]</td> 
                    <td>$distance km</td>
                    <td>$duration</td>
                    <td>$row[height_difference] m</td>
                  </tr>
            ";
          }
          echo "</tbody>";
        }

      ?>
    </table>
  </body>
</html>
