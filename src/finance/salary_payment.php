<?php
require_once "../../config/index.php";
require_once "../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $emp_id = $obj["emp_id"];
  $amount=$obj["amount"];
  $type= $obj["type"];

  // prepare and bind
  $stmt = $conn->prepare("CALL `salary_payment`( ?, ?, ?);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sss",$emp_id,$amount,$type);

  //execute statement
  $stmt->execute();

  if ($stmt->affected_rows>0) {
  echo json_response(200, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>