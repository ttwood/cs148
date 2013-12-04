<!DOCTYPE html>
<html>
    <head>
        <title>Admin Portal</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet"
	  href="style.css"
          media="screen">
    </head>
  
    <?php 
    include ("http://www.uvm.edu/~ttwood/cs148/assignment7.1/nav.php");
    ?>
    
    <p></p>
   
    <header>Add an entry</header>
    <form action="add.php" method="post">
      Add the following email address:
      <input name="email" type="email" />
      <p></p>
      Associated with the first name:
      <input name="name" type="text" /> 
      <p></p>
      And the phone number:
      <input name="number" type="text" />
      <input name="Submit" type="submit" value="Add User" />
    </form>
    
    <br>
    <br>
    
    <header>Delete an entry</header>
    <form action="delete2.php" method="post">
      User Email to be deleted:
     <input name="email" type="email" />
     <input name="Submit" type="submit" value="Delete Record" />
    </form>
    
    <form action="delete2.php" method="post">
      Beer Rating to be deleted:
     <input name="rating" type="text" />
     <input name="Submit" type="submit" value="Delete Record" />
    </form>
    
    <br>
    <br>
    
    <header>Update an entry</header>
    <form action="edit.php" method="post">
      1. Select the email address that requires updating:
<?php

    include("connect.php");

    //make a query to get all the poets
    $sql  = 'SELECT pkBeerRating, fkCustomerEmail ';
    $sql .= 'FROM tblRegister ';
    $sql .= 'ORDER BY fkCustomerEmail';

    if ($debug) print "<p>sql ". $sql;

    $stmt = $db->prepare($sql);
            
    $stmt->execute(); 

    $users = $stmt->fetchAll(); 
    if($debug){ print "<pre>"; print_r($users); print "</pre>";}
    
    // build list box
    print '<fieldset class="listbox"><select name="rating" size="1" tabindex="10">';

    foreach ($users as $user) {
    print '<option value="' . $user['pkBeerRating'] . '">' . $user['fkCustomerEmail'] . "</option>\n";
    }
    
    print "</select>\n";
    
    print "</fieldset>\n";
    
?>
     <p>
      2. Enter the new email address for this rating:
      <input name="email" type="email" />
      <input name="Submit" type="submit" value="Update Record" />
     </p>
     <br>
     <h1><a href="https://www.uvm.edu/~ttwood/cs148/assignment7.1/admin/results.php">Database Admin View</a></h1>
    </form>
</html>
