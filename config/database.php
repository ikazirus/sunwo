<?php
require_once  __DIR__."/constants.php";

function OpenCon()
 {
   $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Connect failed: %s\n". $conn -> error);
   
   return $conn;
 }
 
function CloseCon($conn)
 {
   $conn -> close();
 }

 ?>