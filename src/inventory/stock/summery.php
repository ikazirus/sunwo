<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT si.type,SUM(s.amount) as amount, SUM(s.reserved_amount) as reserved_amount FROM `stock` s
INNER JOIN `stock_item` si ON si.id = s.item_id
GROUP BY si.type";


$result = mysqli_query($conn, $sql);

//This is how to output JSON data.
$data= [];

if(mysqli_num_rows($result)>0){
  while($row=mysqli_fetch_assoc($result))
  {
    array_push($data, $row);
  }
    
  echo json_response(200, $data);
}else{
  echo json_response(404, null);
}

CloseCon($conn);

?>