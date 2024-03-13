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
  $name=$obj["name"];
  $strength_psi= $obj["strength_psi"];
  $cement= $obj["cement"];
  $sand= $obj["sand"];
  $aggregates= $obj["aggregates"];
  $description= $obj["description"];;


  // prepare and bind
  $stmt = $conn->prepare("UPDATE `concrete_grade` 
  SET `name`= ?, 
  `description`= ?, 
  `strength_psi`= ?, 
  `cement`= ?, 
  `sand`= ? , 
  `aggregates` = ?  
  WHERE `id`= ?;");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("ssssssi",$name,$description,$strength_psi,$cement,$sand,$aggregates,$id);

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
