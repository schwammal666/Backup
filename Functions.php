<?php 
//$_SERVER ist ein Array und wird vom Webserver befüllt
function GetCurrentUrl(){
    $protocol = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $protocol;
}

function CheckErrors($response, $message){
    $url = GetCurrentUrl();
    if(strpos($url, "$response")==true){
        echo '<p class="text-danger">' . $message . '</p>';
    }
}
?>