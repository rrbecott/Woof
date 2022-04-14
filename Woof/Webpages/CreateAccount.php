<?php
session_set_cookie_params(0);
session_start();
$servername = "localhost";
$username = "-----------------";
$password = "-----------------";
$database = "-----------------";



try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    } catch(PDOException $e) {    
    echo "Connection failed: " . $e->getMessage();
    }
if (isset($_POST['Submit'])){
    
    $result = ($conn->query("SELECT checkEmail('$_POST[email]');"))->fetch();

    if($result[0] == 0) {
        if (strlen($_POST['email']) > 0 && strlen($_POST['password']) >= 8 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            
            $qry= "Call createAccount('a', '$_POST[email]', '$_POST[password]');";
            $stmt = $conn->prepare($qry);
            $stmt->execute();
            
            $_SESSION['SEmail'] = $_POST['email'];
            
	        $sql = "SELECT accountNum FROM Accounts WHERE email = '$_SESSION[SEmail]';";
            $result = ($conn->query($sql))->fetch();
            
            $_SESSION['accNum'] = $result[0];
            
            $sql = "SELECT name, breed, bio, age FROM Accounts WHERE accountNum = ".$_SESSION[accNum].";";
            $result = ($conn->query($sql))->fetch();
            
            $_SESSION['Sname'] = $result[0];
            $_SESSION['Sbreed'] = $result[1];
            $_SESSION['Sbio'] = $result[2];
            $_SESSION['Sage'] = $result[3];
            $_SESSION['loggedin']=true; // enables us to check if logged in in future
            
            header( "Location: ../myAccount.php?created=true" );
            
    } else { //password less than 8 chars or email not valid
        header( "Location: ../CreateAccount.php?err=true" );
        exit();
        
        }
	} else{ //email in use
    		header( "Location: ../CreateAccount.php?email=true" );
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
	            <li><a href="index.php">Login</a></li>
            </ul> 
        </div>
    </div>

    <div class = "container">
        <div class="main">
            <h2>Create Account</h2>
            <p>Enter an email and password to create an account</p>
		    <form method="post">
		        
		        <?php
		         $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		         if (strpos($url, "err=true")){
		              echo "<p><font color='red'>Enter a valid email and a password with at least 8 characters</font></p>";
		         } else if(strpos($url, "email=true")){
		             echo "<p><font color='red'>Email address already in use</font></p>";
		          }
		        ?>
		        
		        <br/>
			    <div class="email">
		            <label>Email:  </label>
		            <input type = "text" id = "email" name = "email" maxlength="40"/>
		        </div>
		        <br/> 
			    <div class="password">
        	        <label>Password: </label>
        	        <input type = "password" id = "password" name = "password" maxlength="255"/>
        		</div>
        		<br/>
			    <div>
			        <input class = "btn" type="submit" value="Submit" name = "Submit" id = "Submit">
			    </div>
		    </form>	
		</div>
	</div>
	<div class="footer">
        <p>Welcome to our page!</p>
    </div>
</body>
</html>
