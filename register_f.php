<!DOCTYPE html>
<html lang="en">
<head>
<title>Up in Arms Brewery</title>
<meta charset="utf-8">
<link rel="stylesheet"
	  href="style.css"
      media="screen">
</head>
<body>

<?php 
/* 
 * This form is designed to evaluate potential market preferences for the brewery
 * that Jim and I wish to establish.   
 * 
 * Written By: Taylor Wood ttwood@uvm.edu 
 */ 

//----------------------------------------------------------------------------- 
//  
// Initialize variables 
//   

$debug = false; 
if ($debug) print "<p>DEBUG MODE IS ON</p>"; 

$baseURL = "http://www.uvm.edu/~ttwood/"; 
$folderPath = "cs148/assignment7.1/"; 
// full URL of this form 
$yourURL = $baseURL . $folderPath . "register_f.php"; 

require_once("connect.php"); 

//############################################################################# 
// All variables set to default below: 

$email = ""; 
$firstname = "";
$phonenumber = "";
$ipa = false;
$stoutporter = false;
$brownamber = false;
$pale = false; 
$wheat = false;
$lager = false;
$fruit = false;
$sour = false;
$beerrating = 0;
$consumer = "fan";
$interest = 1;

//############################################################################# 
//  
// flags for errors 

$emailERROR = false;
$firstnameERROR = false;
$phonenumberERROR = false;
$consumerERROR = false;
$interestERROR = false;

//############################################################################# 
//   
$mailed = false; 
$messageA = ""; 
$messageB = ""; 
$messageC = ""; 


//----------------------------------------------------------------------------- 
//  
// Checking to see if the form's been submitted. if not we just skip this whole  
// section and display the form 
//  
//############################################################################# 
// minor security check 

if (isset($_POST["btnSubmit"])) { 
    $fromPage = getenv("http_referer"); 

    if ($debug) 
        print "<p>From: " . $fromPage . " should match "; 
        print "<p>Your: " . $yourURL; 

    if ($fromPage != $yourURL) { 
        die("<p>Sorry you cannot access this page. Security breach detected and reported.</p>"); 
    } 


//############################################################################# 
// replace any html or javascript code with html entities 

    $email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8"); 
    $firstname = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8"); 
    $phonenumber = htmlentities($_POST["txtPhoneNumber"], ENT_QUOTES, "UTF-8"); 
    $consumer = htmlentities($_POST["consumer"],ENT_QUOTES,"UTF-8");
    $interest = htmlentities($_POST["interest"],ENT_QUOTES,"UTF-8");
        
    //These are the fields I want for my checkbox options: "What style of beer do you most enjoy?"
    if(isset($_POST["chkIPA"])) {
        $ipa  = true;
    }else{
        $ipa  = false;
    }
    
    if(isset($_POST["chkStoutPorter"])) {
        $stoutporter  = true;
    }else{
        $stoutporter  = false;
    }
    
    if(isset($_POST["chkBrownAmber"])) {
        $brownamber  = true;
    }else{
        $brownamber  = false;
    }
    
    if(isset($_POST["chkPale"])) {
        $pale  = true;
    }else{
        $pale  = false;
    }
    
    if(isset($_POST["chkWheat"])) {
        $wheat  = true;
    }else{
        $wheat  = false;
    }
    
    if(isset($_POST["chkLager"])) {
        $lager  = true;
    }else{
        $lager  = false;
    }
        
    if(isset($_POST["chkFruit"])) {
        $fruit  = true;
    }else{
        $fruit  = false;
    }
    
    if(isset($_POST["chkSour"])) {
        $sour  = true;
    }else{
        $sour  = false;
    }
    
//############################################################################# 
//  
// Check for mistakes using validation functions 
// 
// create array to hold mistakes 
//  

    include ("validation_functions.php"); 

    $errorMsg = array(); 


//############################################################################ 
//  
// Check each of the fields for errors then adding any mistakes to the array. 
// 
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^       Check email address 
    if (empty($email)) { 
        $errorMsg[] = "Please enter a valid Email Address"; 
        $emailERROR = true; 
    } else { 
        $valid = verifyEmail($email); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the email address you entered is not valid."; 
            $emailERROR = true; 
        } 
    } 
    
    if (empty($firstname)) { 
        $errorMsg[] = "Please enter your first name"; 
        $firstnameERROR = true; 
    } else { 
        $valid = verifyText($firstname); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the first name you entered is not valid."; 
            $firstnameERROR = true; 
        } 
    }

    if (empty($phonenumber)) { 
        $errorMsg[] = "Please enter a valid phone number"; 
        $phonenumberERROR = true; 
    } else { 
        $valid = verifyPhone($phonenumber); /* test for non-valid  data */ 
        if (!$valid) { 
            $errorMsg[] = "I'm sorry, the phone number you entered is not valid."; 
            $phonenumberERROR = true; 
        } 
    }

