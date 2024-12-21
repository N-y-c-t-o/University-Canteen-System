<?php

$db = mysqli_connect("localhost", "root", "", "unicanteen"); // connecting 
// Check connection
if (!$db) {       //checking connection to DB	
    die("Connection failed: " . mysqli_connect_error());
}

?>