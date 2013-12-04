<!DOCTYPE html>
<html lang="en">
<head>
<title>Add email</title>
        <link rel="stylesheet"
	  href="style.css"
          media="screen">
 <meta charset="utf-8">
</head>
<?php

$debug = true; 
if ($debug) print "<p>DEBUG MODE IS ON</p>";

if ($debug) 
    print "<p>From: " . $fromPage . " should match "; 
    print "<p>Your: " . $yourURL;

include("connect.php");

//define my variables//

$email = $_POST["email"];
$firstname = $_POST["name"];
$phonenumber = $_POST["number"];

//moving on to my SQL commands://

$sql = "INSERT INTO tblRegister ";
$sql .= "SET fkCustomerEmail='$email'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

$rating = $db->lastInsertId();
if ($debug) print "<p>pk= " . $rating;

$primaryKey = $rating;

$sql = "INSERT INTO tblBeer ";
$sql .= "SET fkBeerRating='$rating'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

$sql = "INSERT INTO tblCustomer ";
$sql .= "VALUES (\"$email\", \"$rating\", \"$firstname\", \"$phonenumber\")";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

$sql = "INSERT INTO tblCustomerBeer ";
$sql .= "VALUES (\"$rating\", \"$email\")";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

if ($debug){
   print "<p>Record has been updated";
}

?>
</html>
