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



  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO  customer( `nic`, `name` , `email` , `address` , `contact`) VALUES ( ?, ?, ?, ?, ?);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssss",$nic,$name,$email,$address,$contact);

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
