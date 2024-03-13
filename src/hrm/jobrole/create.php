<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $job_role= $obj["job_role"];
  $is_permanent= $obj["is_permanent"];
  $is_system_user= $obj["is_system_user"];
  $rate= $obj["rate"];
  $rate_type= $obj["rate_type"];
  $ot_rate= $obj["ot_rate"];

  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `job_role` (`job_role`, `is_permanent`, `is_system_user`, `rate`, `rate_type`, `ot_rate`) 
  VALUES (?, ?, ?, ?, ?, ?);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssssi",$job_role,$is_permanent,$is_system_user,$rate,$rate_type,$ot_rate);

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
