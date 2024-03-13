<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();
  
  $invoice_no = $obj['invoice_no'];
  $quotation_id = $obj['quotation_id'];
  $date = $obj['date'];
  $ms = $obj['ms'];
  $departure_time = $obj['departure_time'];
  $delivered_volume = $obj['delivered_volume'];
  $vehicle = $obj['vehicle'];
  $driver_id = $obj['driver_id'];
  $arrival_time = $obj['arrival_time'];
  $temperature_cel = $obj['temperature_cel'];
  $slump_at_plant = $obj['slump_at_plant'];
  $remarks = $obj['remarks'];
  $cum_del_volume = $obj['cum_del_volume'];
  $acceptance_note = $obj['acceptance_note'];



  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `deleivery_note` ( `invoice_no`, `quotation_id`, `date`, 
  `ms`, `departure_time`, `delivered_volume`, `vehicle`, `driver_id`, `arrival_time`, 
  `temperature_cel`, `slump_at_plant`, `remarks`, `cum_del_volume`, `acceptance_note`, `updated_on`) 
  VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,current_timestamp());");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("ssssssssssssss",
  $invoice_no,
  $quotation_id,
  $date,
  $ms,
  $departure_time,
  $delivered_volume,
  $vehicle,
  $driver_id,
  $arrival_time,
  $temperature_cel,
  $slump_at_plant,
  $remarks,
  $cum_del_volume,
  $acceptance_note
);

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
