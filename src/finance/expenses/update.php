<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $id = $obj["id"];
  $amount= $obj["amount"];
  $type= $obj["type"];
  $cash_type= $obj["cash_type"];
  $description= $obj["description"];
  $check_invoice= $obj["check_invoice"];
  $payee= $obj["payee"];



  // prepare and bind
  $stmt = $conn->prepare("UPDATE `cash_book` 
  SET `amount`= ?, 
  `type`= ?, 
  `cash_type`= ?, 
  `description`= ?, 
  `check_invoice`= ? , 
  `updated_on`= current_timestamp() , 
  `payee` = ?  
  WHERE `id`= ?;");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("ssssssi",
  $amount,
  $type,
  $cash_type,
  $description,
  $check_invoice,
  $payee,
  $id);

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
