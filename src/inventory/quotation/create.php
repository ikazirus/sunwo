<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $cus_id = $obj["cus_id"];
  $concrete_grade=$obj["concrete_grade"];
  $rate= $obj["rate"];
  $qty= $obj["qty"];
  $discount= $obj["discount"];

  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO `quotation` ( `cus_id`, `concrete_grade`, `rate`, 
  `qty`, `discount`, `cum_delivered_qty`, `created_date`, `updated_date`, `revision`, `status`)
  VALUES ( ?, ?, ?, ?, ?, '0', current_timestamp(), current_timestamp(), '0', 'PENDING');");

  /*
  i - integer
  d - double
  s - string
  b - BLOB 
  */

  $stmt->bind_param("sssss",$cus_id,$concrete_grade,$rate,$qty,$discount);

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
