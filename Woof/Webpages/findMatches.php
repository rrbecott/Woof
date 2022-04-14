<?php
//session_set_cookie_params(0);
session_start();
$servername = "localhost";
$username = "---------------";
$password = "---------------";
$database = "---------------";
try {   
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username,$password);
    // set the PDO error mode to exception
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     //echo "Connected successfully"; 
} catch(PDOException $e) {    
    echo "Connection failed: " . $e->getMessage();
}

if(!($_SESSION["loggedin"])){ // log user out if the session indicates they arent logged in
    header("location: index.php");
}
if (isset($_POST['right'])){
    if ($_SESSION['noProfiles'] != 1){
    $_POST['right'] = false;
    $qry= "CALL swipe('$_SESSION[accNum]', '$_SESSION[matchNum]', 1);";
    $stmt = $conn->prepare($qry);
    $stmt->execute();
    }
    header("location: findMatches.php");
}
else if (isset($_POST['left'])){
    if ($_SESSION['noProfiles'] != 1){
    $_POST['left'] = false;
    $qry= "CALL swipe('$_SESSION[accNum]', '$_SESSION[matchNum]', 0);";
    $stmt = $conn->prepare($qry);
    $stmt->execute();
    }
    header("location: findMatches.php");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
 
 <head>
    <meta charset="utf-8">
    <title>Woof!</title>
    <link rel="stylesheet" href="FindMatches.css" type="text/css">
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
              <li><a href="matches.php">My Matches</a></li>
	      <li><a href="logout.php">Logout</a></li>
        </ul> 
      </div>
    </div>
    <div class = "container">
        <h1>Find Matches</h1>
    <div class="main containerA">  
        <div class="swipe-left"> 
        <form method="post">
            <input type="hidden" name="left" value="left">
            <input type="image" src="Leftpaw.png">
        </form>
        </div>
        <div class"container" style="border:1px solid black;background-color: #F0FFFF;"> 
            <div class = "poloroid" style="border:1px solid black;background-color: #F0FFFF;">
              <div class = "pic">
                <?php
                $sql = "SELECT nextMatchF(".$_SESSION['accNum'].");";
                $matchNum = ($conn->query($sql))->fetch();
                if ($matchNum[0] != NULL){
                    $_SESSION['matchNum'] = $matchNum[0];
                    $sql = "SELECT picture FROM Accounts WHERE accountNum='$_SESSION[matchNum]';";
                    $result = ($conn->query($sql));
                
                    while ($row = $result->fetch()){
                        if(strlen($row[0]) > 0){
                            echo '<img src="data:image;base64,'.$row["picture"].'"  width="500" height=auto>'; 
                        } else {
                            echo '<img src="no-image-available.png"  width="400" ">';
                        }
                
                    }
                } else {
                    $_SESSION['noProfiles'] = 1;
                    echo "<center>No more profiles to display!</center>";
                }
                echo "</div><div class = 'cont'>";
                if ($matchNum[0] != NULL){
                    $sql = "SELECT age, name, breed, bio FROM Accounts WHERE accountNum = '$_SESSION[matchNum]';";
                    $result = $conn->query($sql);

                    while($row = $result->fetch()) {
                      echo "<br>Name: ". $row["name"]."<br>Age: ". $row["age"]."<br>Breed:   ".$row["breed"]."<br>Bio:  <textarea readonly name='bio' rows='10' cols='65' maxlength='500' style='font-family:Roboto, sans-serif;font-size:14px;border: none;outline: none;resize:none'>".$row["bio"]."</textarea><br><br><br><br>";
                    }
                }
                ?>
                </div>
            </div>
        </div>
        <div class="swipe-right"> 
        <form method="post">
            <input type="hidden" name="right" value="right">
            <input type="image" src="Rightpaw.png">
        </form>
        </div>
    </div>
    </div>

    </div>
    <div class="footer">
      <div class="container">
      </div>
    </div>
  </body>
</html>
