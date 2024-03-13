<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$dateString = date("Y");

if(isset($_GET['q'])){
  $dateString = $conn -> real_escape_string($_GET['q']);
}

$sql = "SELECT tr.id, u.username, tr.time, tr.item_type, tr.item_id, tr.qty, tr.reference_type, tr.reference_id, si.name 
FROM `transaction_record` tr 
INNER JOIN `user` u ON u.id = tr.user 
INNER JOIN `stock_item` si ON si.id = tr.item_id 
WHERE tr.time LIKE '".$dateString."%'
ORDER BY tr.time DESC
;";

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