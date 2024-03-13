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
  $nic = $obj["nic"];
  $name=$obj["name"];
  $email= $obj["email"];
  $address= $obj["address"];
  $contact= $obj["contact"];
  $status= $obj["status"];


  // prepare and bind
  $stmt = $conn->prepare("UPDATE `customer` 
  SET `nic`= ?, 
  `name`= ?, 
  `email`= ?, 
  `address`= ?, 
  `contact`= ? , 
  `status` = ?  
  WHERE `id`= ?;");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssssss",$nic,$name,$email,$address,$contact,$status,$id);

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
