<!DOCTYPE html>
<html lang="en">
<head>
<title>Update Database</title>
<meta charset="utf-8">
        <link rel="stylesheet"
	  href="style.css"
          media="screen">
</head>
<body>
<?php

include ("connect.php");

$debug = true;
if (isset($_GET["debug"])) {
    $debug = true;
}

$email = $_POST["email"];
$rating = $_POST["rating"];

$sql = "UPDATE tblRegister ";
$sql .= "SET fkCustomerEmail ='$email' ";
$sql .= "WHERE pkBeerRating='$rating'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

$sql = "UPDATE tblCustomerBeer ";
$sql .= "SET fkCustomerEmail ='$email' ";
$sql .= "WHERE fkBeerRating='$rating'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

$sql ="UPDATE tblCustomer ";
$sql .= "SET pkCustomerEmail ='$email' ";
$sql .= "WHERE fkBeerRating ='$rating'";

$stmt = $db->prepare($sql); 
if ($debug) print "<p>sql ". $sql;
        
$stmt->execute();

if ($debug){
   print "<p>Record has been updated";
}

?>                   
</body>    
</html>