//############################################################################ 
//  
// Processing the Data of the form 
// 

    if (!$errorMsg) { 
        if ($debug) print "<p>Form is valid</p>"; 

//############################################################################ 
// 
// the form is valid so now save the information. SQL statements: 
//     
        
        $dataEntered = false; 
         
        try { 
            $db->beginTransaction(); 
             } catch (PDOExecption $e) { 
            $db->rollback(); 
            if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
            $errorMsg[] = "There was a problem with accepeting your data please contact us immediately"; 
        }
// Register table
     
            $sql = 'INSERT INTO tblRegister SET fkCustomerEmail="' . $email . '"';

            $stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute();  
            
//you have inserted the record in the table and now we need to get
// the auto increment primary key for the above record.

            $beerrating = $db->lastInsertId();
            if ($debug) print "<p>pk= " . $beerrating;
                        
            $primaryKey = $beerrating; 
// Beer table
        
            $set_val = "";
            if ($ipa) { $set_val .= ",IPA"; }
            if ($stoutporter) { $set_val .= ",Stout/Porter"; }
            if ($brownamber) { $set_val .= ",Brown/Amber"; }
            if ($pale) { $set_val .= ",Pale"; }
            if ($wheat) { $set_val .= ",Wheat"; }
            if ($lager) { $set_val .= ",Lager"; }
            if ($fruit) { $set_val .= ",Fruit"; }
            if ($sour) { $set_val .= ",Sour"; }
            if (strlen($set_val) > 0)
            {
                $set_val = substr($set_val, 1);
            }
           
            $sql = "INSERT INTO `tblBeer` VALUES (\"$beerrating\", \"$set_val\", \"$consumer\", \"$interest\")";
            
            $stmt = $db->prepare($sql);
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute();
            
            //$beerrating = $db->lastInsertId();
            //if ($debug) print "<p>pk= " . $beerrating;
            
// Customer table

            $sql = "INSERT INTO `tblCustomer` VALUES (\"$email\", \"$beerrating\", \"$firstname\", \"$phonenumber\")";
            
            $stmt = $db->prepare($sql);
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute();
            
// CustomerBeer table
            
            $sql = "INSERT INTO `tblCustomerBeer` VALUES (\"$beerrating\", \"$email\")";
            
            $stmt = $db->prepare($sql);
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute();
            
            // all sql statements are done so lets commit to our changes 
            $dataEntered = $db->commit(); 
            if ($debug) print "<p>transaction complete "; 
       


        // If the transaction was successful, give success message 
        if ($dataEntered) { 
            if ($debug) print "<p>data entered now prepare keys "; 
            //################################################################# 
            // create a key value for confirmation 

            $sql = "SELECT fldDateJoined FROM tblRegister WHERE pkRegisterId=" . $primaryKey; 
            $stmt = $db->prepare($sql); 
            $stmt->execute(); 

            $result = $stmt->fetch(PDO::FETCH_ASSOC); 
             
            $dateSubmitted = $result["fldDateJoined"]; 

            $key1 = sha1($dateSubmitted); 
            $key2 = $primaryKey; 

            if($debug)print "<p>key 1: " . $key1; 
            if($debug) print "<p>key 2: " . $key2; 


            //################################################################# 
            // 
            //Put forms information into a variable to print on the screen 
            //  

            $messageA = '<h2>Thank you for filling out our survey!.</h2>'; 

            $messageB = "<p>Click this link to confirm if you are a fan: "; 
            $messageB .= '<a href="' . $baseURL . $folderPath  . 'confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . '">Confirm Registration</a></p>'; 
            $messageB .= "<p>or copy and paste this url into a web browser: "; 
            $messageB .= $baseURL . $folderPath  . 'confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . "</p>"; 

            $messageC .= "<p><b>Email Address:</b><i>   " . $email . "</i></p>";
            $messageC .= "<p><b>First Name:</b><i>   " . $firstname . "</i></p>";
            $messageC .= "<p><b>Phone Number:</b><i>   " . $phonenumber . "</i></p>";

            //############################################################## 
            // 
            // email the form's information 
            // 
             
            $subject = "Up in Arms Brewery- Thanks for contacting us!"; 
            include_once('mailMessage.php'); 
            $mailed = sendMail($email, $subject, $messageA . $messageB . $messageC . $messageD); 
        } //data entered    
    } // no errors  
}// ends if form was submitted.  

$ext = pathinfo(basename($_SERVER['PHP_SELF'])); 
$file_name = basename($_SERVER['PHP_SELF'], '.' . $ext['extension']); 
    
include ("nav.php");

?> 
<section id="main"> 
    <header>Registration Form</header> 
    <? 
