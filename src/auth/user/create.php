<?php 

require_once "../../../config/index.php";
require_once "../check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if($jwt_user_role!="ADMIN"){
  echo json_response(401);
  exit();
}

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $username= $obj["username"];
  $password= $obj["password"];
  $sys_role= $obj["sys_role"];

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `user` (`username`, `password`, `active`, `sys_role`, `last_login`) 
  VALUES (?, ?, '1', ?, current_timestamp());");

  $stmt->bind_param("sss",$username,$hashed_password,$sys_role);

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
