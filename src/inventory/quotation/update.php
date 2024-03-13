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
  $cus_id = $obj["cus_id"];
  $concrete_grade=$obj["concrete_grade"];
  $rate= $obj["rate"];
  $qty= $obj["qty"];
  $discount= $obj["discount"];


  // prepare and bind
  $stmt = $conn->prepare("UPDATE `quotation` 
  SET
  `cus_id`=?,
  `concrete_grade`=?,
  `rate`=?,
  `qty`=?,
  `discount`=?,
  `updated_date`=current_timestamp(),
  `revision`= (`revision`+1),
  `status`='PENDING'
  WHERE `id`= ?;");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssssi",$cus_id,$concrete_grade,$rate,$qty,$discount,$id);

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
