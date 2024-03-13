<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);

  $sql = "SELECT * FROM `grn` n
  INNER JOIN `supplier` s ON s.id = n.sup_id 
  WHERE n.id=".$id.";";

  $sqlItems = "SELECT * FROM `grn_item` gi 
  INNER JOIN `stock_item` si ON gi.item_id = si.id
  WHERE gi.grn_id=".$id.";";


  $result = mysqli_query($conn, $sql);
  $resultItems = mysqli_query($conn, $sqlItems);

  $data= [];

  if(mysqli_num_rows($result)>0){

    while($row=mysqli_fetch_assoc($result))
    {
      $grn =  $row;
      $grn['itemList']=[];
      while($rowItems=mysqli_fetch_assoc($resultItems))
      {
        array_push($grn['itemList'],$rowItems);
      }

      array_push($data,$grn);
    }

    echo json_response(200, $data);
  }else{
    echo json_response(404, null);
  }


}else{

  $sql = "SELECT  
  n.id,
  s.name,
  n.payment,
  n.sub_total,
  n.discount,
  n.payment_status,
  n.status,
  n.updated_on,
  n.note
  FROM `grn` n
  INNER JOIN supplier s ON s.id = n.sup_id";

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
