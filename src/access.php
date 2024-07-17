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
if (!empty($_SESSION['HN'])) {
    $hnid = $_SESSION['HN'];
?>
    <script>
        ses = <?php echo $hnid; ?>;
        console.log("ses:" + ses);
    </script>
<?php
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
                                <a class="dropdown-item ditem" href="#">แก้ไขข้อมูลส่วนตัว</a>
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
                        <a href="index.php" class="now">
                            <div class="col-md-12 ">
                                <img src="img/home.png" width="30px;" alt=""> หน้าแรก
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
                        <center>
                            <hr style="width:100px;">
                        </center>
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
                        <a href="setmanager.php" class="choosed">
                            <div class="col-md-12 ">
                                จัดการสิทธิการเข้าถึง
                            </div>
                        </a>
                        <a href="setmanager.php" class="choosed">
                            <div class="col-md-12 ">
                                จัดการสิทธิการประเมิน
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-10">
                    <table id="myTable" class="table table-bordered" style="width:100%;border:1px">
                        <thead>
                            <tr>
                                <th style="text-align: center;">รหัส</th>
                                <th style="text-align: center;">ชื่อ</th>
                                <th style="text-align: center;">นามสกุล</th>
                                <th style="text-align: center;">ตำแหน่งงาน</th>
                                <th style="text-align: center;">การเข้าถึง</th>
                                <th style="text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody id="history">
                            <?php
                            $user_sql = "SELECT * FROM users";
                            if ($result = mysqli_query($conn, $user_sql)) {
                                while ($row = mysqli_fetch_array($result)) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['user_fname']; ?></td>
                                        <td><?php echo $row['user_lname']; ?></td>
                                        <td><?php echo $row['job']; ?></td>
                                        <td>
                                                <select class="form-select form-select-sm w" name="" id="">
                                                     <option value="user" <?php if($row['level'] == 'user'){echo 'selected';} ?>>user</option> 
                                                    <option value="admin" <?php if($row['level'] == 'admin'){echo 'selected';} ?>>admin</option>
                                                     <option value="master" <?php if($row['level'] == 'master'){echo 'selected';} ?>>Master</option>
                                                </select>
                                            
                                        </td>
                                        <td width="15%">
                                            <a class="btn btn-warning w-100">แก้ไขข้อมูลส่วนตัว</a>
                                            <a class="btn btn-danger w-100">ลบบัญชี</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <!-- Development -->
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

        <!-- Production -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
</body>
<script>
    $(document).ready(function() {
        new DataTable('#myTable');
    });
    tippy('#print_btn', {
        content: 'print!',
    });
    tippy('#dt_btn', {
        content: 'Duplicate Template',
    });

    function img(id, path) {
        var img = document.getElementById(id);
        img.src = path;
    }

    function Table_to_Json(id_table) {
        var table = document.getElementById(id_table);
        var result = [];
        var rows = table.rows;
        var cells;
        var iLen;
        for (var i = 0, iLen = rows.length; i < iLen; i++) {
            cells = rows[i].cells;
            t = [];

            // Iterate over cells
            for (var j = 0, jLen = cells.length; j < jLen; j++) {
                t.push(cells[j].textContent);
            }
            result.push(t);
        }
        return result;
    }
</script>
<script>
    if (ses) {
        hn_search();
    }


    function CaseTable(Orcase, table_id) {
        let path = Orcase;
        let rs = [];
        fetch("Case.json")
            .then(res => res.text())
            .then(function(data) {
                //console.log(data);
                let myjson = JSON.parse(data); //text to jspOject
                $("#" + table_id + " tr").remove();
                var total = 0;
                for (var i in myjson[path]) {
                    var row = `<tr>
                        <td>${myjson[path][i]['ID']}</td>
                        <td>${myjson[path][i]['Medicine']}</td>
                        <td>${myjson[path][i]['Amount']}</td><td>
                        <td>${myjson[path][i]['Price']}</td>
                    </tr>`;
                    var table = $("#" + table_id);
                    var price = myjson[path][i]['Price'];
                    var amount = myjson[path][i]['Amount'];
                    total = total + (price * amount);
                    table.append(row);
                }

            }).catch(err => {
                console.log(err)
            });

    }


    function hn_search() {
        var txt = document.getElementById('hn_id').value;
        if (txt) {
            console.log(txt);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('idenTable').innerHTML = this.responseText;
                    document.getElementById('tablezone').style.display = 'block';

                }
            }
            xmlhttp.open("GET", "path.php?hn_id=" + txt, true);
            xmlhttp.send();
        } else {
            alert("กรุณากรอกรหัส HN เพื่อยืนยันตัวตน");
        }
    }

    function test() {
        console.log("helloworld");
    }
</script>

</html>