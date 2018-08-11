<?php
session_start();
if (isset($_SESSION['token'])) {
        echo "<a href='client.php'>главная</a>";
        echo " ";
        echo "<a href='edit.php'>редактировать</a>";
        echo " ";
        echo "<a href='logout.php'>выход</a><br><br>";
        //echo $_SESSION['token'];
        $url = "http://localhost/api_example/server.php";
        $client = curl_init();
        curl_setopt($client, CURLOPT_URL, $url . '?token=' . $_SESSION['token'] . '&submit=userinfo');
        curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($client);
        $result = json_decode(json_encode($response), True);
        $array = json_decode($result,true);  
        $array = array_slice($array,0,3); 

        echo '<form action="" method="post" enctype="multipart/form-data">';
        echo '<input type="text" name="name" value="';
        echo  $array['data'][0]['name'];
        echo '"placeholder="Имя" size="44"><br><br>';
        echo '<input type="text" name="last_name" value="';
        echo  $array['data'][0]['last_name'];
        echo '"placeholder="Фамилия" size="44"><br><br>';
        echo '<textarea name="description" rows="10" cols="45" placeholder="Описание">';
        echo  $array['data'][0]['description'];
        echo '</textarea><br><br>';
        echo '<input type="hidden" name="token" value="';
        echo $_SESSION['token'] . '">';
        echo '<input type="file" name="image"/><br><br>';
        echo '<input type="submit" name="submit" value="update">';
        echo '<form>';


            if (isset($_POST['submit'])) {
                $name = $_POST['name'];
                $lastname = $_POST['last_name'];
                $description = $_POST['description'];
                $submit = $_POST['submit'];
                $token = $_POST['token'];
                $ch = curl_init();
                $cfile = new CURLFile($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);
                $data = array(
                    "myimage"=>$cfile,
                    "name"=>$name,
                    "last_name"=>$lastname,
                    "description"=>$description,
                    "submit"=>$submit,
                    "token"=>$token
                );
                curl_setopt($ch, CURLOPT_URL, "http://localhost/api_example/server.php");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            
                $response = curl_exec($ch);
                if ($response == true) {
                    echo "File posted";
                } else {
                    echo "Error: " . curl_error($ch);
                }
                echo "<pre>";
                echo "<br>";
            print_r($data);
            }

        }
?>

