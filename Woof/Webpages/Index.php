<?php
//session_set_cookie_params(0);
session_start();

$servername = "------------";
$username = "--------------";
$password = "-------------";
$database = "-------------";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    if($_SESSION["loggedin"]){ 
        header("location: findMatches.php");
    }
} catch(PDOException $e) {    
    echo "Connection failed: " . $e->getMessage();
}
if (isset($_POST['Login'])){
    $result = ($conn->query("SELECT login('$_POST[email]', '$_POST[password]');"))->fetch();
	
    if($result[0] == 1) {
        $_SESSION['loggedin'] = true; // enables php to check if user is logged in
        $_SESSION['SEmail'] = $_POST['email'];
        $sql = "SELECT accountNum FROM Accounts WHERE email = '$_SESSION[SEmail]';";
        $result = ($conn->query($sql))->fetch();
        $_SESSION['accNum'] = $result[0];
        $sql = "SELECT name, breed, bio, age, picture FROM Accounts WHERE accountNum = ".$_SESSION[accNum].";";
        $result = ($conn->query($sql))->fetch();
        $_SESSION['Sname'] = $result[0];
        $_SESSION['Sbreed'] = $result[1];
        $_SESSION['Sbio'] = $result[2];
        $_SESSION['Sage'] = $result[3];
        $_SESSION['Spicture'] = $result[4];
	    header( "Location: findMatches.php" );
    }else{
	    header( "Location: ../index.php?err" );
    	exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Woof!</title>
    <link rel="stylesheet" href="index.css" type="text/css"> 
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
	      <li><a href="CreateAccount.php">Create Account</a></li>
        </ul> 
      </div>
    </div>

    <div class="container">
      <div class="main">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <br/>
        <br/>
        <form method="post">
        <?php
		    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		    if (strpos($url, "err")){
		        echo "<p><font color='red'>Incorrect email or password</font></p>";
		    } 
		?>
          <div class = "email">
            <label>Email: </label>
              <input type="text" name="email">
          </div>    
          <br/>
          <div class = "password">
            <label>Password: </label>
              <input type="password" name="password">
          </div>
          <br/>
          <div>
            <input type="submit" class="btn" value="Login" name="Login">
          </div>
        </form>
      </div>
    </div>
    <div class="footer">
      <div class="container">
        <p>Welcome to our page!</p>
      </div>
    </div>
  </body>
</html>
