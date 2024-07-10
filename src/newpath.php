<?php 
    if(isset($_POST['login'])){
        if(!empty($_POST['username'])&&!empty($_POST['passwd'])){
            echo $_POST['login'];
        }else{
            echo "โปรด";
        }
    }
?>