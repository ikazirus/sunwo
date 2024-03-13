<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $amount= $obj["amount"];
  $type= $obj["type"];
  $cash_type= $obj["cash_type"];
  $description= $obj["description"];
  $check_invoice= $obj["check_invoice"];
  $payee= $obj["payee"];


  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `cash_book` (`amount`, `type`, `cash_type`, `description`, `check_invoice`, `payee`,`date`) 
  VALUES ( ?, ?, ?, ?, ?, ?, CURRENT_DATE);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("ssssss",
  $amount,
  $type,
  $cash_type,
  $description,
  $check_invoice,
  $payee);

  //execute statement
  $stmt->execute();

 
  if ($stmt->affected_rows>0) {
      echo json_response(201, null);
  } else {
      echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>
