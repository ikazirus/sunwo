<?php 

require_once "../../../config/index.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $name=$obj["name"];
  $email= $obj["email"];
  $address= $obj["address"];
  $contact= $obj["contact"];
  $status= 0;



  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO  customer( `nic`, `name` , `email` , `address` , `contact`, `status`) 
  VALUES (?, ?, ?, ?, ?, ?);");


  $stmt->bind_param("ssssss",$nic,$name,$email,$address,$contact,$status);

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
