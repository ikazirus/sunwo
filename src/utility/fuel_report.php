<?php

require_once "../../config/index.php";
require_once "../auth/check_auth.php";


$conn = OpenCon();

$sql = "SELECT fc.*, e.name, v.reg_no 
FROM fuel_consumption fc
INNER JOIN employee e ON e.id = fc.driver
INNER JOIN vehicle v ON v.id = fc.vehicle;";

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