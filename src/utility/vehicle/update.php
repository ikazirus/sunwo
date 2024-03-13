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
  $owner = $obj["owner"];
  $reg_no=$obj["reg_no"];
  $type= $obj["type"];
  $status= $obj["status"];
  $oil_meter= $obj["oil_meter"];
  $distance_meter= $obj["distance_meter"];
  $last_user= $obj["last_user"];


  // prepare and bind
  $stmt = $conn->prepare("UPDATE `vehicle` 
  SET `owner`= ?, 
  `reg_no`= ?, 
  `type`= ?, 
  `status`= ?, 
  `oil_meter`= ? , 
  `distance_meter`= ? , 
  `updated_on`=current_timestamp(),
  WHERE `id`= ?;");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssssssi",
  $owner,
  $reg_no,
  $type,
  $status,
  $oil_meter,
  $distance_meter,
  $last_user,
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