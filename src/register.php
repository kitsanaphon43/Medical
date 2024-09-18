<script>
    var ses;
</script>
<?php
include("connect.php");
session_start();

if (empty($_SESSION['fname'])) {
    echo $_SESSION['fname'];
    header("location:login.php");
}
if (isset($_GET['editu'])) {
    $editu = $_GET['editu'];
    $get_pv = "SELECT * FROM users WHERE user_id ='" . $editu . "'";
    if ($rs = mysqli_query($conn, $get_pv)) {
        while ($row = mysqli_fetch_array($rs)) {
             $user_fname = $row['user_fname'];
             $user_lname = $row['user_lname'];
             $user_engfull = $row['user_engfull'];
             $user_englast = $row['user_englast'];
              $user_mail = $row['user_mail'];
              $user_phone = $row['user_phone'];
              $job =  $row['job'] 
              ?>
            <script>
                var user_id = '<?php echo $row['user_id'] ?>';
                var user_pre = '<?php echo $row['user_pre'] ?>';
                var user_bday ='<?php echo $row['user_bday'] ?>';
            </script>
<?php

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/s.css?<?php echo time() ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    <title>Estimate</title>

</head>
<style>
    .d_analyse {
        color: black;
        text-decoration: none;
        font-weight: bold;
        border: none;
        background: none;
    }

    .d_analyse:hover {
        color: blue;

        text-decoration: underline;
    }
</style>


<body style="font-family: 'Noto Sans Thai', sans-serif; ">
    <nav class="navbar navbar-expand-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><b>ระบบประเมินค่ารักษาพยาบาล</b></a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">

                </ul>
                <form class="d-flex my-2 my-lg-0">
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownId">
                            <a class="dropdown-item ditem" href="register.php?editu=<?php  echo $_SESSION['user_id']; ?>">แก้ไขข้อมูลส่วนตัว</a>
                            </div>
                        </li>
                    </ul>

                    <a href="newpath.php?logout=<?php echo time() ?>" class=" btn my-2 my-sm-0" id="Logout">
                        ออกจากระบบ
                    </a>
                </form>
            </div>
        </div>
    </nav>


    <div class="container-fluid">

        <div id="tablezone">
            <div class="row">

                <!---->
                <!--Estimate Table-->
                <div class="col-md-2" id="menu">
                    <div class="row">
                        <a href="index.php" class="choosed">
                            <div class="col-md-12 ">
                                หน้าแรก
                            </div>
                        </a>
                        <a href="estimate.php" class="choosed">
                            <div class="col-md-12 ">
                                เพิ่มใบประเมินราคา
                            </div>
                        </a>

                        <a href="appraisal.php" class="disabled">
                            <div class="col-md-12 choosed">
                                พิมพ์ใบประเมินราคา
                            </div>
                        </a>

                        <a href="setmanager.php" class="choosed">
                            <div class="col-md-12 ">
                                จัดการชุดผ่าตัด
                            </div>
                        </a>
                        <a href="lab_xray.php" class="choosed">
                            <div class="col-md-12 ">
                                จัดการชุดแล็ปและเอกซเรย์
                            </div>
                        </a>
                        <?php if ($_SESSION['level'] == 'admin') { ?>
                            <center>
                                <hr style="width:100px;">
                            </center>
                            <a href="access.php" class="now">
                                <div class="col-md-12 ">
                                    จัดการสิทธิการเข้าถึง
                                </div>
                            </a>
                            <a href="privilege.php" class="choosed">
                                <div class="col-md-12 ">
                                เกณฑ์การใช้สิทธิการรักษา
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-10">
                    <br>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <nav>คำนำหน้า</nav><select name="" class="form-select" id="callname">
                                <option value="นาย" selected>นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <nav>ชื่อ</nav><input type="text" id="fullname" class="form-control"  value="<?php if(!empty($user_fname)){echo $user_fname;}?>" >
                        </div>
                        <div class="col-md-3">
                            <nav>นามสกุล</nav><input type="text" id="lastname" class="form-control" value="<?php if(!empty($user_lname)){echo $user_lname;}?>">
                        </div>

                        <div class="col-md-2"></div>
                        <div class="col-md-2"></div>

                        <div class="col-md-4">
                            <nav>firstname</nav><input type="text" id="engfull" class="form-control"  value="<?php if(!empty($user_engfull)){echo $user_engfull;}?>" >
                        </div>
                        <div class="col-md-4">
                            <nav>lastname</nav><input type="text" id="englast" class="form-control"  value="<?php if(!empty($user_englast)){echo $user_englast;}?>" >
                        </div>

                        <div class="col-md-2"></div>
                        <div class="col-md-2"></div>

                        <div class="col-md-4">
                            <nav>e-mail</nav><input type="email" id="mail" class="form-control" value="<?php if(!empty($user_mail)){echo $user_mail;}?>">
                        </div>
                        <div class="col-md-4">
                            <nav>เบอร์โทรศัทพ์</nav><input type="text" id="phone" class="form-control" value="<?php if(!empty($user_phone)){echo $user_phone;}?>">
                        </div>

                        <div class="col-md-2"></div>
                        <div class="col-md-2"></div>

                        <div class="col-md-4">
                            <nav>วันเกิด</nav><input type="date" id="bday" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <nav>ตำแหน่งงาน</nav><input type="text" id="job" class="form-control" value="<?php if(!empty($job)){echo $job;}?>">
                        </div>

                        <div class="col-md-2"></div>
                        <div class="col-md-2"></div>
                           <?php 
                            if(empty($_GET['editu'])){
                                ?>
                          
                        <div class="col-md-2">
                            <nav>ชื่อผู้ใช้งาน</nav><input type="text" id="username" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <nav>สิทธิการเข้าถึง</nav>
                            <select name="setlevel" id="setlevel" class="form-select">
                                <option value="user" selected>user</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <nav>รหัสผ่าน</nav><input type="password" id="passwd" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <nav>ยืนยันรหัสผ่าน</nav><input type="password" id="c_passwd" class="form-control">
                        </div>

                        <div class="col-md-2"></div>
                        <div class="col-md-2"></div>
                   <?php 
                 }
                    ?>
                        <div class="col-md-4">
                            <?php
                            if (isset($_GET['editu'])) {
                            ?>
                                <button onclick="update_data()" class="btn btn-outline-warning mt-3 w-100" id="">แก้ไขข้อมูล</button>
                            <?php
                            } else {
                            ?>
                                <button onclick="checkinfo()" class="btn btn-outline-success mt-3 w-100" id="">เพิ่มบัญชี</button>
                            <?php
                            }
                            ?>

                            <nav style="color:red" name="noticfity"></nav>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-info w-100 mt-3" href="register.php">รีเซ็ต</a>
                        </div>

                        <div class="col-md-2"></div>
                    </div>


                </div>


            </div>
        </div>

        <!-- Boolstrap5.0.2 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <!-- datatable -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
        <!-- Development -->
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

        <!-- Production -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
</body>
<script>
      if(user_id){
        getvalue();
    }
    var counter = 0;

    function checkinfo() {
        counter = 0;
        let fullname = $('#fullname').val();
        let lastname = $('#lastname').val();
        let engfull = $('#engfull').val();
        let englast = $('#englast').val();
        let bday = $('#bday').val();
        let phone = $('#phone').val();
        let email = $('#mail').val();
        let job = $('#job').val();
        let username = $('#username').val();
        let passwd = $('#passwd').val();
        let c_passwd = $('#c_passwd').val();
        let callname = $('#callname').val();
        let setlevel = $('#setlevel').val();
        checker(fullname, 'fullname');
        checker(lastname, 'lastname');
        checker(engfull, 'engfull');
        checker(englast, 'englast');
        checker(bday, 'bday');
        checker(phone, 'phone');
        checker(email, 'mail');
        checker(job, 'job');
        checker(username, 'username');
        checker(passwd, 'passwd');
        console.log(counter);
        if (counter == 10) {
            if (passwd == c_passwd) {
                var res_json = {
                    "fullname": fullname,
                    "lastname": lastname,
                    "engfull": engfull,
                    "englast": englast,
                    "bday": bday,
                    "phone": phone,
                    "email": email,
                    "job": job,
                    "username": username,
                    "passwd": passwd,
                    "callname": callname,
                    "setlevel": setlevel
                };
                console.log(res_json);
                res_json = JSON.stringify(res_json);
                var http = new XMLHttpRequest();
                var url = 'newpath.php';
                var params = 'ddd=' + res_json;
                http.open('POST', url, true);

                //Send the proper header information along with the request
                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                http.onreadystatechange = function() { //Call a function when the state changes.
                    if (http.readyState == 4 && http.status == 200) {
                        console.log(http.responseText);
                    }
                }
                http.send(params);

            } else {
                alert('รหีสผ่านไม่ตรงกัน')
            }
        }
    }

    function checker(item, id) {
        if (item) {
            counter++;
        } else {

            document.getElementById(id).style.borderColor = "#FFAAAA";
            document.getElementById(id).style.borderWidth = "2px";
            //alarm.setAttribute("style", "borderColor:#FFAAAA;borderWidth = 2px");
        }
    }
    function getvalue() {
        if(user_id){
            pre_select = document.getElementById("callname");
            pre_op = pre_select.querySelectorAll("option");
            for (var i = 0; i < pre_op.length; i++) {
                console.log(pre_op[i].value+":"+user_pre);
                if (pre_op[i].value == user_pre) {
                    pre_op[i].selected = true;
                 
                } 
            }
            var bday = document.getElementById("bday");
            bday.value = user_bday;
        }
    }
    function update_data() {
        counter = 0;
        let fullname = $('#fullname').val();
        let lastname = $('#lastname').val();
        let engfull = $('#engfull').val();
        let englast = $('#englast').val();
        let bday = $('#bday').val();
        let phone = $('#phone').val();
        let email = $('#mail').val();
        let job = $('#job').val();
        let username = $('#username').val();
        let passwd = $('#passwd').val();
        let c_passwd = $('#c_passwd').val();
        let callname = $('#callname').val();
        checker(fullname, 'fullname');
        checker(lastname, 'lastname');
        checker(engfull, 'engfull');
        checker(englast, 'englast');
        checker(bday, 'bday');
        checker(phone, 'phone');
        checker(email, 'mail');
        checker(job, 'job');
        if(counter == 8){
            var up_json = {
                    "uid": user_id,
                    "fullname": fullname,
                    "lastname": lastname,
                    "engfull": engfull,
                    "englast": englast,
                    "bday": bday,
                    "phone": phone,
                    "email": email,
                    "job": job,
                    "callname": callname,
                };
                console.log(up_json);
                up_json = JSON.stringify(up_json);
                var httpup = new XMLHttpRequest();
                var url = 'newpath.php';
                var params = 'update=' + up_json;
                httpup.open('POST', url, true);

                //Send the proper header information along with the request
                httpup.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                httpup.onreadystatechange = function() { //Call a function when the state changes.
                    if (httpup.readyState == 4 && httpup.status == 200) {
                        console.log(httpup.responseText);
                        
                        if(httpup.responseText == 1){
                            window.location.href="access.php";
                        }
                    }
                }
                httpup.send(params);
        }else{
            console.log(counter);
            
        }
    }
</script>

</html>