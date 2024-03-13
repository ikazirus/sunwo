<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $name=$obj["name"];
  $description= $obj["description"];
  $type= $obj["type"];


  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `stock_item` ( `name`, `description`, `type`) VALUES (?,?,?);");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sss",$name,$description,$type);

  //execute statement
  $stmt->execute();
  $itemID = $stmt->insert_id;

  $stmt2 = $conn->prepare("INSERT INTO `stock` 
  (`id`, `item_id`, `updated_date`, `availability`, `amount`, `reserved_amount`) 
  VALUES (NULL, ?, current_timestamp(), 'NOT AVAILABLE', '0', '0')");

  $stmt2->bind_param("s",$itemID);

  $stmt2->execute();
  $stmt2->close();

  if ($stmt->affected_rows>0) {
  echo json_response(201, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>
