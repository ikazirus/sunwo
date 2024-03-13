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
  $job_role = $obj["job_role"];
  $is_permanent= $obj["is_permanent"];
  $is_system_user= $obj["is_system_user"];
  $rate= $obj["rate"];
  $rate_type= $obj["rate_type"];
  $ot_rate= $obj["ot_rate"];



  // prepare and bind
  $stmt = $conn->prepare("UPDATE `job_role` 
  SET `job_role`= ?, 
  `is_permanent`= ?, 
  `is_system_user`= ?, 
  `rate`= ?, 
  `rate_type`= ? , 
  `ot_rate` = ?  
  WHERE `id`= ?;");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("ssssssi",$job_role,$is_permanent,$is_system_user,$rate,$rate_type,$ot_rate,$id);

  //execute statement
  $stmt->execute();

  if ($stmt->affected_rows>0) {
  echo json_response(202, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>