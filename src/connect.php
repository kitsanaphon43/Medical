<?php
    
    $host="localhost";
    $username="root";
    $password ="";
    $database = "estimatebase";

    $conn = mysqli_connect($host,$username,$password,$database) or die("Error : ".mysqli_error($conn));//เชื่อม DB
    function mysqlFetch($conn,$sql){// ฟังค์ชัน fetch to array
        $data = [];
        if ($rs = mysqli_query($conn, $sql)) {
            while ($r = mysqli_fetch_assoc($rs)) {
                $data[] = $r;
            }
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return $data;
    }
?>