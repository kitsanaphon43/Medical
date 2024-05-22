<?php
include("connect.php");
session_start();
if (!isset($_GET['doc_id'])) {
    header('Location:index.php');
} else {
    $doc_id = $_GET['doc_id'];
    
    $docsql = "SELECT `doc_id`, `doc_no`, uc.uc_name, `HN_id`
    , `doc_total`, `doc_date`, `doc_p_total`,`doc_noti` FROM `docestimate` 
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
 , `visit_address`, `visit_tel`FROM visit 
 WHERE HN_id = '" . $_SESSION['HN'] . "';";
    if ($res = mysqli_query($conn, $hnsql)) {
        while ($row = mysqli_fetch_array($res)) {
            $HN_id = $row['HN_id'];
            $visit_name = $row['visit_name'];
            $visit_address = $row['visit_address'];
            $visit_iden = $row['visit_iden'];
            $visit_tel = $row['visit_tel'];
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
        if($set_name[$r] != "" && $r != 0) {
            $sname .= " , ";
        }  
        $sname .= $set_name[$r];
    }

    for ($i = 0; $i < count($set); $i++) {
        $itemsql = "SELECT `Item_id`
                            , `Item_name`, `Item_type`, `Item_amount`
                            , `item_price`, `item_uc_price`, `item_ofc_price`
                            , `detail_total`,d.doc_total,d.doc_name FROM `docdetail` LEFT JOIN docestimate d ON docdetail.doc_id = d.doc_id
                             WHERE docdetail.doc_id = '" . $doc_id . "' AND item_set ='" . $set[$i] . "';";
        if ($rs = mysqli_query($conn, $itemsql)) {
            while ($row = mysqli_fetch_assoc($rs)) {
                $item_id[$i][] = $row['Item_id'];
                $Item_name[$i][] = $row['Item_name'];
                $Item_type[$i][] = $row['Item_type'];
                $Item_amount[$i][] = $row['Item_amount'];
                $item_price[$i][] = $row['item_price'];
                $item_uc_price[$i][] = $row['item_uc_price'];
                $item_ofc_price[$i][] = $row['item_ofc_price'];
                $detail_total[$i][] = $row['detail_total'];
                $doc_total = $row['doc_total'];
                $doc_name = $row['doc_name'];
            }
        }
    }
    $sumarycost = $doc_total - $doc_p_total  ;
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
            <div class="col-md-12" style="background-color:rgb(69, 158, 214);color:white">
                <h2 style="text-align: center;margin-top:20px;">ใบประเมินค่ารักษาพยาบาล</h2>
            </div>

            <div class="col-md-2" style="margin-top:20px;" id="menu">
                <div class="row">
                    <a href="index.php">
                        <div class="col-md-12 choosed">
                            หน้าแรก
                        </div>
                    </a>
                    <a href="estimate.php" class="disabled">
                        <div class="col-md-12 choosed">
                            เพิ่มใบประเมินราคา
                        </div>
                    </a>
                    <a href="appraisal.php" class="disabled">
                        <div class="col-md-12 now">
                            <img src="img/print.png" width="30px;" alt=""> พิมพ์ใบประเมินราคา
                        </div>
                    </a>
                    <center>
                        <hr style="width:100px;">
                    </center>
                    <a href="setmanager.php">
                        <div class="col-md-12 choosed">
                            จัดการชุดผ่าตัด
                        </div>
                    </a>
                    <a href="lab_xray.php">
                        <div class="col-md-12 choosed">
                            จัดการชุด LAB แล็ปและเอกซเรย์
                        </div>
                    </a>
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
                                <th width="1%">ลำดับ</th>
                                <th width="15%">รายการ</th>
                                <th width="2%">ราคาต่อชิ้น</th>
                                <th width="1%">จำนวน</th>
                                <th width="2%">จำนวนเงิน</th>
                            </tr>
                        </thead>

                        <tbody id="history">
                            <?php
                            for ($i = 0; $i < count($set); $i++) { ?>
                                <tr>
                                    <?php if ($set_name[$i] != '') { ?>
                                        <td colspan="6" id="head">
                                            <?php echo 'ชุดผ่าตัด ' . $set_name[$i] ?>
                                        </td>
                                    <?php } else { ?>
                                        <td colspan="6" id="head">
                                            <?php echo "ค่าจิปาถะและค่าบริการอื่นๆ" ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                                <?php
                                for ($a = 0; $a < count($item_id[$i]); $a++) {
                                    $num++;

                                ?>
                                    <script>
                                        var i_price = <?php echo  $item_price[$i][$a]; ?>;
                                        var i_total = <?php echo $detail_total[$i][$a]; ?>;
                                        var d_total = <?php echo  $doc_total; ?>;
                                        var dp_total= <?php echo  $sumarycost; ?>;
                                        var doc_p_total= <?php echo  $doc_p_total; ?>;
                                        i_price = i_price.toLocaleString();
                                        i_total = i_total.toLocaleString();
                                        d_total = d_total.toLocaleString();
                                        dp_total = dp_total.toLocaleString();
                                        doc_p_total = doc_p_total.toLocaleString();
                                    </script>
                                    <tr>
                                        <td style="text-align:center;">
                                            <?php echo $num ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if($Item_type[$i][$a] == 'LAB'|| $Item_type[$i][$a] == 'X-RAY'){
                                                   echo $Item_type[$i][$a].":".$Item_name[$i][$a];   
                                                }else{
                                                    echo $Item_name[$i][$a];
                                                }
                                               
                                            ?>
                                        </td>
                                        <td style="text-align:right">
                                            <script>
                                                document.write(i_price)
                                            </script>
                                        </td>
                                        <td style="text-align:right">
                                            <?php echo $Item_amount[$i][$a] ?>
                                        </td>
                                        <td style="text-align:right">
                                            <script>
                                                document.write(i_total)
                                            </script>
                                        </td>

                                    </tr>
                            <?php
                                }
                            }
                            ?>
                            <tr style="border-top: 1px solid gray">
                                <td colspan="4" style="text-align: right;">ราคารวมประมาณ</td>
                                <td style="text-align: right;">
                                    <script>
                                        document.write(d_total + " บาท")
                                    </script>
                                </td>

                            </tr>
                            <tr style="border-top: 1px solid gray">
                                <td colspan="4" style="text-align: right;">ราคาเมื่อใช้สิทธิเบิกได้</td>
                                <td style="text-align: right;">
                                <script>
                                        document.write(dp_total + " บาท")
                                    </script>
                                </td>

                            </tr>
                            <tr style="border-top: 1px solid gray">
                                <td colspan="4" style="text-align: right;">ราคาที่สิทธิเบิกไม่ได้</td>
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
                <?php echo "*".$warning?>
                <center id="signature" style="display: block;">
                  
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