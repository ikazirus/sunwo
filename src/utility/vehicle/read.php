<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT v.*, e.name AS last_user 
FROM `vehicle` v
LEFT JOIN employee e ON e.id = v.last_user;";

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);
  $sql = "SELECT v.*, e.name AS last_user 
  FROM `vehicle` v
  LEFT JOIN employee e ON e.id = v.last_user
  WHERE id=".$id.";";
}else 
if (isset($_GET['available'])){
  $sql = "SELECT * FROM `vehicle` WHERE `status`='AVAILABLE';";
}



$result = mysqli_query($conn, $sql);

//This is how to output JSON data.
$data= [];

if(mysqli_num_rows($result)>0){
  while($row=mysqli_fetch_assoc($result))
  {
    array_push($data, $row);
  }
    
  echo json_response(200, $data);
}else{
  echo json_response(404, null);
}

CloseCon($conn);

?>