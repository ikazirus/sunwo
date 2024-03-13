<?php

require_once '../../vendor/autoload.php';
use \Firebase\JWT\JWT;

require_once "../../config/index.php";

$obj = json_decode(file_get_contents("php://input"),true);


if($obj != null){
  $conn = OpenCon();

  $username= $obj["username"];
  $password= $obj["password"];

  $stmt = $conn->prepare("SELECT * FROM `user` WHERE username = ? AND active = 1;");
  $stmt->bind_param("s",$username);
  
  if ($stmt->execute()) {
    $result = $stmt->get_result(); // get the mysqli result
    $user_data = $result->fetch_assoc(); // fetch data   
   
   if(!empty($user_data)){

      $id = $user_data['id'];
      $name = $user_data['username'];
      $password_db = $user_data['password'];
      $sys_role = $user_data['sys_role'];

      
      if(password_verify($password, $password_db)){ // normal password, hashed password
  
        $iss = ISS;             // issuer -> localhost
        $iat = time();          // issued at time
        $nbf = $iat;
        $exp = $iat + 1*60*60;  // expired at
        $aud = "suwo_erp";       // audience
        $user_arr_data = array(
          "id" => $id,
          "name" => $name,
          "role" => $sys_role
        );
  
  
        $payload_info = array(
          "iss"=> $iss,
          "iat"=> $iat,
          "nbf"=> $nbf,
          "exp"=> $exp,
          "aud"=> $aud,
          "data"=> $user_arr_data
        );
  
        $jwt = JWT::encode($payload_info, SECRET_KEY, 'HS512');
  
        echo json_response(200,array
          ("jwt"=>$jwt,
          "name"=>$name,
          "id"=>$id,
          "role"=>$sys_role)
          );
      }else{
        echo json_response(401, "Incorrect Username or Password");
      }

    } else {
      echo json_response(404, "User Not Found");
    }
  
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}

?>