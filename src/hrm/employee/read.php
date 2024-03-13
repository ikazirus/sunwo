<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT *, employee.job_role as job_role_id,  employee.id as emp_id   
FROM employee 
INNER JOIN job_role ON job_role.id = employee.job_role;";

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);
  $sql = "SELECT *, employee.job_role as job_role_id , employee.id as emp_id  
  FROM employee 
  INNER JOIN job_role ON job_role.id = employee.job_role 
  WHERE employee.id=".$id.";";
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