//############################################################################ 
// 
//  In this block  display the information that was submitted and do not  
//  display the form. 
// 
        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { 
            print "<h2>Your Request has "; 

            if (!$mailed) { 
                echo "not "; 
            } 

            echo "been processed</h2>"; 

            print "<p>A copy of this message has "; 
            if (!$mailed) { 
                echo "not "; 
            } 
            print "been sent to: " . $email . "</p>"; 

            echo $messageA . $messageC; 
        } else { 


//############################################################################# 
// 
// Here we display any errors that were on the form 
// 

            print '<div id="errors">'; 

            if ($errorMsg) { 
                echo "<ol>\n"; 
                foreach ($errorMsg as $err) { 
                    echo "<li>" . $err . "</li>\n"; 
                } 
                echo "</ol>\n"; 
            } 

            print '</div>'; 
            ?> 
            <!--   Take out enctype line    --> 
            <form action="<? print $_SERVER['PHP_SELF']; ?>"  
                  method="post" 
                  id="frmRegister"> 
                <fieldset class="contact"> 
                    <legend>Contact Information</legend> 

                    <label class="required" for="txtEmail">Email </label> 

                    <input type="email" id ="txtEmail" name="txtEmail" class="element text medium <?php if ($emailERROR) echo ' mistake'; ?>" tabindex="110" maxlength="255" value="<?php echo $email; ?>" placeholder="enter your email address" autofocus onfocus="this.select()" > 
                
                    <label class="required" for="txtFirstName">First Name </label> 
                    
                    <input id ="txtFirstName" name="txtFirstName" class="element text medium<?php if ($firstnameERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $firstname; ?>" placeholder="enter your first name" onfocus="this.select()"  tabindex="30"/> 
                    
                    <label class="required" for="txtPhoneNumber">Phone Number </label> 
                    
                    <input id ="txtPhoneNumber" name="txtPhoneNumber" class="element text medium<?php if ($phonenumberERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $phonenumber; ?>" placeholder="enter your phone number" onfocus="this.select()"  tabindex="30"/>
             
                </fieldset>  

                <fieldset class="checkbox">
                    <legend>What style of beer do you most enjoy? </legend>
                    <label><input type="checkbox" id="chkIPA" name="chkIPA" value="India Pale Ale" tabindex="221" 
			<?php if($ipa) echo ' checked="checked" ';?>> India Pale Ale</label>
            
                    <label><input type="checkbox" id="chkStoutPorter" name="chkStoutPorter" value="Stout/Porter" tabindex="223" 
			<?php if($stoutporter) echo ' checked="checked" ';?>> Stout/Porter</label>
                    
                    <label><input type="checkbox" id="chkPale" name="chkPale" value="Pale" tabindex="223" 
                        <?php if($pale) echo ' checked="checked" ';?>> Pale</label>
                    
                    <label><input type="checkbox" id="chkWheat" name="chkWheat" value="Wheat" tabindex="223" 
                        <?php if($wheat) echo ' checked="checked" ';?>> Wheat</label>
                    
                    <label><input type="checkbox" id="chkLager" name="chkLager" value="Lager" tabindex="223" 
                        <?php if($lager) echo ' checked="checked" ';?>> Lager</label>
                    
                    <label><input type="checkbox" id="chkFruit" name="chkFruit" value="Fruit" tabindex="223" 
                        <?php if($fruit) echo ' checked="checked" ';?>> Fruit</label>
                    
                    <label><input type="checkbox" id="chkSour" name="chkSour" value="Sour" tabindex="223" 
                        <?php if($sour) echo ' checked="checked" ';?>> Sour</label>
                    
                </fieldset>
               
                <fieldset class="lists">	
                    <legend>Which of the following best describes your affiliation with VT craft brewing?</legend>
                    <select id="consumer" name="consumer" tabindex="281" size="1">
                    <option value="don't care" <?php if($consumer == "don't care") echo ' selected="selected" ';?>>Don't care</option>
                    <option value="fan" <?php if($consumer == "fan") echo ' selected="selected" ';?>>I'm a fan</option>
                    <option value="avid" <?php if($consumer == "avid") echo ' selected="selected" ';?>>Avid follower</option>
                    <option value="brewer" <?php if($consumer == "brewer") echo ' selected="selected" ';?>>I brew</option>
                    </select>
                </fieldset>
                
                <fieldset class="radio">
                    <legend>Would you be interested in purchasing our beer if it became available?</legend>
                    <label><input type="radio" id="interest" name="interest" value="1" tabindex="233" 
                    <?php if($interest=="1") echo ' checked="checked" ';?>>Yes</label>
            
                    <label><input type="radio" id="interest" name="interest" value="0" tabindex="233" 
                    <?php if($interest=="0") echo ' checked="checked" ';?>>No</label>
                </fieldset>

                <fieldset class="buttons"> 
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="991" class="button"> 
                    <input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm()" > 
                </fieldset> 

            </form> 
            <?php 
        } // end body submit 
        if ($debug) 
            print "<p>END OF PROCESSING</p>"; 
        include ("footer.php");
        ?> 
    </section>
<div id="left"></div>
<div id="right"></div>
<div id="top"></div>
<div id="bottom"></div>
</body> 
</html>