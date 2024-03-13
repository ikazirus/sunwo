<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT *,q.status as q_status,q.id as q_id FROM `quotation` q
INNER JOIN `concrete_grade` cg ON cg.id = q.concrete_grade
INNER JOIN `customer` c ON c.id = q.cus_id
";

// FOR GIN
if(isset($_GET['valid'])){
  $sql = "SELECT *,q.status as q_status,q.id as q_id FROM `quotation` q
  INNER JOIN `concrete_grade` cg ON cg.id = q.concrete_grade  
  INNER JOIN `customer` c ON c.id = q.cus_id
  WHERE q.status = 'APPROVED' OR q.status = 'PARTIALLY DELIVERED';";
}

// FOR Approval
if(isset($_GET['invalid'])){
  $sql = "SELECT *,q.status as q_status,q.id as q_id FROM `quotation` q
  INNER JOIN `concrete_grade` cg ON cg.id = q.concrete_grade  
  INNER JOIN `customer` c ON c.id = q.cus_id
  WHERE q.status = 'PENDING' OR q.status = 'REJECTED';";
}

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);
  $sql = "SELECT *,q.status as q_status,q.id as q_id FROM `quotation` q
  INNER JOIN `concrete_grade` cg ON cg.id = q.concrete_grade
  INNER JOIN `customer` c ON c.id = q.cus_id
  WHERE q.id=".$id.";";
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