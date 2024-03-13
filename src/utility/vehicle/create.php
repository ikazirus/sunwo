<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $owner = $obj["owner"];
  $reg_no=$obj["reg_no"];
  $type= $obj["type"];
  $status= $obj["status"];
  $oil_meter= $obj["oil_meter"];
  $distance_meter= $obj["distance_meter"];
  $last_user= null;


  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `vehicle` 
  ( `owner`, `reg_no`, `type`, `status`, `oil_meter`, `distance_meter`, `last_user`) 
  VALUES ( ?,?,?,?,?,?,?);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssssss",$owner,$reg_no,$type,$status,$oil_meter,$distance_meter,$last_user);

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
