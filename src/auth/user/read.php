<?php

require_once "../../../config/index.php";
require_once "../check_auth.php";

$conn = OpenCon();

$sql = "SELECT * FROM user;";

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