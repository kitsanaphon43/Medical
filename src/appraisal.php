<?php
include("connect.php");
session_start();
if (!isset($_GET['doc_id'])) {
    // header('Location:index.php');
} else {
    $doc_id = $_GET['doc_id'];

    $docsql = "SELECT `doc_id`, `doc_no`, uc.uc_name, `HN_id`
    , `doc_min_total`, `doc_max_total`, `doc_date`, `doc_p_total`,`doc_noti` FROM `docestimate` 
    LEFT JOIN uc ON docestimate.doc_privacy = uc.uc_id 
    WHERE doc_id='" . $doc_id . "'";
    if ($result = mysqli_query($conn, $docsql)) {
        $row = mysqli_fetch_assoc($result);
        $privacy = $row['uc_name'];
        $doc_no = $row['doc_no'];
        $doc_p_total = $row['doc_p_total'];
        $warning = $row['doc_noti'];
    }
    $sheet = $doc_id . '_' . $doc_no;
    $hnsql = "SELECT `HN_id`, `visit_name`, `visit_iden`
 , `visit_address`, `visit_tel`, `uc_id`FROM visit 
 WHERE HN_id = '" . $_SESSION['HN'] . "';";
    if ($res = mysqli_query($conn, $hnsql)) {
        while ($row = mysqli_fetch_array($res)) {
            $HN_id = $row['HN_id'];
            $visit_name = $row['visit_name'];
            $visit_address = $row['visit_address'];
            $visit_iden = $row['visit_iden'];
            $visit_tel = $row['visit_tel'];
            $visit_uc = $row['uc_id'];
        }
    }
    $setsql = "SELECT DISTINCT `item_set`,s.set_name FROM `docdetail` LEFT JOIN standardsetor s ON `item_set` = s.set_id WHERE doc_id='" . $doc_id . "'";
    if ($rs = mysqli_query($conn, $setsql)) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $set[] = $row['item_set'];
            $set_name[] = $row['set_name'];
        }
    }
    $sname = "";
    for ($r = 0; $r < count($set_name); $r++) {
        if ($set_name[$r] != "" && $r != 0) {
            $sname .= " , ";
        }
        $sname .= $set_name[$r];
    }

    for ($i = 0; $i < count($set); $i++) {
        $itemsql = "SELECT `Item_id`
                            , `Item_name`, `Item_type`, `Item_min_amount`, `Item_max_amount`
                            , `item_price`, `item_uc_price`, `item_ofc_price`, `item_ss_price`
                            , `detail_min_total`, `detail_max_total`,d.doc_min_total,d.doc_max_total,d.doc_name FROM `docdetail` LEFT JOIN docestimate d ON docdetail.doc_id = d.doc_id
                             WHERE docdetail.doc_id = '" . $doc_id . "' AND item_set ='" . $set[$i] . "';";
        if ($rs = mysqli_query($conn, $itemsql)) {
            while ($row = mysqli_fetch_assoc($rs)) {
                $item_id[$i][] = $row['Item_id'];
                $Item_name[$i][] = $row['Item_name'];
                $Item_type[$i][] = $row['Item_type'];
                $Item_min_amount[$i][] = $row['Item_min_amount'];
                $Item_max_amount[$i][] = $row['Item_max_amount'];
                $item_price[$i][] = $row['item_price'];
                $item_uc_price[$i][] = $row['item_uc_price'];
                $item_ofc_price[$i][] = $row['item_ofc_price'];
                $item_ss_price[$i][] = $row['item_ss_price'];
                $detail_min_total[$i][] = $row['detail_min_total'];
                $detail_max_total[$i][] = $row['detail_max_total'];
                $doc_min_total = $row['doc_min_total'];
                $doc_max_total = $row['doc_max_total'];
                $doc_name = $row['doc_name'];
            }
        }
    }
   // echo $doc_min_total ."/". $doc_p_total;
    $sumary_mincost = ($doc_min_total + $doc_p_total);
    $sumary_maxcost = ($doc_max_total + $doc_p_total);
    $num = 0;
   
} ?>
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
    <title>ใบประเมินค่ารักษาพยาบาล</title>

</head>

