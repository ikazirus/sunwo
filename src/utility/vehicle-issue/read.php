<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT vis.*, e.name, v.reg_no
FROM `vehicle_issue` vis
INNER JOIN vehicle v ON v.id = vis.vehicle_id
INNER JOIN employee e ON e.id = vis.emp_id";

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);
  $sql = "SELECT vis.*, e.name, v.reg_no
  FROM `vehicle_issue` vis
  INNER JOIN vehicle v ON v.id = vis.vehicle_id
  INNER JOIN employee e ON e.id = vis.emp_id
  WHERE vis.id=".$id.";";
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