<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);

  $sql = "SELECT *, g.id AS gin_id FROM `gin` g
  INNER JOIN `quotation` q ON q.id = g.quotation_id 
  INNER JOIN `customer` c ON c.id = q.cus_id 
  INNER JOIN `concrete_grade` cg ON cg.id = q.concrete_grade 
  WHERE g.id=".$id.";";

  $sqlItems = "SELECT * FROM `gin_item` gi 
  INNER JOIN `stock_item` si ON gi.item_id = si.id
  WHERE gi.gin_id=".$id.";";


  $result = mysqli_query($conn, $sql);
  $resultItems = mysqli_query($conn, $sqlItems);

  $data= [];

  if(mysqli_num_rows($result)>0){

    while($row=mysqli_fetch_assoc($result))
    {
      $gin =  $row;
      $gin['itemList']=[];
      while($rowItems=mysqli_fetch_assoc($resultItems))
      {
        array_push($gin['itemList'],$rowItems);
      }

      array_push($data,$gin);
    }

    echo json_response(200, $data);
  }else{
    echo json_response(404, null);
  }


}else{

  $sql = "SELECT *, g.id AS gin_id FROM `gin` g
  INNER JOIN `quotation` q ON q.id = g.quotation_id 
  INNER JOIN `customer` c ON c.id = q.cus_id;";

  $result = mysqli_query($conn, $sql);

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
}

CloseCon($conn);

?>
