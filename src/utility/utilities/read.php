<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT ut.*, ut.id AS util_id,si.name AS item_name,si.type, em.name AS emp_name,  v.reg_no AS v_name
FROM `utility` ut 
INNER JOIN `stock_item` si ON si.id = ut.item 
LEFT JOIN `employee` em ON em.id = ut.emp_id
LEFT JOIN `vehicle` v ON v.id = ut.vehicle_id;";

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);
  $sql = "SELECT ut.*, ut.id AS util_id,si.name AS item_name,si.type, em.name AS emp_name,  v.reg_no AS v_name
  FROM `utility` ut 
  INNER JOIN `stock_item` si ON si.id = ut.item 
  LEFT JOIN `employee` em ON em.id = ut.emp_id
  LEFT JOIN `vehicle` v ON v.id = ut.vehicle_id
  WHERE ut.id=".$id.";";
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
