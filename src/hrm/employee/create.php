<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $nic = $obj["nic"];
  $name=$obj["name"];
  $email= $obj["email"];
  $address= $obj["address"];
  $contact= $obj["contact"];
  $job_role= $obj["job_role"];
  $barcode= "EMP-".$nic;

  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `employee` 
  ( `barcode`, `name`, `nic`, `address`, `email`, `contact`, `credits`, `job_role`, `photo`, `status`) 
  VALUES ( ?, ?, ?, ?, ?, ?, 0, ?, NULL, 'ACTIVE');");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssssss",$barcode,$name,$nic,$address,$email,$contact,$job_role);

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
