<?php
header("Content-Type:application/json");
include_once("connection.php");





switch($_REQUEST['submit']) {
 case 'post':
 if(!empty($_GET['submit'])) {
    $username=$_GET['username'];
    $password = $_GET['password'];
    $items = getItems($username, $password);
    if(empty($items)) {
    jsonResponse(200,"Items Not Found",NULL);
    } else {
    jsonResponse(200,"Item Found",$items);
    $array = explode(" ",$items);
    $array = array_slice($array,0,3); 
    //print_r($array);
    //echo $array[0];
    //echo $array[1];
    //echo $array[2];
    putItems($array[2], $array[1]);
    }
    }
 break;
 case 'userinfo':
 if(!empty($_GET['token'])) {
    $token=$_GET['token'];
    $userinfo = getUserinfo($token);
    if(empty($userinfo)) {
        jsonResponse(200,"Items Not Found",NULL);
    } else {
        jsonResponse(200,"Item Found",$userinfo);
    }
 }

 break;
 case 'update':
 if(!empty($_POST['token'])) {
    $token=$_POST['token'];
    $name=$_POST['name'];
    $lastname=$_POST['last_name'];
    $description=$_POST['description'];
    $user_id = getUserid($token);
    if (isset($_FILES['myimage']['tmp_name'])) {
        $filename = $_FILES['myimage']['name'];
        $tmp           = explode('.', $filename);
        $end = end($tmp);
        $foto = "uploads/" . fotoKey() . "." . $end;
        move_uploaded_file($_FILES['myimage']['tmp_name'], $foto);
        }
    //echo  $userinfo;
    $updateuser = updateUser($user_id, $name, $lastname, $description, $foto);
    if(empty($updateuser)) {
        jsonResponse(200,"Items Not Found",NULL);
    } else {
        jsonResponse(200,"Item Found",$updateuser);
    }
 }






 break;
 case 'put':

 break;
 case 'delete':

 break;
 default:
 http_response_code(405);
}

function jsonResponse($status,$status_message,$data) {
    header("HTTP/1.1 ".$status_message);
    $response['status']=$status;
    $response['status_message']=$status_message;
    $response['data']=$data;
    $json_response = json_encode($response);
    echo $json_response;
    }

    function getItems($username, $password) {
        $sql = "SELECT * FROM users WHERE username=:username AND password=:password";
        $db = Db::getInstance();
        $query = $db->prepare($sql);
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $password);
        $query->execute();
        $count = $query->rowCount();
        if($count>0)
        {
        $userdata = $query->fetch();
        $data = 'SUCCESS' . ' ';
        $data .= $userdata['id'] . ' ';
        $data .= createKey();
        return $data;
        }
        else
        {
        //$data = 'ERROR';
        return 'ERROR';
        } 
    }

    function putItems($token, $userid) {

        $sql = "INSERT into tokens (`token`,`userid`) VALUES(:token,:userid); ";
        $db = Db::getInstance();
        $query = $db->prepare($sql);
        $query->bindParam(':token', $token);
        $query->bindParam(':userid', $userid);
        if($query->execute())
        {
        //$data = 'SUCCESS';
        return 'SUCCESS';
        }
        else
        {
       //$data = 'ERROR';
        return 'ERROR';
        } 
    
    }

    function getUserinfo($token) {
        $sql = "SELECT * FROM tokens WHERE token=:token";
        $db = Db::getInstance();
        $query = $db->prepare($sql);
        $query->bindParam(':token', $token);
        $query->execute();
        $count = $query->rowCount();
        if($count>0)
        {
        $userdata = $query->fetch();
        $userid = $userdata['userid'];
        $user = getUser($userid);
        return $user;
        }
        else
        {
        //$data = 'ERROR';
        return 'ERROR';
        } 
    }
    function getUser($id) {
    $sql = "SELECT * FROM userinfo WHERE user_id=:userid";
    $db = Db::getInstance();
    $query = $db->prepare($sql);
    $query->bindParam(':userid', $id);
    $query->execute();
    $data = $query->fetchAll();
    return $data;
    }


    function getUserid($token) {
        $sql = "SELECT * FROM tokens WHERE token=:token";
        $db = Db::getInstance();
        $query = $db->prepare($sql);
        $query->bindParam(':token', $token);
        $query->execute();
        $count = $query->rowCount();
        if($count>0)
        {
        $userdata = $query->fetch();
        $userid = $userdata['userid'];
        return $userid;
        }
        else
        {
        return 'ERROR';
        } 
    }

    function updateUser($user_id, $name, $lastname, $description, $foto) {
        $sql = "UPDATE userinfo SET name=:name, last_name=:last_name, description=:description, foto=:foto WHERE user_id=:user_id";
        $db = Db::getInstance();
        $query = $db->prepare($sql);
        $query->bindParam(':name', $name);
        $query->bindParam(':last_name', $lastname);
        $query->bindParam(':description', $description);
        $query->bindParam(':foto', $foto);
        $query->bindParam(':user_id', $user_id);
        if($query->execute())
        {
        return 'SUCCESS';
        }
        else
        {
        return 'ERROR';
        } 
    }







function createKey($length = 32) {
$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$key = "";
for ($i = 0; $i < $length; $i++) {
    $key .= $chars{rand(0, strlen($chars) - 1)};
}
return $key;
}

function fotoKey($length = 32)
{
$chars = "abcdefghijklmnopqrstuvwxyz1234567890";
$key = "";

for ($i = 0; $i < $length; $i++) {
    $key .= $chars{rand(0, strlen($chars) - 1)};
}

return $key;
}
