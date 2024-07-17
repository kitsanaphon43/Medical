<?php 
session_start();
include_once("connect.php");
 if(isset($_POST['login'])){
    $pass_md5 = md5($_POST['passwd']);
    echo $pass_md5;
    //echo $pass_md5;
    $login_sql = "SELECT count(*) as 'accept_point' FROM `users` WHERE `password` = '" . $pass_md5 . "' AND `username` =  '" . $_POST['username'] . "';";
    $user_sql = "SELECT `user_fname`,`user_lname` FROM `users` WHERE `password` = '" . $pass_md5 . "' AND `username` =  '" . $_POST['username'] . "';";
   // echo $login_sql;
    if($res = mysqli_query($conn,$login_sql)){
        while($row = mysqli_fetch_assoc($res)){
            $accept_point =  $row['accept_point'];
            
        }
    }
    if($accept_point == 1){
        if($re = mysqli_query($conn,$user_sql)){
             while($r = mysqli_fetch_assoc($re)){
            $_SESSION['fname'] = $r['user_fname'];
            $_SESSION['lname'] = $r['user_lname'];
        }

        header('location:index.php');
        }
    }else{
        header('location:login.php?t='.time());
    }
}
if(isset($_GET['logout'])){ 
    if(session_destroy() == TRUE){
        header('Location:login.php');
    }
}
?>