<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $item= $obj["item"];
  $qty= $obj["qty"];
  $note= $obj["note"];
  
  $stmt = $conn->prepare("INSERT INTO `utility` ( `item`, `qty`, `note`, `transaction_type`)
   VALUES (?, ?, ?, 'RECEIVED');");

  $stmt->bind_param("sss",$item,$qty,$note);

  $stmt->execute();

  $stmtStock = $conn->prepare("UPDATE `stock` SET `amount` = `amount` + ? WHERE `stock`.`id` = ?;");

  $stmtStock->bind_param("ss",$qty,$item);

  $stmtStock->execute();  
  $stmtStock->close();  

  if ($stmt->affected_rows>0) {
  echo json_response(200, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>
