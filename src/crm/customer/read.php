<?php

require_once "../../../config/index.php";
// require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT c.*, SUM(q.rate*q.qty*(1-q.discount/100)) as tot_outstanding
FROM customer c
LEFT JOIN quotation q ON q.cus_id = c.id
WHERE q.status <> 'PENDING' OR  q.status <> 'REJECTED' OR q.id IS NULL
GROUP BY c.id";

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);
  $sql = "SELECT * FROM customer WHERE id=".$id.";";
}



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