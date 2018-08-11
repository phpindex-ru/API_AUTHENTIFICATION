<?php
session_start();
if (isset($_POST['submit'])) {
$username = $_POST['username'];
$password = $_POST['password'];
$submit = $_POST['submit'];
$url = "http://localhost/api_example/server.php";
$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url . '?username=' . $username . '&password='.$password.'&submit='.$submit);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);
$result = json_decode(json_encode($response), True);
$array = json_decode($result,true);  
$array = explode(" ",$array['data']);
$array = array_slice($array,0,3); 
// echo $array[0];
// echo '<br>';
// echo $array[1];
// echo '<br>';
// echo $array[2];
}
if(isset($array[2])){
        $_SESSION['token'] = $array[2];
}
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
        //echo '<pre>';
        //print_r($array);
        echo '<br>';
        echo '<small>Имя : </small>';
        echo $array['data'][0]['name'] . ' ' . $array['data'][0]['last_name'];
        echo '<br>';
        echo '<small>Фотография : </small>';
        echo '<br>';
        echo '<img src="http://localhost/api_example/';
        echo $array['data'][0]['foto'];
        echo '"';
        echo 'width="200"alt="lorem">';
        echo '<br>';
        echo '<small>Описание : </small>';
        echo $array['data'][0]['description'];
        //echo $array[2];
} else {
        echo "<form action='' method='post'>";
        echo "<input type='text' name='username' placeholder='username'><br><br>";
        echo "<input tupe='text' name='password' placeholder='password'><br><br>";
        echo "<input type='submit' name='submit' value='post'>";
        echo "</form>";
}
?>