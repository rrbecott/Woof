//session_set_cookie_params(0);
session_start();
$servername = "--------------";
$username = "-------------";
$password = "-----------------";
$database = "----------------";



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
if (isset($_POST['update'])){
    
    if ($_FILES['picture']['size'] > 1000000){
        header( "Location: ../myAccount.php?size=true" );
        exit();
    }
    
    $img = addslashes($_FILES['picture']['tmp_name']);
    $img = file_get_contents($img);
    $img = base64_encode($img);
    if (strlen($img) > 0){
        $_SESSION['Spicture'] = $img;
    }
    
    $_SESSION['Sname'] = $_POST['name'];
    $_SESSION['Sbreed'] = $_POST['breed'];
    $_SESSION['Sbio'] = $_POST['bio'];
    $temp = $_POST['age'];
    
    if (strlen($temp) > 0){
        $_SESSION['Sage'] = $_POST['age'];
    } else {
       $_SESSION['Sage'] = 0;
    }
    
    $qry= "CALL modifyProfile('$_SESSION[accNum]', '$_POST[name]', '$_POST[breed]', '$_POST[bio]', '$_SESSION[Sage]', '$_SESSION[Spicture]');";
    $stmt = $conn->prepare($qry);
    $stmt->execute();
    header("location: myAccount.php");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Woof!</title>
    <link rel="stylesheet" href="myAccount.css" type="text/css">
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
              <li><a href="findMatches.php">Find Matches</a></li>
              <li><a href="matches.php">My Matches</a></li>
	      <li><a href="logout.php">Logout</a></li>
	      
        </ul> 
	
      </div>
    </div>

<div class="container">
    <h1>My Profile</h1>
    <div class = "main">
	  <form enctype="multipart/form-data" method = "post">
	    <?php
		    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		    if (strpos($url, "created=true")){
		        echo "<p><font color='green'>Account created successfully!</font></p>";
		        echo "<p><font color='blue'>Please continue to update your profile</font></p>";
		        echo "<br/>";
		    } else if (strpos($url, "size=true")) {
		        echo "<p><font color='red'>Upload unsuccessful, file size too large</font></p>";
		    }
		?>
	    <label>Name: </label>
	    <center>
		<input type = "text"
             id = "name" name = "name" value = "<?php echo"$_SESSION[Sname]"; ?>" size="49" style="height:20px;" maxlength="40"/>
        </center>
		<br/>
		<br/>
		
		<label>Breed: </label>
		<center>
		<input type = "text"
             id = "breed" name = "breed" value = "<?php echo"$_SESSION[Sbreed]"; ?>" size="49" style="height:20px;" maxlength="40"/>
        </center>
		<br/>
		<br/>
		<label>Tell us more about you and your doggo: </label>
		<center>
		<textarea name="bio" rows="12" cols="50" maxlength="500" style="font-family:Roboto, sans-serif;font-size:14px"><?php echo"$_SESSION[Sbio]"; ?></textarea>
		
        </center>
		<br/>
		<br/>
        <label>Age (in human years): </label>
        <center>
		<input type = "number" 
		    id = "age" name = "age" value = "<?php echo"$_SESSION[Sage]"; ?>" min="0" max="99" size="49" style="height:20px;" maxlength="2"/>
		</center>
		<br/>
		<br/>
        <label>Picture: </label>
        <br/>
        <center>
        <p><input type = "file" accept="image/*" name="picture" value = "<?php echo"$_SESSION[Spicture]"; ?>" /></p>
        </center>
       <center>
		    <?php
		    $sql = "SELECT picture FROM Accounts WHERE accountNum = ".$_SESSION[accNum].";";
            $result = ($conn->query($sql));
            while ($row = $result->fetch()){
                if(strlen($_SESSION['Spicture']) > 0){
                    echo "<center>";
                    echo '<img src="data:image;base64,'.$row["picture"].'"  width="400" height="400"">'; 
                    echo "</center>";
                } else {
                    echo "<p>*no image uploaded yet*</p>";
                }
                
            }
		    ?>
		</center>
		<br/>
		<br/>
		<center>
		    <input class = "btn" type="submit" name = "update" value="Update Profile">
		</center>
	  </form>
	</div>
</div>
  
<div class="footer">
    <div class="container">
    </div>
</div>
</body>
</html>
