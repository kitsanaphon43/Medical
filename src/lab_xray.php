<?php
include("connect.php");
session_start();
if (empty($_SESSION['HN'])) {
    header('Location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/s.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    <title>LAB&X-RAY</title>
</head>
<style>
  
</style>
<body style="font-family: 'Noto Sans Thai', sans-serif;">
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12" style="background-color:rgb(69, 158, 214);color:white">
                <h2 style="text-align: center;margin-top:20px;">แล็ปและเอกซเรย์</h2>
            </div>
        <div class="col-md-2" style="margin-top:20px;" id="menu">
                <div class="row">
                    <a href="index.php">
                        <div class="col-md-12 choosed">
                            หน้าแรก
                        </div>
                    </a>
                    <a href="estimate.php">
                        <div class="col-md-12 choosed">
                            เพิ่มใบประเมินราคา
                        </div>
                    </a>
                   
                    <a href="appraisal.php"class ="disabled">
                        <div class="col-md-12 choosed">
                             พิมพ์ใบประเมินราคา
                        </div>
                    </a> 
                    <center>
                    <hr style="width:100px;"></center>
                    <a href="setmanager.php">
                        <div class="col-md-12 choosed">
                            จัดการชุดผ่าตัด
                        </div>
                    </a>
                    <a href="lab_xray.php">
                            <div class="col-md-12 now">
                            <img src="img/x-ray.png" width="30px;" alt=""> จัดการชุดแล็ปและเอกซเรย์
                            </div>
                    </a>
                </div>
            </div>
            <div class="col-md-10" style="margin-top:20px;" style="border-left:1px solid gray ; height: 500px;">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <select class="form-select form-select-md" name="" id="type">
                            <option value="LAB">LAB</option>
                            <option value="X-RAY">X-RAY</option>
                        </select>
                        </div>
                        <div class="col-md-3"><input type="text" class="form-control" id="name"  placeholder="ชื่อรายการ"></div>
                        <div class="col-md-3"><input type="number" class="form-control" id="cost" placeholder="ราคา"></div>
                        <div class="col-md-2"><button class="btn btn-success w-100 " onclick="eventbtn()" id="addbtn">+ เพิ่มรายการ</button></div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="row" style="margin-top: 20px;" >
                    <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <center>
                            <table class="table " id="labtable">
                                <thead>
                                    <th>รหัส</th>
                                    <th>ชื่อรายการ</th>
                                    <th>ประเภท</th>
                                    <th>ราคา</th>
                                    <th>สถานะ</th>
                                </thead>
                                <tbody>
                                <?php
                                     $sql = "SELECT * FROM labxray ";
                                     if ($result = mysqli_query($conn, $sql)) {
                                        while($row = mysqli_fetch_array($result)){
                                 ?>
                                 <tr>
                                            <td><?php echo $row['lab_id'] ?></td>
                                            <td><?php echo $row['lab_name'] ?></td>
                                            <td><?php echo $row['lab_type'] ?></td>
                                            <td><?php echo $row['lab_cost'] ?></td>
                                            <td width="10%">
                                                <div class='form-check form-switch'>
                                                    <?php if ($row['lab_status'] == 1) { ?>
                                                        <input class='form-check-input' onclick="update_status('<?php echo $row['lab_id'] ?>')" type='checkbox' id='<?php echo 'status'.$row['lab_id'] ?>' role='switch' checked />
                                                        <label class='form-check-label' style='color:#35fd2e' id='<?php echo 'messtatus'.$row['lab_id'] ?>' for='flexSwitchCheckDefault'>Active</label>
                                                    <?php } else { ?>
                                                        <input class='form-check-input' onclick="update_status('<?php echo $row['lab_id'] ?>')" type='checkbox' id='<?php echo 'status'.$row['lab_id'] ?>' role='switch' />
                                                        <label class='form-check-label'id='<?php echo 'messtatus'.$row['lab_id'] ?>' for='flexSwitchCheckDefault'>InActive</label>

                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                 <?php }} ?>
                                </tbody>
                            </table>
                            </center>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
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
<script>
    $(document).ready(function(){
        $('#labtable').dataTable();
    });
    function eventbtn(){
        var name =document.getElementById('name').value;
        var cost =document.getElementById('cost').value;
        if(name && cost ){
            var type = document.getElementById('type').value;
           window.location.href = "path.php?name="+name+"&cost="+cost+"&type="+type+"&go=1";
        }else{
            alert('กรุณากรอกข้อมูลให้ครบ');
        }
    }
    function update_status(data) {
        var x = confirm("ต้องการเปลี่ยนสถานะหรือไม่");
        var toggle = document.getElementById('status'+data);
        var key = 0;
        if (x == true) {
            if (toggle.checked == true) {
                key = 1;
            } else {
                key = 0;
            }
            var lab_id = data;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    if(key == 1){
                    document.getElementById('status'+data).checked = true;
                    document.getElementById('messtatus'+data).innerHTML = "Active";
                    document.getElementById('messtatus'+data).style.color = "#35fd2e";
                    }else{
                    document.getElementById('status'+data).checked = false;
                    document.getElementById('messtatus'+data).innerHTML = "InActive";
                    document.getElementById('messtatus'+data).style.color = "gray";
                    }
                }
            }
            xmlhttp.open("GET", "path.php?key=" + key + "&lab_id=" + lab_id, true);
            xmlhttp.send();
        } else if (x == false) {
            if (toggle.checked == true) {
                toggle.checked = false;
            } else {
                toggle.checked = true;
            }
        }
    }
</script>
</html>