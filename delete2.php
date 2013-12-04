<!DOCTYPE html>
<html lang="en">
<head>
<title>Delete email</title>
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

$email = $_POST["email"];
$rating = $_POST["rating"];

$sql = "DELETE ";
$sql .= "FROM tblRegister ";
$sql .= "WHERE fkCustomerEmail='$email' ";
$sql .= "OR pkBeerRating='$rating'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

$sql = "DELETE ";
$sql .= "FROM tblBeer ";
$sql .= "WHERE fkBeerRating='$rating'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

$sql = "DELETE ";
$sql .= "FROM tblCustomer ";
$sql .= "WHERE pkCustomerEmail='$email'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql; 
        
$stmt->execute();

$sql = "DELETE ";
$sql .= "FROM tblCustomerBeer ";
$sql .= "WHERE fkCustomerEmail='$email' ";
$sql .= "OR fkBeerRating='$rating'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql; 
        
$stmt->execute();

if ($debug){
   print "<p>Record has been updated";
}

?>
</html>