<body style="font-family: 'Noto Sans Thai', sans-serif;">
    <div class="container-fluid">
        <div class="row">
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
                                        <a class="dropdown-item ditem" href="register.php?editu=<?php echo $_SESSION['user_id']; ?>">แก้ไขข้อมูลส่วนตัว</a>
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

                    <a href="appraisal.php" class="now">
                        <div class="col-md-12">
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

            <div class="col-md-10" style="margin-top:20px;" id="sheet">
                <center>
                    <table class="table table-borderless" style="width:100%;">
                        <tbody id="idenTable">
                            <tr>
                                <td>รหัสใบประเมิน</td>
                                <td><?php echo $doc_id ?></td>
                                <td>ใบที่</td>
                                <td><?php echo $doc_no ?></td>
                            </tr>
                            <tr>
                                <td>HN</td>
                                <td><?php echo $HN_id ?></td>
                                <td>AN</td>
                                <td><?php echo "-" ?></td>

                            </tr>
                            <tr>
                                <td>ชื่อ-นามสกุล</td>
                                <td><?php echo $visit_name ?></td>
                                <td>การวินิจฉัยโรค</td>
                                <td><?php echo $doc_name ?></td>

                            </tr>
                            <tr>
                                <td>การผ่าตัด</td>
                                <td><?php echo $sname ?></td>
                                <td>สิทธิ์</td>
                                <td><?php echo $privacy ?></td>

                            </tr>
                            <tr>
                                <td>ICD9</td>
                                <td><?php echo "-" ?></td>
                                <td>ICD10</td>
                                <td><?php echo "-" ?></td>
                            </tr>
                        </tbody>
                    </table>
                </center>

                <center>
                    <table id="summary" class="table table-borderless" style="width:100%;">
                        <thead style="text-align: center;">
                            <tr>


                            <tr>
                                <th width="1%" rowspan="2">ลำดับ</th>
                                <th width="20%" rowspan="2">รายการ</th>
                                <th width="2%" rowspan="2">ราคาต่อชิ้น</th>
                                <th width="5%" colspan="2">จำนวน</th>
                                <th width="5%" colspan="2">ราคา </th>
                                <th width="5%" rowspan="2">เบิกได้</th>
                            </tr>
                            <tr>
                                <th width="2%">ต่ำสุด</th>
                                <th width="2%">สูงสุด </th>
                                <th width="2%">ต่ำสุด</th>
                                <th width="2%">สูงสุด </th>
                              
                        
                            </tr>
                            </th>
                            </tr>
                        </thead>

                        <tbody id="history">
                            <?php
                            for ($i = 0; $i < count($set); $i++) { ?>
                                <tr>
                                    <?php if ($set_name[$i] != '') { ?>
                                        <td colspan="9" id="head">
                                            <?php echo 'ชุดผ่าตัด ' . $set_name[$i] ?>
                                        </td>
                                    <?php } else { ?>
                                        <td colspan="9" id="head">
                                            <?php echo "ค่าจิปาถะและค่าบริการอื่นๆ" ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                                <?php
                                for ($a = 0; $a < count($item_id[$i]); $a++) {
                                    $num++;
                                    
                                    if($visit_uc == "UC002"){
                                        ?>
                                        <script>
                                              var uc_cost = <?php echo  $item_uc_price[$i][$a]; ?>;
                                        </script>
                                        <?php
                                    }else if($visit_uc == "UC003"){
                                        ?>
                                        <script>
                                              var uc_cost = <?php echo  $item_ofc_price[$i][$a]; ?>;
                                        </script>
                                        <?php
                                    }else if($visit_uc == "UC004"){
                                        ?>
                                        <script>
                                              var uc_cost = <?php echo  $item_ss_price[$i][$a]; ?>;
                                        </script>
                                        <?php
                                    }else{
                                        ?>
                                        <script>
                                              var uc_cost = 0;
                                        </script>
                                        <?php
                                    }
                                ?>
                                    <script>
                                        
                                        var i_price = <?php echo  $item_price[$i][$a]; ?>;
                                        var min_total = <?php echo $detail_min_total[$i][$a]; ?>;
                                        var max_total = <?php echo $detail_max_total[$i][$a]; ?>;
                                        var d_mintotal = <?php echo  $doc_min_total; ?>;
                                        var d_maxtotal = <?php echo  $doc_max_total; ?>;
                                        var dp_mintotal = <?php echo  $sumary_mincost; ?>;
                                        var dp_maxtotal = <?php echo  $sumary_maxcost; ?>;
                                        var doc_p_total = <?php echo  $doc_p_total; ?>;
                                        uc_cost = uc_cost.toLocaleString();
                                        i_price = i_price.toLocaleString();
                                        min_total = min_total.toLocaleString();
                                        max_total = max_total.toLocaleString();
                                        d_mintotal = d_mintotal.toLocaleString();
                                        d_maxtotal = d_maxtotal.toLocaleString();
                                        dp_mintotal = dp_mintotal.toLocaleString();
                                        dp_maxtotal = dp_maxtotal.toLocaleString();
                                        doc_p_total = doc_p_total.toLocaleString();
                                    </script>
                                    <tr>
                                        <td style="text-align:center;">
                                            <?php echo $num ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($Item_type[$i][$a] == 'LAB' || $Item_type[$i][$a] == 'X-RAY') {
                                                echo $Item_type[$i][$a] . ":" . $Item_name[$i][$a];
                                            } else {
                                                echo $Item_name[$i][$a];
                                            }

                                            ?>
                                        </td>
                                        <td style="text-align:right">
                                            <script>
                                                document.write(i_price);
                                            </script>
                                        </td>
                                        <td style="text-align:right">
                                            <?php echo $Item_min_amount[$i][$a] ?>
                                        </td>
                                        <td style="text-align:right">
                                            <?php echo $Item_max_amount[$i][$a] ?>
                                        </td>
                                        <td style="text-align:right">
                                            <script>
                                                document.write(min_total);
                                            </script>
                                        </td>
                                        <td style="text-align:right">
                                            <script>
                                                document.write(max_total);
                                            </script>
                                        </td>
                                        <td style="text-align:right">
                                            <script>
                                                document.write(uc_cost);
                                            </script>
                                        </td>
                                      
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                            <tr style="border-top: 1px solid gray">
                                <td colspan="5" style="text-align: right;">ราคารวมประมาณ</td>
                                <td style="text-align: right;">
                                    <script>
                                        document.write(d_mintotal + " บาท")
                                    </script>
                                </td>
                                <td style="text-align: right;">
                                    <script>
                                        document.write(d_maxtotal + " บาท")
                                    </script>
                                </td>
                                <td style="text-align: right;">
                                    <script>
                                        document.write(doc_p_total + " บาท")
                                    </script>
                                </td>
                            </tr>
                            
                           
                        </tbody>
                        <tfoot>
                            <tr></tr>
                        </tfoot>
                    </table>
                </center>
                
                <?php echo "*" . $warning ?>
                <center id="signature" style="display: none;">
               
                <?php echo "<br><nav style='margin-left:20px;'>ราคาประมาณ&nbsp;&nbsp;&nbsp; <script>document.write(d_mintotal)</script>- <script>document.write(d_maxtotal)</script>&nbsp;&nbsp;บาท</nav><br>" ?>
    
                <table class="table table-borderless w-100 mt-5">

                        <tbody>

                            <tr>
                                <td>ลงชื่อ ............................. ผู้ป่วย/ผู้แทนโดยชอบธรรม</td>
                                <td>ลงชื่อ ................................ เจ้าหน้าที่ผู้ให้ข้อมูล</td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;(....................................)</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(....................................)</td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;ลงชื่อ ......../......../.........</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ ......../......../.........</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="color:red">หมายเหตุ : ค่ารักษาพยาบาลรวมดังกล่าวเป็นเพียงการประเมินค่ารักษาพยาบาลเบื้องต้น การเข้ารับบริการจริงอาจมีการเปลี่ยนแปลง</p>
                </center>
            </div>

            <center>
                <input type="submit" onclick="sendData()" id="send" name="send" class="btn btn-success w-25" value="พิมพ์" style="background-color:#02006c" ;>

            </center>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
<script>
    function sendData() {
        var title = document.title;
        var signature = document.getElementById('signature');
        signature.style.display = 'block';
        document.title = "โรงพยาบาลศูนย์การแพทย์มหาวัทยาลัยแม่ฟ้าหลวง"
        window.print();
        setTimeout(() => {
            signature.style.display = 'none';
            document.title = title;
        }, "0");

    }
</script>

</html>