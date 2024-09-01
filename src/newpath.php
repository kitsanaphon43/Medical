<?php 
session_start();
include_once("connect.php");
 if(isset($_POST['login'])){
    $pass_md5 = md5($_POST['passwd']);
    //echo $_POST['username']." ".$_POST['passwd']."=".$pass_md5."<br>";
    //echo $pass_md5."<br>";
    $login_sql = "SELECT count(*) as 'accept_point' FROM `users` WHERE `password` = '" . $pass_md5 . "' AND `username` =  '" . $_POST['username'] . "';";
    $user_sql = "SELECT `user_id`, `user_fname`,`user_lname`,`level` FROM `users` WHERE `password` = '" . $pass_md5 . "' AND `username` =  '" . $_POST['username'] . "';";
    //echo $login_sql."<br>";
    if($res = mysqli_query($conn,$login_sql)){
        while($row = mysqli_fetch_assoc($res)){
            $accept_point =  $row['accept_point'];
            
        }
    }
    echo $accept_point."<br>";
    if($accept_point == 1){
        if($re = mysqli_query($conn,$user_sql)){
             while($r = mysqli_fetch_assoc($re)){
            $_SESSION['user_id'] = $r['user_id'];
            $_SESSION['fname'] = $r['user_fname'];
            $_SESSION['lname'] = $r['user_lname'];
            $_SESSION['level'] = $r['level'];
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
if(isset($_POST['ddd'])){
    $r = $_POST['ddd'];
    $r_json = json_decode($r,true);
   
    $call_sql = "SELECT COUNT(*) FROM `users`";
    if($ans = mysqli_query($conn,$call_sql)){
        $numid = mysqli_fetch_array($ans)[0]+1;
        if($numid < 9){
            $newid = "USER00".$numid;
        }else if($numid < 99){
            $newid = "USER0".$numid;
        }else{
            $newid = "USER".$numid;
        }
       echo $newid;
    }

    $sql = "INSERT INTO `users`
    (`user_id`, `user_pre`, `user_fname`, `user_lname`
    , `user_engfull`, `user_englast`, `user_bday`
    , `user_mail`, `user_phone`, `username`
    , `password`, `job`, `level`) 
    VALUES ('$newid','".$r_json['callname']."','".$r_json['fullname']."'
    ,'".$r_json['lastname']."','".$r_json['engfull']."'
    ,'".$r_json['englast']."','".$r_json['bday']."'
    ,'".$r_json['email']."','".$r_json['phone']."'
    ,'".$r_json['username']."','".md5($r_json['passwd'])."'
    ,'".$r_json['job']."','".$r_json['setlevel']."')";
   
    if(mysqli_query($conn,$sql) == 1){
        echo "insert success";
    }
}
if(isset($_POST['deluser'])){
    $userid = $_POST['deluser'];
    $delsql = "DELETE FROM `users` WHERE `user_id` ='".$userid."'";
   // echo $delsql;
    if($_SESSION['user_id'] != $userid){
       if(mysqli_query($conn,$delsql) == 1){
            echo "ลบบัญชีสำเร็จ";
       }
    }else{
        echo "ไม่สามารถลบบัญชีนี้ เนื่องจากกำลังเข้าสู้ระบบอยู่";
    }
}
if(isset($_POST['newlevel'])){
    $value = $_POST['newlevel'];
    $uid = $_POST['v'];
    $levelsql = "UPDATE `users` SET `level`='$value' WHERE `user_id`='$uid'";
    //echo $levelsql;
    if(mysqli_query($conn,$levelsql) == 1){
        echo "insert success";
    }
}
if(isset($_POST['update'])){
    $up = $_POST['update'];
    $up_json = json_decode($up,true);
    $up_sql = "UPDATE `users` SET 
    `user_pre`='".$up_json['callname']."',`user_fname`='".$up_json['fullname']."',
    `user_lname`='".$up_json['lastname']."',`user_engfull`='".$up_json['engfull']."',
    `user_englast`='".$up_json['englast']."',`user_bday`='".$up_json['bday']."',
    `user_mail`='".$up_json['email']."',`user_phone`='".$up_json['phone']."',
    `job`='".$up_json['job']."' WHERE `user_id` = '".$up_json['uid']."'";
    if(mysqli_query($conn,$up_sql) == 1){
        echo 1;
    }
}
?>