<?php
require_once __DIR__.'/../../vendor/autoload.php';
use \Firebase\JWT\JWT;

$headers_list = getallheaders();
$header_jwt =null;

if(isset($headers_list['Authorization'])){
  $header_jwt= $headers_list['Authorization'];
}
else{
  echo json_response(401,"Auth Headers Not Found");
  exit();
}


$jwt_user_id =0;
$jwt_user_role =null;

if($header_jwt!=null || $header_jwt!='n/a'){
  
 try{

    $decoded_data = JWT::decode($header_jwt, SECRET_KEY, ['HS512']);

    $jwt_user_id = $decoded_data->data->id;
    $jwt_user_role =$decoded_data->data->role;
    
    if($jwt_user_id == 0 || $jwt_user_role == null){
        echo json_response(401);
        exit();
    }

  }catch(Exception $ex){
    echo json_response(500,$ex->getMessage()); 
    exit();
  }
}else{
    echo json_response(403);
    exit();
}

?>
