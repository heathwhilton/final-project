<?php
  // retrieve session information
  session_start();

  // if no username set, then redirect to login
  if(!isset($_SESSION['myusername'])){
    header("location:login.php");
    exit;
  }  
  
  // check that a orderNumber value was sent

  if ( !isset($_POST['orderNumber']) )
  {
    header("location:edit_select.php?deleteEntryError=1"); 
    exit;

  }
  
  if ($_POST['orderNumber'] == "")
  {
    header("location:edit.php?deleteEntryError=1"); 
    exit;

  }

  // open connection to the database on LOCALHOST with 
  // orderNumber of 'root', password '', and database 'web'

  @ $db = new mysqli('LOCALHOST', 'root', '', 'web');

  // Check if there were error and if so, report and exit

  if (mysqli_connect_errno()) 
  { 
    echo 'ERROR: Could not connect to database, error is '.mysqli_connect_error();
    exit;
  }

  // delete the selected entry with a prepared statement

  $query = "DELETE FROM `listing` WHERE orderNumber = ?";

  $stmt = $db->prepare($query);

  $stmt->bind_param("i", $_POST['orderNumber']);

  $stmt->execute();

  // check for errors

  if ($stmt->errno <> 0)
  {
    $stmt->close();
    $db->close();
    header("location:edit_select.php?deleteEntryError=2");
    exit;
  }

  // deallocate memory for the results and close the database connection

  $stmt->close();

  $db->close();

  // return to edit_select.php successfully

  header("location:edit_select.php?deleteEntrySuccess=1");

?>
