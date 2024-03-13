<?php
require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

    $vehicle_id = $obj['vehicle_id'];
    $emp_id = $obj['emp_id'];
    $fuel_meter = $obj['fuel_meter'];
    $distance_meter = $obj['distance_meter'];
    $isReturned = $obj['isReturned'];
    $note = $obj['note'];

  $stmt = $conn->prepare("CALL `vehicle_issue`(?, ?, ?, ?, ?, ?);");


  $stmt->bind_param("ssssss",$vehicle_id,$emp_id,$fuel_meter,$distance_meter,$isReturned,$note);


  if ($stmt->execute()) {
    echo json_response(200, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>

