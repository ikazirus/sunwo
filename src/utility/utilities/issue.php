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
  $emp_id= $obj["emp_id"];
  $vehicle_id= $obj["vehicle_id"];
  
  $stmt = $conn->prepare("INSERT INTO `utility` ( `item`, `qty`, `note`, `transaction_type`,`emp_id`,`vehicle_id`)
   VALUES (?, ?, ?, 'ISSUED', ?, ?);");

  $stmt->bind_param("sssss",$item,$qty,$note, $emp_id, $vehicle_id);

  $stmt->execute();

  $stmtStock = $conn->prepare("UPDATE `stock` SET `amount` = `amount` - ? WHERE `stock`.`id` = ?;");

  $stmtStock->bind_param("ss",$qty,$item);

  $stmtStock->execute();  
  $stmtStock->close();  

  if ($obj["type"]=="OIL") {
    $stmtVehicle = $conn->prepare("UPDATE `vehicle` SET `oil_meter` = `oil_meter`+? WHERE `vehicle`.`id` = ?;");

  $stmtVehicle->bind_param("ss",$qty,$vehicle_id);

  $stmtVehicle->execute();
  $stmtVehicle->close();

  }

  if ($stmt->affected_rows>0) {
  echo json_response(200, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>
