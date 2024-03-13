<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(401,null);
}
else{
  $conn = OpenCon();

  $name=$obj["name"];
  $strength_psi= $obj["strength_psi"];
  $cement= $obj["cement"];
  $sand= $obj["sand"];
  $aggregates= $obj["aggregates"];
  $description= $obj["description"];
  
  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `concrete_grade` ( `name`, `strength_psi`, `cement`, `sand`, `aggregates`, `description`) VALUES (?,?,?,?);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("ssssss",$name,$strength_psi,$cement,$sand,$aggregates,$description);

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
