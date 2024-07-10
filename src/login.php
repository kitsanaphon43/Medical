<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <link rel="stylesheet" href="css/login.css?=<?php echo time()?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
</head>
<style>
    body{
        overflow: hidden;
        background-color: aquamarine;
    }
</style>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7" ></div>
            <div class="col-md-5" id="space">
                        <center>
                            <h4 class="card-title" style="margin-top:30%">เข้าสู่ระบบ</h4>
                        </center>
                                <form action="newpath.php" method="post">
                                    <nav>ชื่อผู้ใช้งาน</nav>
                                    <input type="text " name="username" class="form-control">
                                    <br>
                                    <nav>รหัสผ่าน</nav>
                                    <input type="password" name="passwd" class="form-control">
                                    <input type="checkbox" onchange="showpwd()" name="" id=""> แสดงรหัสผ่าน
                                    <br>
                                   <center><input type="submit" name="login" value="เข้าสู่ระบบ" class="btn btn-outline-primary w-50" style="margin-top: 20px;"></center>
                                </form>                           
                </div>
         
           
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</body>

</html>