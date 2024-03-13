<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $brno = $obj["brno"];
  $name=$obj["name"];
  $email= $obj["email"];
  $address= $obj["address"];
  $contact= $obj["contact"];



  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `supplier` (`name`, `address`, `email`, `brno`, `contact`) VALUES (?, ?, ?, ?, ?);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssss",$name,$address,$email,$brno,$contact);

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
