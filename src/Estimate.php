<script>
    var ses;
    var getId = null;
    var sesprivacy = "";
    var warnning = "";
</script>
<?php
include("connect.php");

session_start();
if (!empty($_SESSION['HN'])) {
    $hnid = $_SESSION['HN'];
?>
    <script>
        ses = <?php echo $hnid; ?>;
        //console.log("ses:" + ses);
    </script>
<?php
} else {
    header('Location:index.php');
}
if (isset($_GET['doc_id'])) { //// ดึงข้อมูลเอกสารเก่า
    $docid = $_GET['doc_id'];
    $viewsql = "SELECT DISTINCT doc.doc_id ,doc.doc_name,doc.doc_privacy
    FROM docestimate doc LEFT JOIN docdetail d ON doc.doc_id = d.doc_id 
    WHERE doc.doc_id = '" . $docid . "';";
    $lab_sql = "SELECT `Item_id` FROM docdetail WHERE `Item_id` LIKE '%LX%' AND `doc_id` ='" . $docid . "';";
    $set_sql = "SELECT DISTINCT `item_set` FROM `docdetail` WHERE `doc_id` ='" . $docid . "' AND `item_set` != '';";
    $restquery = "SELECT `Item_id`,`Item_min_amount`,`Item_max_amount` FROM docdetail WHERE `Item_id` LIKE '%21%' AND `doc_id`='" . $docid . "'";
    $orquery = "SELECT `Item_id`,`Item_min_amount`,`Item_max_amount` FROM docdetail WHERE `Item_name` LIKE '%ผ่าตัด%' AND `doc_id`='" . $docid . "'";
    $othersql = "SELECT `Item_name`,`Item_min_amount`,`Item_max_amount`,`item_price` FROM `docdetail` WHERE `Item_id` LIKE '%OTHER%' AND `doc_id`='" . $docid . "';";
    if ($rs = mysqli_query($conn, $viewsql)) {
        $r = mysqli_fetch_assoc($rs);
        $sesprivacy = $r['doc_privacy'];
        $sesdiag = $r['doc_name'];
    }
    $sesset = mysqlFetch($conn, $set_sql);
    $sesLab = mysqlFetch($conn, $lab_sql);
    $sesrest = mysqlFetch($conn, $restquery);
    $sesOR = mysqlFetch($conn, $orquery);
    $sesOther = mysqlFetch($conn, $othersql);
?>
    <script>
        getId = '<?php echo $_GET['doc_id'] ?>';
    </script>
<?php
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/s.css?<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    <title>Create New document</title>
</head>

<body style="font-family: 'Noto Sans Thai', sans-serif;">
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
        <div class="row">
            <!--interface เมนู-->
            <div class="col-md-2" id="menu">
                <div class="row">
                    <a href="index.php" class="choosed">
                        <div class="col-md-12 ">
                            หน้าแรก
                        </div>
                    </a>
                    <a href="estimate.php" class="now">
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
                    <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'superadmin') { ?>
                        <center>
                            <hr style="width:100px;">
                        </center>
                        <a href="access.php" class="choosed">
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
            <div class="col-md-10" style="margin-top:20px;">

                <div class="row" style="margin-left:5px;">
                    <!--interface สิทธิ์-->
                    <div class="col-md-12" style="margin-bottom:20px;">
                        <?php
                        $sqli = "SELECT v.HN_id as vid
                         ,v.visit_name as vname,v.visit_address as vaddress
                         ,v.visit_tel as vtel,v.uc_id as uc_id,v.visit_iden as viden ,u.uc_name as uname
                          FROM visit v LEFT JOIN uc u ON v.uc_id = u.uc_id 
                          WHERE HN_id = " . $_SESSION['HN'] . "";
                        if ($res = mysqli_query($conn, $sqli)) {
                            while ($row = mysqli_fetch_array($res)) {
                                $vname = $row['vname'];
                                $vaddress = $row['vaddress'];
                                $vtel = $row['vtel'];
                                $viden = $row['viden'];
                                $uname = $row['uname'];
                                ?>
                                <script>
                                    var uc_id = '<?php echo $row['uc_id'];?>';   
                               
                                </script>
                                <?php
                               
                            }
                        }
                        ?>
                        <table class="table table-borderless">
                            <tbody id="idenTable">
                                <tr>
                                    <td><b>HN</b></td>
                                    <td><?php echo  $_SESSION['HN']; ?></td>
                                    <td><b>ชื่อ-นามสกุล</b></td>
                                    <td><?php echo  $vname; ?></td>
                                </tr>
                                <tr>
                                    <td><b>ที่อยู่</b></td>
                                    <td><?php echo  $vaddress; ?></td>
                                    <td><b>บัตรประชาชน</b></td>
                                    <td><?php echo  $viden; ?></td>
                                </tr>
                                <tr>
                                    <td><b>เบอร์ติดต่อ</b></td>
                                    <td><?php echo  $vtel; ?></td>
                                    <td><b>สิทธิ์</b></td>
                                    <td>
                                    <?php echo  $uname; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!---<div class="col-md-12" style="margin-bottom:20px;">
                        <h5>สิทธิ์</h5>
                        <div class="row" id="p_box">
                            <div class="col-md-1"></div>
                            <div class="col-md-2">
                                <input type="radio" class="form-check-input privacy" value="UC001" name="privacy" id=""> ไม่มี
                            </div>
                            <div class="col-md-2">
                                <input type="radio" class="form-check-input privacy" value="UC002" name="privacy" id=""> UC
                            </div>
                            <div class="col-md-2">
                                <input type="radio" class="form-check-input privacy" value="UC003" name="privacy" id=""> OFC
                            </div>
                        </div>
                    </div>-->
                    <hr>
                    <!--interface การวินิฉัยโรค-->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <center>
                                    <h5>การวินิฉัยโรค</h>
                                </center>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="diagnose" value='<?php if (isset($_GET['doc_id'])) {
                                                                                echo $sesdiag;
                                                                            } ?>' id="diagnose" class="form-control w-100">
                            </div>
                        </div>


                    </div>
                    <hr style="margin-top:15px;">
                    <!--interface ชุดผ่าตัด-->
                    <div class="col-md-12" style="margin-bottom:10px;">
                        <table class="table table-borderless">
                            <tbody id="setfield">
                                <tr id="set1">
                                    <td style="vertical-align: middle;" id="settitle">
                                        <h5>เลือกชุดผ่าตัด</h5>
                                    </td>
                                    <td id="row">
                                        <select name="select1" id="select1" class="form-select form-select-lg">
                                            <?php
                                            $setsql = "SELECT set_id,set_name FROM standardsetor";
                                            if ($rs = mysqli_query($conn, $setsql)) {
                                                while ($row = mysqli_fetch_array($rs)) {
                                            ?>
                                                    <option value="<?php echo $row['set_id'] ?>">
                                                        <?php echo $row['set_name'] ?>
                                                    </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td id="bt">
                                        <a class="btn btn-primary btn-sm btnshow" data-bs-toggle="modal" data-bs-target="#modalId" onclick="esTable('select1')"><img src="img/table.png" width="30px" alt=""></a>
                                    </td>
                                    <td width="20%" id="bsetting">
                                        <a class="btn btn-info" onclick="addsetfield()">เพิ่มชุดข้อมูลผ่าตัด</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Modal Body -->
                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                    <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>รหัส</th>
                                                <th>ชื่อ</th>
                                                <th>ราคา/ชิ้น</th>
                                                <th>จำนวน</th>
                                                <th>รวม</th>
                                            </tr>
                                        </thead>
                                        <tbody id="setOR">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--interface Lab-->
                    <div class="col-md-12" style="margin-bottom:10px;">
                        <h5>ค่าแล็ปเอกซเรย์</h5><button class="btn btn-outline-info" onclick="morelabxray(null)">+</button>&nbsp;<label>เพิ่ม LAB&X-ray</label>&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-outline-success" id="newlab_btn" onclick="newlabxray()">+</button>&nbsp;<label id="newlab_label">สร้าง LAB&X-ray ใหม่</label>
                        <table class="table table-borderless w-100">
                            <tbody id="labbody">
                                <tr style="display: none;background-color:#DBD3D1;" id="newlab_form">
                                    <td width="20%">
                                        <select class="form-select form-select-md" name="" id="type">
                                            <option value="LAB">LAB</option>
                                            <option value="X-RAY">X-RAY</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <input type="text" class="form-control" id="name" placeholder="ชื่อรายการ">
                                    </td>
                                    <td width="20%">
                                        <input type="number" class="form-control" id="cost" placeholder="ราคา">
                                    </td>
                                    <td width="10%">
                                        <button class="btn btn-success w-100" onclick="eventbtn()" id="addbtn">+ เพิ่มรายการ</button>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <!--ค่าบริการอื่นๆ-->
                    <div class="col-md-12" style="margin-bottom:10px;">
                        <h5>ค่าบริการอื่นๆ &nbsp;&nbsp;<a onclick="genNewitem()" class="btn btn-outline-success ">เพิ่มรายการค่าบริการ</a></h5>
                        <div class="row" id="other_cost">
                            <!--interface หอพักผู้ป่วย-->
                            <div class="col-md-12" id="rest">
                                <button class="btn btn-light" onclick="showtb(this,'rest_table')">
                                    <input type="checkbox" id="rest_c1" class="form-check-input" disabled>
                                    หอผู้ป่วย</button>
                                <table class="table table-borderless" id="rest_table" style="display:none;">
                                    <?php
                                    $sql = "SELECT * FROM item WHERE item_code = '21201_1' OR item_code = '21201_2'"; ///เพิ่มค่าห้องอื่นๆจาก DB อ้างอิงจากไอดี
                                    if ($res = mysqli_query($conn, $sql)) {
                                        $i = 0;
                                        while ($row = mysqli_fetch_array($res)) {
                                            $i++;
                                    ?>
                                            <tr style="vertical-align: middle;text-align:left;" id="<?php echo "rest_item" . $i ?>">
                                                <td></td>
                                                <td width="5%">
                                                    <input type="checkbox" value="<?php echo $row['item_code'] ?>" id="<?php echo "rest" . $i ?>" onclick="data_interface('<?php echo 'rest_item' . $i ?>',0)" class="form-check-input">
                                                </td>
                                                <td width="20%">
                                                    <label><?php echo $row['item_name'] ?> </label>
                                                </td>
                                                <td>
                                                    <label> <?php echo $row['item_unitprice'] . " บาท" ?> </label>
                                                </td>

                                                <td style="text-align:center;">จำนวนต่ำสุด</td>
                                                <td width="15%"><input type="number" min="1" id="<?php echo 'rest_date_min' . $i ?>" class="form-control w-100" disabled></td>
                                                <td>คืน</td>
                                                <td style="text-align:center;">จำนวนสูงสุด</td>
                                                <td width="15%"><input type="number" min="1" id="<?php echo 'rest_date_max' . $i ?>" class="form-control w-100" disabled></td>
                                                <td>คืน</td>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </div>
                            <!--interface ห้องผ่าตัด-->
                            <div class="col-md-12" id="room">
                                <button class="btn btn-light" style="margin-top: 10px;" onclick="showtb(this,'orroom_table')">
                                    <input type="checkbox" id="room_check" onclick="" class="form-check-input" disabled>
                                    ห้องผ่าตัด
                                </button>
                                <table class="table table-borderless" id="orroom_table" style="display:none;">
                                    <?php
                                    $sql = "SELECT * FROM item WHERE item_code = 'ORR001' OR item_code = 'ORR002'"; ///เพิ่มค่าห้องอื่นๆจาก DB อ้างอิงจากไอดี
                                    if ($res = mysqli_query($conn, $sql)) {
                                        $i = 0;
                                        while ($row = mysqli_fetch_array($res)) {
                                            $i++;
                                    ?>
                                            <tr style="vertical-align: middle;text-align:left;" id="<?php echo "room_item" . $i ?>">
                                                <td></td>
                                                <td width="5%">
                                                    <input type="checkbox" value="<?php echo $row['item_code'] ?>" id="<?php echo "room" . $i ?>" onclick="data_interface('<?php echo 'room_item' . $i ?>',0)" class="form-check-input">
                                                </td>
                                                <td width="20%">
                                                    <label><?php echo $row['item_name'] ?> </label>
                                                </td>
                                                <td>
                                                    <label> <?php echo $row['item_unitprice'] . " บาท" ?> </label>
                                                </td>

                                                <td style="text-align:center;">จำนวนต่ำสุด</td>
                                                <td width="15%"><input type="number" min="1" id="<?php echo 'room_date_min' . $i ?>" class="form-control w-100" disabled></td>
                                                <td>ครั้ง</td>
                                                <td style="text-align:center;">จำนวนสูงสุด</td>
                                                <td width="15%"><input type="number" min="1" id="<?php echo 'room_date_max' . $i ?>" class="form-control w-100" disabled></td>
                                                <td>ครั้ง</td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </div>
                            <!--interface ค่าบริการที่ต้องการเพิ่มใหม่-->
                            <div class="col-md-12" id="newdiv">

                                <table id="newitem_tb">
                                    <tbody id="newbody">
                                        <tr style="display:none;">
                                            <td width="5%"><input type="checkbox" id="other_c" onclick="" class="form-check-input">&nbsp;</td>
                                            <td width="10%" style="text-align:center;">ชื่อรายการ</td>
                                            <td width="20%"><input type="text" class="form-control w-100"></td>
                                            <td width="5%" style="text-align:center;">จำนวน</td>
                                            <td width="20%"><input type="number" min="1" class="form-control w-100"></td>
                                            <td width="10%" style="text-align:center;">ราคา/ชิ้น</td>
                                            <td width="20%"><input type="number" min="0" class="form-control w-100"></td>
                                            <td width="10%"><button class="btn btn-danger">ยกเลิก</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <center>
                        <input type="submit" onclick="sendData()" name="send" class="btn btn-success w-25" value="บันทึก">
                        <a href="Estimate.php" class="btn btn-warning w-25">รีเช็ต</a>
                    </center>
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
    //libery


    function Table_to_Json(id_table) { //ตารางเป็นไฟล์ JSON
        var table = document.getElementById(id_table);
        var result = [];
        var rows = table.rows;
        var cells;
        var iLen;
        for (var i = 0, iLen = rows.length; i < iLen; i++) {
            cells = rows[i].cells;
            t = [];

            for (var j = 0, jLen = cells.length; j < jLen; j++) {
                t.push(cells[j].textContent);
            }
            result.push(t);
        }
        return result;
    }

    function idisabled(nc, table) { ///disable เมื่อค่าบริการใหม่ถูกเช็ค
        var tab = document.getElementById(table);
        var tb = tab.getElementsByTagName('input');
        // console.log(tb);
        if (nc.checked == true) {
            tb[1].disabled = true;
            tb[2].disabled = true;
            tb[3].disabled = true;
        } else {
            tb[1].disabled = false;
            tb[2].disabled = false;
            tb[3].disabled = false;
        }
    }


    function genNewitem() { ////เพิ่มช่องใส่ค่าบริการใหม่
        var tb = document.getElementById('newitem_tb');
        var simple = document.querySelectorAll('#newbody tr');
        var otherid;
        if (simple.length <= 1) {
            otherid = simple.length + 1;
        } else {
            var lastid = simple[simple.length - 1].id;
            var numb = lastid.match(/\d/g);
            numb = numb.join("");
            otherid = Number(numb) + 1;

        }
        var clone = simple[0].cloneNode(true);
        var newid = "newitem" + otherid;
        var btn = clone.getElementsByTagName('button');
        var checkevent = clone.getElementsByTagName('input');
        checkevent[0].setAttribute('onclick', "idisabled(this,'" + newid + "')");
        btn[0].setAttribute('onclick', "delnew('" + newid + "')");
        clone.setAttribute('id', newid);
        clone.removeAttribute('style');
        var newbody = document.getElementById('newbody');
        innertag(newbody, clone);
    }

    function delnew(id) { ///ลบช่องค่าบริการใหม่
        document.getElementById(id).remove();
    }

    function createN(data) { //สร้าง Element
        return document.createElement(data);
    }

    function innertag(mom, child) { //innerHTML
        return mom.appendChild(child);
    }

    function getRadio(name) { //รับค่าจาก radio
        var radio_ch = document.getElementsByName(name);
        for (var i = 0; i < radio_ch.length; i++) {
            if (radio_ch[i].checked == true) {
                var radio_value = radio_ch[i].value;
            }
        }
        return radio_value;
    }

    function removeArray(data) { //ดักรายการซ้ำ
        return data.filter((value, index) => data.indexOf(value) === index);
    }

    function showtb(btn, table) { ///เช็คcheckbox
        //console.log(btn);
        var ch = btn.getElementsByTagName('input');
        //console.log(ch[0].checked);
        ch[0].checked = !ch[0].checked;
        displaytb(ch[0], table);
    }

    function displaytb(checkbox, table) { //เปิด/ปิดการมองเห็นรายละเอียด
        var tb = document.getElementById(table);
        //console.log(checkbox.checked);
        if (checkbox.checked == true) {
            tb.style.display = 'block';
        } else {
            tb.style.display = 'none';
        }
    }
    // active this page
    function addsetfield() { //เพิ่มรายการชุดผ่าตัด
        var datatable = document.querySelectorAll('#setfield tr');
        var rspan;
        if (datatable.length <= 1) {
            rspan = datatable.length + 1;
        } else {
            var lastid = datatable[datatable.length - 1].id;
            var numb = lastid.match(/\d/g);
            numb = numb.join("");
            rspan = Number(numb) + 1;
        }
        var spantxt = rspan.toString();
        var atag = document.createElement('a');
        atag.innerHTML = "ลบรายการ";
        atag.className = "btn btn-danger";
        atag.id = spantxt;
        atag.setAttribute("onclick", "delrow(" + spantxt + ")");
        var table = document.getElementById('setfield');
        var R1 = document.querySelectorAll('tbody #row')[0];
        var R2 = document.querySelectorAll('tbody #bt')[0];
        var tr = document.createElement('tr');
        var td = document.createElement('td');
        var demo = 'set' + rspan;
        tr.id = demo;
        var C1 = R1.cloneNode(true);
        var C2 = R2.cloneNode(true);
        td.appendChild(atag);
        tr.appendChild(C1);
        tr.appendChild(C2);
        tr.appendChild(td);
        document.getElementById("settitle").rowSpan = spantxt;
        table.appendChild(tr);
        var selectnew = document.getElementById(demo).getElementsByTagName('select');
        var sdemo = 'select' + spantxt;
        selectnew[0].id = sdemo;
        selectnew[0].setAttribute("name", sdemo);
        var showtb = document.getElementById(demo).getElementsByTagName('a');
        //showtb[0].removeAttribute('onclick');
        showtb[0].setAttribute("onclick", "esTable(`" + sdemo + "`)");
    }

    function delrow(data) { //ลบรายการผ่าตัด
        var trdel = document.getElementById('set' + data);
        var table = document.getElementById('setfield');
        table.removeChild(trdel);
    }

    function morelabxray(session) { //เพิ่มรายการชุดผ่าแล็ปเอกซเรย์
        var labtable = document.getElementById('labbody');
        var lab_data = document.querySelectorAll('#labbody tr');
        var setlabid = "";
        if (lab_data.length <= 1) {
            setlabid = lab_data.length + 1;
        } else {
            var lastid = lab_data[lab_data.length - 1].id;
            console.log(lastid);
            var numb = lastid.match(/\d/g);
            numb = numb.join("");
            setlabid = Number(numb) + 1;
        }
        var selecturl = "";
        if (session != null) {
            var newses = session;
        }
        var clone = createN('select');
        clone.setAttribute('class', 'form-select');
        var td1 = createN('td');
        var tr = document.createElement('tr');
        var td2 = document.createElement('td');
        var btn = document.createElement('a');
        btn.innerHTML = "ลบรายการ";
        btn.className = "btn btn-danger";
        let tr_id = "labxray" + setlabid.toString();
        tr.setAttribute("id", tr_id);

        btn.setAttribute("onclick", "dellab(" + setlabid + ")");
        innertag(td1, clone);
        td2.appendChild(btn);
        td2.width = '20%';
        tr.appendChild(td1);
        tr.appendChild(td2);
        labtable.appendChild(tr);
        var selectlab = document.getElementById(tr_id).getElementsByTagName('select');

        var selectID = 'labsl' + setlabid.toString();
        selectlab[0].setAttribute('name', selectID);
        selectlab[0].id = selectID;

        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                selectlab[0].innerHTML = this.response;
            }
        };
        if (session != null) {
            selecturl = "path.php?select='true'&newses=" + session;
        } else {
            selecturl = "path.php?select='true'";
        }
        xml.open("GET", selecturl);
        xml.send();
    }
    let op = 0

    function newlabxray() { //interface ปุ่มเพื่มแล็ปเอกซเรย์
        var newlab_btn = document.getElementById("newlab_btn");
        var newlab_label = document.getElementById("newlab_label");
        if (op == 0) {
            document.getElementById("newlab_form").style.display = 'block';
            newlab_btn.setAttribute("class", "btn btn-danger");
            newlab_btn.innerHTML = "-";
            newlab_label.innerHTML = "&nbsp;ยกเลิกการสร้าง";
            op = 1;
        } else {
            document.getElementById("newlab_form").style.display = 'none';
            newlab_btn.setAttribute("class", "btn btn-success");
            newlab_btn.innerHTML = "+";
            newlab_label.innerHTML = "&nbsp;สร้าง LAB&X-ray ใหม่";
            op = 0;
        }
    }

    function eventbtn() { //บันทึกแล็ปเอกซเรย์ to base
        var name = document.getElementById('name').value;
        var cost = document.getElementById('cost').value;
        var new_id;
        if (name && cost) {
            var type = document.getElementById('type').value;
            var xml = new XMLHttpRequest();
            xml.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    morelabxray(null);
                    new_id = this.responseText;
                    /*var optionall = selectall[cs - 1].getElementsByTagName("option");
                    let co = optionall.length;
                    optionall[co - 1].selected = true;*/

                    var form = document.getElementById('newlab_form');
                    var input_f = form.getElementsByTagName("input");
                    for (var i = 0; i < input_f.length; i++) {
                        input_f[i].value = "";
                    }
                    document.getElementById("newlab_form").style.display = 'none';
                    newlab_btn.setAttribute("class", "btn btn-success");
                    newlab_btn.innerHTML = "+";
                    newlab_label.innerHTML = "&nbsp;สร้าง LAB&X-ray ใหม่";
                    op = 0;
                }
            }
            xml.open("GET", "path.php?name=" + name + "&cost=" + cost + "&type=" + type + "&go=0", true);
            xml.send();


        } else {
            alert('กรุณากรอกข้อมูลให้ครบ');
        }

    }

    function dellab(data) { //ลบรายการแล็ปเอกซเรย์
        var trdel = document.getElementById('labxray' + data);
        var table = document.getElementById('labbody');
        table.removeChild(trdel);
    }
    $(".privacy").click(function() { //สิทธิอื่นๆ
        var other = document.getElementById('other');
        var text = document.getElementById('othertxt');
        if (other.checked == true) {
            text.style.display = 'block';
        }
        if (other.checked == false) {
            text.value = ""
            text.style.display = 'none';
        }
    });

    function esTable(data) { //โชว์ชุดไอเทมของชุดผ่าตัด
        var value = document.getElementById(data).value;
        var table = document.getElementById('setOR');

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                table.innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "path.php?value=" + value, true);
        xmlhttp.send();
    }

    function data_interface(checkbox, setamount1,setamount2) { //interface disabled form
        var data_row = document.getElementById(checkbox);
        var amount1 = 1;
        var amount2 = 1;
        if (setamount1 != 0) {
            amount1 = setamount1;
        }
        if (setamount2 != 0) {
            amount2 = setamount2;
        }
        var data_mark = data_row.getElementsByTagName('input');
        var data_select = data_row.getElementsByTagName('select');
        if (data_mark[0].checked == true) {
            data_mark[1].disabled = false;
            data_mark[1].value = amount1;
            data_mark[2].disabled = false;
            data_mark[2].value = amount2;

        } else {
            data_mark[1].disabled = true;
            data_mark[1].value = null;
            data_mark[2].disabled = true;
            data_mark[2].value = null;
        }
    }

    function sendData() { //บันทึกข้อมูลทั้งหมด
        var privacy = uc_id;
        var or_value = sendOR();
        var lab_value = sendLab();
        var rest_value = sendrest();
        var room_value = sendroom();
        var other_value = sendother();
        var diagnose = document.getElementById('diagnose').value;
        /* console.log(privacy);
         console.log(or_value);
         console.log(lab_value);
         console.log(rest_value);
         console.log(room_value);
         console.log(other_value);*/
        if (getId != null) {
            warnning = "เอกสารฉบับนี้อ้างอิงจากรหัส " + getId;
        }
        var bigdata = {
            'privacy': privacy,
            'diagnose': diagnose,
            'or_value': or_value,
            'lab_value': lab_value,
            'rest_value': rest_value,
            'room_value': room_value,
            'other_value': other_value,
            'warning': warnning
        };
        //console.log(bigdata);
        var j = JSON.stringify(bigdata);
        if (privacy && or_value && lab_value && rest_value && room_value && other_value[0] != "ERROR") {
            window.location.href = "path.php?j=" + j + "&hndoc=" + ses;
        } else {
            alert("กรุณากรอกข้อมูลให้ครบ");
        }
    }

    function sendOR() { //จัดเก็บข้อมูล ชุดผ่าตัด เป็น JSON

        var or_list = document.getElementById('setfield');
        var select_list = or_list.getElementsByTagName('select')

        var c_list = select_list.length;
        var or_value = [];
        for (var i = 0; i < c_list; i++) {
            or_value[i] = select_list[i].value;

        }
        if (or_value.length > 1) {
            or_value = removeArray(or_value);
        }
        return or_value;
    }

    function sendLab() { //จัดเก็บข้อมูล ชุดแล็ปและเอ็กซเรย์ เป็น JSON

        var lab_list = document.getElementById('labbody');
        var select_list = lab_list.getElementsByTagName('select');

        var c_list = select_list.length - 1;
        var lab_value = [];
        for (var i = 0; i < c_list; i++) {
            lab_value[i] = select_list[i + 1].value;
        }
        if (lab_value.length > 1) {
            lab_value = removeArray(lab_value);
        }
        return lab_value;
    }

    function sendrest() { //จัดเก็บข้อมูล หอพักผู้ป่วย เป็น JSON

        var rest_check = document.querySelectorAll('#rest input[type=checkbox]');
        var rest_tr = document.querySelectorAll('#rest_table tr');


        var rest_data = [];
        //console.log(rest_check);
        if (rest_check[0].checked == true) {
            var num = 0;
            for (var i = 1; i < rest_check.length; i++) {
                var findid = rest_tr[i - 1].id;
                var rest_date_min = document.getElementById("rest_date_min" + i);
                var rest_date_max = document.getElementById("rest_date_max" + i);
                var rest_text = document.querySelectorAll("#" + findid + " label");
                //console.log(i);
                if (rest_check[i].checked == true) {
                    rest_data[num] = {
                        "id": rest_check[i].value,
                        "min_restday": rest_date_min.value,
                        "max_restday": rest_date_max.value
                    };
                    num++;
                }
            }
        }
        return rest_data;
    }

    function sendroom() { //จัดเก็บข้อมูล ห้องผ่าตัด เป็น JSON
        var room_check = document.querySelectorAll('#room input[type=checkbox]');
        var room_tr = document.querySelectorAll('#orroom_table tr');


        var room_data = [];

        if (room_check[0].checked == true) {
            var num = 0;
            for (var i = 1; i < room_check.length; i++) {
                var roomid = room_tr[i - 1].id;
                var room_date_min = document.getElementById("room_date_min" + i);
                var room_date_max = document.getElementById("room_date_max" + i);
                var room_text = document.querySelectorAll("#" + roomid + " label");
                // console.log(i);
                if (room_check[i].checked == true) {
                    room_data[num] = {
                        "id": room_check[i].value,
                        "min_amount": room_date_min.value,
                        "max_amount": room_date_max.value
                    };
                    num++;
                }
            }
        }
        return room_data;
    }

    function sendother() { //จัดเก็บข้อมูล ค่าบริการเพิ่มเติม เป็น JSON
        var tb = document.getElementById('newitem_tb');
        var other_tr = document.querySelectorAll("#newitem_tb tr");
        var other_value = [];
        for (var i = 1; i < other_tr.length; i++) {
            var tr_id = other_tr[i].id;
            var data = document.querySelectorAll("#" + tr_id + " input");

            if (data[0].checked == true) {
                if (data[1].value && data[3].value && data[2].value) {
                    // console.log(i);
                    other_value[i - 1] = {
                        "name": data[1].value,
                        "price": data[3].value,
                        "amount": data[2].value
                    }
                } else {
                    other_value[0] = "ERROR";
                    alert("กรุณากรอกข้อมูลค่าบริการให้ครบ");
                }

            }
        }
        return other_value;
    }
    /////duplicate setting////
    if (getId != null) { /// เช็คว่ามีการส่งค่าจะเอกสารเก่า หรือไม่
        ///privacy settings
        <?php if (isset($sesprivacy)) { ?>
            sesprivacy = "<?php echo $sesprivacy; ?>";
        <?php   } ?>
      /*  if (sesprivacy != "") {
            var p_btn = p_box.querySelectorAll("#p_box input[name='privacy']");
            for (var i = 0; i < p_btn.length; i++) {
                if (p_btn[i].value == sesprivacy) {
                    p_btn[i].checked = true;
                }
            }
        }*/
        ///item_set settings
        <?php if (isset($sesprivacy)) { ?>
            var sesset = '<?php echo $sesset; ?>'
        <?php   } ?>
        sesset = JSON.parse(sesset);
        var setfield = document.querySelectorAll("#setfield tr");
        while (setfield.length != sesset.length) {
            addsetfield();
            setfield = document.querySelectorAll("#setfield tr");
        }
        for (var i = 0; i < setfield.length; i++) {
            var set_sl = document.getElementById('select' + (i + 1));
            var set_op = set_sl.getElementsByTagName('option');
            //console.log(set_sl);
            //console.log(set_op);
            for (var j = 0; j < set_op.length; j++) {
                if (set_op[j].value == sesset[i]['item_set']) {
                    set_op[j].selected = true;
                }
            }
        }
        ///lab/x-ray_set settings
        <?php if (isset($sesLab)) { ?>
            var sesLab = '<?php echo $sesLab; ?>';
        <?php   } ?>
        sesLab = JSON.parse(sesLab);
        //console.log(sesLab);
        var labbody = document.querySelectorAll("#labbody tr");
        var xml = [];
        for (var i = 0; i < sesLab.length; i++) {
            var lab_sl = sesLab[i]['Item_id'];
            //console.log(lab_sl);
            morelabxray(lab_sl);
        }
        ///restroom settings
        <?php if (isset($sesrest)) { ?>
            var sesrest = '<?php echo $sesrest; ?>';
        <?php   } ?>
        sesrest = JSON.parse(sesrest);
        var restdiv = document.querySelectorAll('#rest input[type=checkbox]');
        var rbtn = document.querySelectorAll('#rest button')[0];
        if (sesrest != "") {
            showtb(rbtn, 'rest_table');
            for (var i = 0; i < sesrest.length; i++) {
                for (var j = 1; j < restdiv.length; j++) {
                    if (restdiv[j].value == sesrest[i]['Item_id']) {
                        restdiv[j].checked = true;
                        data_interface('rest_item' + j, sesrest[i]['Item_min_amount'], sesrest[i]['Item_max_amount']);
                    }
                }
            }
        }
        ///ORroom settings
        <?php if (isset($sesOR)) { ?>
            var sesOR = '<?php echo $sesOR; ?>';
        <?php   } ?>
        sesOR = JSON.parse(sesOR);
        var ORdiv = document.querySelectorAll('#room input[type=checkbox]');
        var obtn = document.querySelectorAll('#room button')[0];
        if (sesOR != "") {
            showtb(obtn, 'orroom_table');
            for (var i = 0; i < sesOR.length; i++) {
                console.log(ORdiv[i + 1].value);
                console.log(sesOR[i]['Item_id']);
                for (var j = 1; j < ORdiv.length; j++) {
                    if (ORdiv[j].value == sesOR[i]['Item_id']) {
                        ORdiv[j].checked = true;
                        data_interface('room_item' + j, sesOR[i]['Item_min_amount'], sesOR[i]['Item_max_amount']);
                    }
                }
            }
        }
        ///Other settings
        <?php if (isset($sesOther)) { ?>
            var sesOther = '<?php echo $sesOther; ?>';
        <?php   } ?>
        sesOther = JSON.parse(sesOther);
        //console.log(sesOther);
        if (sesOther != "") {
            var setfield = document.querySelectorAll("#newbody tr");
            while (setfield.length != sesOther.length + 1) {
                genNewitem();
                setfield = document.querySelectorAll("#newbody tr");
            }
            for (var i = 0; i < sesOther.length; i++) {

                var other_line = document.querySelectorAll("#newitem" + (i + 2) + " input");
                // console.log(other_line);
                var other_name = sesOther[i]['Item_name'];

                while (other_name.search("&#039;") > 0 || other_name.search("&quot;") > 0) {
                    other_name = other_name.replace("&#039;", "\'");
                    other_name = other_name.replace("&quot;", "\"");
                }

                other_line[1].value = other_name;
                other_line[2].value = sesOther[i]['Item_amount'];
                other_line[3].value = sesOther[i]['item_price'];
                other_line[0].checked = true;
                idisabled(other_line[0], setfield[i + 1].id)

            }
        }
    }
</script>

</html>