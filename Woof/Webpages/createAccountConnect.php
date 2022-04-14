<?php
session_set_cookie_params(0);
session_start();
$servername = "xx";
$username = "xx";
$password = "xx";
$database = "xx";



try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    } catch(PDOException $e) {    
    echo "Connection failed: " . $e->getMessage();
    }

$result = ($conn->query("SELECT checkEmail('$_POST[email]');"))->fetch();

if($result[0] == 0) {
            $qry= "Call createAccount('a', '$_POST[email]', '$_POST[password]');";
            $stmt = $conn->prepare($qry);
            $stmt->execute();
            header( "Location: findMatches.php" );
	}else{
    		header( "Location: CreateAccount.html" );
    		echo("Email in use");
    	}

?>
