<?php
//session_set_cookie_params(0);
session_start();
session_start();
$servername = "--------------";
$username = "---------------";
$password = "-----------";
$database = "-----------";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 

} catch(PDOException $e) {    
        echo "Connection failed: " . $e->getMessage();
}
if(!($_SESSION["loggedin"])){ // log user out if the session indicates they arent logged in
    header("location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Woof!</title>
    <link rel="stylesheet" href="Matches.css" type="text/css">
  </head>
  <body>
    <div class="header">
      <div class="container">
        <h1>Woof!</h1>
        <div class="text-background">
          <p>Find your perfect doggie playdate</p>
        </div>
      </div>
    </div>

    <div class="nav">
      <div class="container">
        <ul>
	      <li><a href="myAccount.php">My Profile</a></li>
              <li><a href="findMatches.php">Find Matches</a></li>
	      <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>

    <div class="container">
      <h1>My Matches</h1>
      <br/>
      <br/>
      <br/>
        <div class="main" style="min-height:350px;">
         <form method="post">
	       <table>
	         <tr>
		       <th>Picture</th>
		       <th>Name</th>
		       <th>Email</th>
		       <th>Breed</th>
	         </tr>
	       
            <?php
            //var_dump($_SESSION);
            $sql = "SELECT acc2 FROM Matches WHERE acc1 = '$_SESSION[accNum]';";
            $matchNum = ($conn->query($sql))->fetchALL();
            foreach ($matchNum as $value){
                
                $sql = "SELECT picture, name, email, breed FROM Accounts WHERE accountNum = '$value[0]';";
                $result = ($conn->query($sql));
                
                while ($row = $result->fetch()){
                    if(strlen($row[0]) > 0){ // if there is a profile image
                        echo '<tr><td><img src="data:image;base64,'.$row[0].'"  width="500" height=auto></td>'; 
                    } else { // if there is no image
                        echo '<<tr><td>img src="no-image-available.png"  width="400" "></td>';
                    }
                    echo "<td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
                }
            }
            
            $sql = "SELECT acc1 FROM Matches WHERE acc2 = '$_SESSION[accNum]';";
            $matchNum = ($conn->query($sql))->fetchALL();
            foreach ($matchNum as $value){
                
                $sql = "SELECT picture, name, email, breed FROM Accounts WHERE accountNum = '$value[0]';";
                $result = ($conn->query($sql));
                
                while ($row = $result->fetch()){
                    if(strlen($row[0]) > 0){ // if there is a profile image
                        echo '<tr><td><img src="data:image;base64,'.$row[0].'"  width="500" height=auto></td>'; 
                    } else { // if there is no image
                        echo '<<tr><td>img src="no-image-available.png"  width="400" "></td>';
                    }
                    echo "<td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
                }
            }
            
            // does there have to be a for loop here for each matchNum?
            //$_SESSION['matchNum'] = $matchNum[0];
            // gets info in form of a table   
            //$sql = "SELECT picture, name, breed, email FROM Matches WHERE accountNum =                    '$_SESSION[matchNum]';";
            //$result = $conn->query($sql);
            
            
	        //while ($row = $result->fetch()){
	            // put image in first collumn
                //if(strlen($row[0]) > 0){ // if there is a profile image
                    //echo '<tr><td><img src="data:image;base64,'.$row[0].'"  width="500" height=auto></td>'; 
                //} else { // if there is no image
                      //echo '<<tr><td>img src="no-image-available.png"  width="400" "></td>';
                //}
                // put name, breed, email in next collumns
	            //echo "<td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
	        //} 
            ?>
            
	        </table>
	        <br/>
	        <br/>
	      </form>
        </div>
      </div>


    <div class = "footer">
    </div>
  </body>
</html>
