<?php
include "connect.php";
session_start();
if (isset($_POST["case"]) || isset($_POST['rp'])) {
    $case = $_POST['case'];
    $pt = $_POST['pt'];
    $rp = $_POST['rp'];
    $sqlc = "SELECT COUNT('doc_id') as'docnum' FROM docestimate ;";
    $makeId = mysqli_query($conn, $sqlc) or die(mysqli_error($con));
    $row = mysqli_fetch_array($makeId);
    $MID = $row['hisnum'];
    $Id = 'HC000' . ($MID + 1);
    $sql = "INSERT INTO `docestimate`(`doc_id`, `doc_no`, `doc_date`, `hn_id`, `doc_uc`, `doc_total`)
         VALUES ('$Id','$case','$pt','$rp','ยังไม่ได้รับการยืนยัน')";
    if (mysqli_query($conn, $sql)) {
        header("location:index.php");
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM  `docestimate` WHERE `doc_id` = '$id'";
    if (mysqli_query($conn, $sql)) {
        header("location:index.php");
    }
}
if (isset($_GET['set_id'])) {
    $id = $_GET['set_id'];
    $sql = "SELECT it.item_code as iid
    ,it.item_name as iname
    ,s.std_amount as amount
    ,it.item_unitprice as iprice
    ,it.item_uc_price as item_uc
    ,it.item_ofc_price as item_ofc
    FROM set_detail s LEFT JOIN item it 
    ON s.Item_code = it.Item_code WHERE s.set_id ='" . $id . "'";

    if ($rs = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_array($rs)) {
            $amount = htmlentities($row['amount']);
            $once_p = htmlentities($row['iprice']);
            $price = $once_p * $amount;
            $price = htmlentities($price);

            echo '<tr>';
            echo "<td> <center><input class='form-check-input' type='checkbox'id='check" . $row['iid'] . "'></center></td>";
            echo '<td>' . $row['iid'] . '</td>';
            echo '<td>' . $row['iname'] . '</td>';
            echo "<td ><input type='number' class='form-control' id='quality" . $row['iid'] . "' onchange='price(`" . $row['iid'] . "`,this.value)' value ='" . $amount . "' min='1' ></td>";
            echo "<td style='text-align:right' id='unit" . $row['iid'] . "'>" . $once_p . "</td>";
            echo "<td style='text-align:right' id='cost" . $row['iid'] . "'>" . $price . "</td>";

            if ($row['item_uc'] != 0 || $row['item_ofc'] != 0) {
                echo "<td>เบิกได้ทั้งหมด</td>";
            } else {
                echo "<td>เบิกไม่ได้</td>";
            }
            echo '</tr>';
        }
    } else {
        print_r($rs);
    }
}
if (isset($_GET['hn_id'])) {
    $id = $_GET['hn_id'];
    $sqli = "SELECT v.HN_id as vid
    ,v.visit_name as vname,v.visit_address as vaddress
    ,v.visit_tel as vtel,v.visit_iden as viden ,u.uc_name as uname
     FROM visit v LEFT JOIN uc u ON v.uc_id = u.uc_id 
     WHERE HN_id = " . $id . "";
    if ($res = mysqli_query($conn, $sqli)) {
        while ($row = mysqli_fetch_array($res)) {
            echo       '<tr>';
            echo      '<td>HN</td>';
            echo       '<td>' . $row['vid'] . '</td>';
            echo       '<td>ชื่อ-นามสกุล</td>';
            echo     '<td>' . $row['vname'] . '</td>';
            echo  ' </tr>';
            echo   '<tr>';
            echo      '<td>ที่อยู่</td>';
            echo      '<td>' . $row['vaddress'] . '</td>';
            echo       '<td>บัตรประชาชน</td>';
            echo       '<td>' . $row['viden'] . '</td>';
            echo   '</tr>';
            echo   '<tr>';
            echo       '<td>เบอร์ติดต่อ</td>';
            echo       '<td>' . $row['vtel'] . '</td>';
            echo        '<td>สิทธิ์</td>';
            echo       '<td>' . $row['uname'] . '</td>';
            echo  '</tr>';
        }
    } else {
        print_r($rs);
    }
}
if (isset($_GET['search']) && isset($_GET['filter'])) {
    $f = $_GET['filter'];
    $search = $_GET['search'];

    if ($f == 'all') {

        $sqls = "SELECT * FROM item
        WHERE item_code  LIKE '%" . $search . "%' OR item_name LIKE '%" . $search . "%' ";
    } else {

        $sqls = "SELECT * FROM item
        WHERE item_code  LIKE '%" . $search . "%' AND item_category = '" . $f . "' OR item_name LIKE '%" . $search . "%' 
        AND item_category = '" . $f . "'";
    }
    if ($search == '*' && $f == 'all') {
        $sqls = "SELECT * FROM item";
    } else if ($search == "*" && $f != "all") {
        $sqls = "SELECT * FROM item WHERE item_category = '" . $f . "'";
    }

    if ($res = mysqli_query($conn, $sqls)) {
        while ($row = mysqli_fetch_array($res)) {
            echo "<tr>";
            echo "<td>" . $row['item_code'] . "</td>";
            echo "<td width='50%;'>" . $row['item_name'] . "</td>";
            echo "<td width='25%;'>" . $row['item_unitprice'] . "</td>";
            echo "<td><a onclick='adddata(`" . $row['item_code'] . "`)'  class='btn btn-primary'>เลือก</button></td>";
            echo "</tr>";
        }
    }
}
if (isset($_GET['item_id']) && isset($_GET['quality'])) {
    $quality = $_GET['quality'];
    $item_id = $_GET['item_id'];

    $sqls = "SELECT * FROM item
        WHERE item_code = '" . $item_id . "'";

    if ($res = mysqli_query($conn, $sqls)) {
        while ($row = mysqli_fetch_array($res)) {
            $uprice = $row['item_unitprice'];
            $price = $uprice * $quality;
            $newitem[0] = $row['item_code'];
            $newitem[1] = $row['item_name'];
            $newitem[2] = $uprice;
            if ($row['item_uc_price'] != 0 || $row['item_ofc_price'] != 0) {
                $newitem[3] = "เบิกได้ทั้งหมด";
            } else {
                $newitem[3] = "เบิกไม่ได้";
            }
            echo json_encode($newitem, JSON_UNESCAPED_UNICODE);
        }
    }
}
if (isset($_GET['itemid']) && isset($_GET['setid'])) {
    $setid = htmlentities($_GET['setid']);
    $itemid = htmlentities($_GET['itemid']);
    $len = $_GET['len'];
    $item_amount = $_GET['item_amount'];
    $itotal = $_GET['itotal'];
    $sql = "SELECT COUNT(item_code) as c_item FROM set_detail WHERE item_code = '" . $itemid . "' AND set_id = '" . $setid . "'";
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_array($res)) {
            echo "C = " . $row['c_item'];
            if ($row['c_item'] == 0) {
                $updatesql = "INSERT INTO set_detail(set_id,item_code,std_amount) VALUE ('" . $setid . "','" . $itemid . "','" . $item_amount . "');";
            } else {
                $updatesql = "UPDATE set_detail SET std_amount ='" . $item_amount . "' WHERE item_code ='" . $itemid . "' ";
            }
        }
    }
    if (mysqli_query($conn, $updatesql)) {
        $upset = "UPDATE standardsetor SET std_total ='" . $itotal . "' WHERE set_id ='" . $setid . "'";
        if (mysqli_query($conn, $upset)) {
            echo $itotal . "บันทึกเรียบร้อย";
        }
    } else {
        echo "เกิดข้อผิดพลาด";
    }
}
if (isset($_GET["del_id"]) && isset($_GET['setid'])) {
    $del_id = $_GET["del_id"];
    $setid = $_GET['setid'];
    $delsql = "DELETE FROM set_detail WHERE item_code = '" . $del_id . "' AND set_id = '" . $setid . "'";
    echo $delsql;
    if (mysqli_query($conn, $delsql)) {
        echo "del success";
    }
}
if (isset($_POST["submit"])) {
    $newsetname = htmlentities($_POST["setname"]);
    $setsql = "SELECT set_id,COUNT(set_id) as c_setid FROM standardsetor";
    if ($res = mysqli_query($conn, $setsql)) {
        while ($row = mysqli_fetch_array($res)) {
            $countid = $row['c_setid'];
            for ($i = 1; $i <= $countid + 1; $i++) {
                $tid = "set00" . $i;
                $csql = "SELECT COUNT(set_id) as checkid FROM standardsetor WHERE set_id ='" . $tid . "'";
                if ($rs = mysqli_query($conn, $csql)) {
                    while ($r = mysqli_fetch_array($rs)) {
                        echo $tid;
                        if ($r['checkid'] == 0) {
                            $set_id = $tid;
                            echo $set_id;
                            break;
                        }
                    }
                }
            }
            $namesql = "INSERT INTO `standardsetor`(`set_id`,`set_name`) VALUE('" . $set_id . "','" . $newsetname . "')";
        }
    }
    if (mysqli_query($conn, $namesql) == 1) {
        header("location:setmanager.php");
    }
}
if (isset($_GET["value"])) {
    $value = $_GET["value"];
    $tbsql = "SELECT s.item_code as item_code,i.item_name as item_name,i.item_unitprice as item_unitprice
            ,s.std_amount as std_amount,i.item_uc_price as item_uc_p,i.item_ofc_price as item_ofc_p FROM `set_detail` s  
            LEFT JOIN item i ON s.item_code = i.item_code WHERE s.set_id = '" . $value . "' ORDER BY item_unitprice DESC;";

    $total = 0;
    if ($rs = mysqli_query($conn, $tbsql)) {
        while ($row = mysqli_fetch_array($rs)) {
            $unit  = number_format($row['item_unitprice']);
            $amount = number_format($row['std_amount']);
            $oncetotal = $row['item_unitprice'] * $row['std_amount'];
            $total += $oncetotal;
            $oncetotal_f = number_format($oncetotal);
            echo  "<tr>";
            echo "<td>" . $row['item_code'] . "</td>";
            echo "<td>" . $row['item_name'] . "</td>";
            echo "<td style='text-align:right'>" . $unit . "</td>";
            echo "<td style='text-align:right'>" . $amount . "</td>";
            echo "<td style='text-align:right' >" . $oncetotal_f . "</td>";
            echo  "</tr>";
        }
    }
    $total = number_format($total);
    echo "<tfoot>";
    echo "<tr style='font-weight:bold;'>";
    echo "<td colspan='3' style='text-align:right' >รวม</td>";
    echo "<td style='text-align:center'>" . $total . "</td>";
    echo "<td>บาท</td>";
    echo "</tr>";
    echo "</tfoot>";
}
if (isset($_GET["his_id"]) && isset($_GET["show"])) {
    $hn = $_GET["his_id"];
    $sql = "SELECT d.doc_id,d.doc_no,v.visit_name,v.HN_id,d.doc_total,d.doc_date,d.doc_status
                 FROM docestimate d LEFT JOIN visit v ON d.HN_id = v.HN_id 
                 WHERE d.HN_id = '" . $hn . "'";
    if ($rs = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_array($rs)) {
            $status = $row['doc_status'];
            echo "<tr>";
            echo     "<td>" . $row['doc_id'] . "</td>";
            echo     "<td>" . $row['doc_no'] . "</td>";
            echo     "<td>" . $row['visit_name'] . "</td>";
            echo     "<td>" . $row['doc_total'] . " </td>";
            echo     "<td>" . $row['doc_date'] . "</td>";
            echo    "<td width ='5%'>";
            echo     "<a class='btn btn-outline-secondary'><img src='img/open_eye.png'width='20px'></a>";
            echo    "</td>";
            echo    "<td width='10%'>";
            echo "<div class='form-check form-switch'>";
            if ($status == 1) {
                echo     "<input class='form-check-input' onclick='update_status(`" . $row['doc_id'] . "`)' type='checkbox' id='status' role='switch' checked />";
                echo     "<label class='form-check-label' style='color:#35fd2e' id='messtatus' for='flexSwitchCheckDefault'>Active</label>";
            } else if ($status == 0) {
                echo     "<input class='form-check-input' onclick='update_status(`" . $row['doc_id'] . "`)' type='checkbox' id='status' role='switch' />";
                echo     "<label class='form-check-label' style='color:#868686' id='messtatus' for='flexSwitchCheckDefault'>InActive</label>";
            }
            echo "</div>";
            echo    "</td>";
            echo  "</tr>";
        }
    }
}
if (isset($_GET['key']) && isset($_GET['doc_id'])) {
    $key = $_GET['key'];
    $doc_id = $_GET['doc_id'];
    $updoc = "UPDATE `docestimate` SET `doc_status` =" . $key . " WHERE `doc_id` = '" . $doc_id . "';";
    echo $updoc;
    mysqli_query($conn, $updoc);
}
if (isset($_POST["hn_btn"])) {
    $_SESSION['HN'] = $_POST["hn"];
    echo $_SESSION['HN'];
    header("Location:index.php");
}
if (isset($_GET["name"]) && isset($_GET["cost"]) && isset($_GET["type"])) {
    $name = htmlentities($_GET["name"]);
    $cost = $_GET["cost"];
    $type = $_GET["type"];
    $viewlab = "SELECT count(lab_id) as c_lab FROM `labxray`";
    $row = mysqli_fetch_assoc(mysqli_query($conn, $viewlab));
    $c = $row['c_lab'];
    $go = $_GET['go'];
    $lab_id = "LX000" . ($c + 1);
    $labsql = "INSERT INTO `labxray`(`lab_id`,`lab_name`, `lab_type`, `lab_cost`, `lab_status`) VALUES ( '" . $lab_id . "','" . $name . "','" . $type . "'," . $cost . ",1)";
    if (mysqli_query($conn, $labsql)) {
        if ($go == 1) {
            header("location:lab_xray.php");
        } else  if ($go == 0) {
            $_SESSION['newlab'] = $lab_id;
        }
    }
}
if (isset($_GET['key']) && isset($_GET['lab_id'])) {
    $key = $_GET['key'];
    $lab_id = $_GET['lab_id'];
    $updoc = "UPDATE `labxray` SET `lab_status` =" . $key . " WHERE `lab_id` = '" . $lab_id . "';";
    if (mysqli_query($conn, $updoc)) {
        echo $lab_id . " update successful";
    }
}

if (isset($_GET['j']) && isset($_GET['hndoc'])) {
    $j = $_GET['j'];
    $min_stack = 0;
    $max_stack = 0;
    $js = json_decode($j, true); //GET ALL DATA FROM estimate form
    ///////////////////////CREATE DOCUMENTS/////////////////////////////////////////

    $hndoc = $_GET['hndoc'];
    $showsql = "SELECT COUNT(`doc_id`) as c FROM `docestimate`";
    $showsql2 = "SELECT COUNT(`doc_id`) as c FROM `docestimate` WHERE `HN_id` = '" . $hndoc . "'";
    if ($s = mysqli_query($conn, $showsql)) {
        $rs = mysqli_fetch_assoc($s);
        $number = $rs['c'];
    }
    if ($s = mysqli_query($conn, $showsql2)) {
        while ($rs = mysqli_fetch_assoc($s)) {
            $count = $rs['c'];
        }
    }
    $doc_id = 'DOC00' . ($number + 1);
    $privacy = $js['privacy'];
    $dg = $js['diagnose'];
    $createDoc = "INSERT INTO `docestimate`(`doc_id`, `doc_no`,`doc_name`, `doc_privacy`, `HN_id`)
        VALUES('" . $doc_id . "','" . ($count + 1) . "','" . $dg . "','" . $privacy . "'," . $hndoc . ")";
    if (mysqli_query($conn, $createDoc)) {
        echo "<br>" . $doc_id . "<br>";
        ////////////////////////OR ITEMS////////////////////////////////////////
        $or_c = count($js['or_value']);
        for ($i = 0; $i < $or_c; $i++) {
            $vdata = $js['or_value'][$i];
            $sql = "SELECT s.item_code,i.item_name,s.set_id,i.item_category
        ,i.item_unitprice,s.std_amount,i.item_uc_price,i.item_ofc_price,i.item_ss_price
        FROM set_detail s LEFT JOIN item i ON s.item_code = i.item_code WHERE s.set_id = '" . $vdata . "';";
            if ($res = mysqli_query($conn, $sql)) {
                while ($row = mysqli_fetch_array($res, true)) {
                    $or[] = $row;
                }
            }
        }
        if ($or) {
            for ($i = 0; $i < count($or); $i++) {
                $itemtotal = $or[$i]['item_unitprice'] * $or[$i]['std_amount'];
                insert_item(
                    $doc_id,
                    $or[$i]['item_code'],
                    $or[$i]['item_name'],
                    $or[$i]['set_id'],
                    $or[$i]['item_category'],
                    $or[$i]['std_amount'],
                    $or[$i]['std_amount'],
                    $or[$i]['item_unitprice'],
                    $or[$i]['item_uc_price'],
                    $or[$i]['item_ofc_price'],
                    $or[$i]['item_ss_price'],
                    $itemtotal,
                    $itemtotal,
                    $conn
                );
                $min_stack += $itemtotal;
                $max_stack += $itemtotal;
            }
        }
        ////////////////////////////LAB X-RAY////////////////////////////////////
        $lab_c = count($js['lab_value']);
        //echo "count:" . $lab_c;

        if ($lab_c != 0) {
            for ($i = 0; $i < $lab_c; $i++) {
                $vdata = $js['lab_value'][$i];
                //echo  $vdata;
                $sql = "SELECT * FROM `labxray` WHERE `lab_id` = '" . $vdata . "';";
                $row = mysqli_fetch_array(mysqli_query($conn, $sql));
                $lab[$i][0] =  $row['lab_id'];
                $lab[$i][1] =  $row['lab_name'];
                $lab[$i][2] =  $row['lab_type'];
                $lab[$i][3] =  $row['lab_cost'];
            }
            for ($i = 0; $i < $lab_c; $i++) {
                $itemtotal = $lab[$i][3] * 1;
                insert_item(
                    $doc_id,
                    $lab[$i][0],
                    $lab[$i][1],
                    NULL,
                    $lab[$i][2],
                    '1',
                    '1',
                    $lab[$i][3],
                    0,
                    0,
                    0,
                    $itemtotal,
                    $itemtotal,
                    $conn
                );
                $min_stack += $itemtotal;
                $max_stack += $itemtotal;
            }
        }
    }
    //////////////////////////ห้องพักผู้ป่วย//////////////////////////////////////
    $rest_c = count($js['rest_value']);
    //echo "count:" . $rest_c;
    for ($i = 0; $i < $rest_c; $i++) {
        $restsql = "SELECT * FROM item WHERE item_code = '" . $js['rest_value'][$i]['id'] . "';";
        if ($res = mysqli_query($conn, $restsql)) {
            while ($row = mysqli_fetch_array($res, true)) {
                $rest[] = $row;
            }
        }
    }

    for ($i = 0; $i < $rest_c; $i++) {
        $item_mintotal = $rest[$i]['item_unitprice'] * $js['rest_value'][$i]['min_restday'];
        $item_maxtotal = $rest[$i]['item_unitprice'] * $js['rest_value'][$i]['max_restday'];
        insert_item(
            $doc_id,
            $rest[$i]['item_code'],
            $rest[$i]['item_name'],
            NULL,
            $rest[$i]['item_category'],
            $js['rest_value'][$i]['min_restday'],
            $js['rest_value'][$i]['max_restday'],
            $rest[$i]['item_unitprice'],
            $rest[$i]['item_uc_price'],
            $rest[$i]['item_ofc_price'],
            $rest[$i]['item_ss_price'],
            $item_mintotal,
            $item_maxtotal,
            $conn
        );

        $min_stack += $item_mintotal;
        $max_stack += $item_maxtotal;
    }


    //////////////////////////ห้องผ่าตัด//////////////////////////////////////
    $room_c = count($js['room_value']);
    //echo "<br>count:" . $room_c;
    for ($i = 0; $i < $room_c; $i++) {
        $roomsql = "SELECT * FROM item WHERE item_code = '" . $js['room_value'][$i]['id'] . "'";
        if ($res = mysqli_query($conn, $roomsql)) {
            while ($row = mysqli_fetch_array($res, true)) {
                $room[] = $row;
            }
        }
    }
    for ($i = 0; $i < $room_c; $i++) {
        $item_mintotal = $room[$i]['item_unitprice'] * $js['room_value'][$i]['min_amount'];
        $item_maxtotal = $room[$i]['item_unitprice'] * $js['room_value'][$i]['max_amount'];
        insert_item(
            $doc_id,
            $room[$i]['item_code'],
            $room[$i]['item_name'],
            NULL,
            $room[$i]['item_category'],
            $js['room_value'][$i]['min_amount'],
            $js['room_value'][$i]['max_amount'],
            $room[$i]['item_unitprice'],
            $room[$i]['item_uc_price'],
            $room[$i]['item_ofc_price'],
            $room[$i]['item_ss_price'],
            $item_mintotal,
            $item_maxtotal,
            $conn
        );
        $min_stack += $item_mintotal;
        $max_stack += $item_maxtotal;
    }

    //////////////////////////OTHER//////////////////////////////////////

    $other_c = count($js['other_value']);

    for ($i = 0; $i < $other_c; $i++) {
        $othersql = "SELECT count(`Item_id`) as c FROM docdetail WHERE `Item_id` LIKE '%OTHER%'";
        if ($crs = mysqli_query($conn, $othersql)) {
            $rnum = mysqli_fetch_assoc($crs);
            $other_num =  $rnum['c'];
        }
        $itemtotal = $js['other_value'][$i]['amount'] * $js['other_value'][$i]['price'];
        insert_item(
            $doc_id,
            'OTHER00' . ($other_num + 1),
            $js['other_value'][$i]['name'],
            null,
            '',
            $js['other_value'][$i]['amount'],
            $js['other_value'][$i]['amount'],
            $js['other_value'][$i]['price'],
            0,
            0,
            0,
            $itemtotal,
            $itemtotal,
            $conn
        );
        $min_stack += $itemtotal;
        $max_stack += $itemtotal;
    }
   // echo $js['privacy'];
    if ($js['privacy'] != "UC001") {
        if ($js['privacy'] == "UC002") {
            $privacyAllcost = "SELECT SUM(`item_uc_price`) as 'p_total' FROM docdetail WHERE `doc_id` ='" . $doc_id . "';";
        }else if($js['privacy'] == "UC003"){
            $privacyAllcost = "SELECT SUM(`item_ofc_price`) as 'p_total' FROM docdetail WHERE `doc_id` ='" . $doc_id . "';";
        }else if($js['privacy'] == "UC004"){
            $privacyAllcost = "SELECT SUM(`item_ss_price`) as 'p_total' FROM docdetail WHERE `doc_id` ='" . $doc_id . "';";
        }
        if ($row = mysqli_fetch_assoc(mysqli_query($conn, $privacyAllcost))) {
            $p_total = $row['p_total'];
        }
    }else{
        $p_total = 0;
    }
    $warning = $js['warning'];
       // echo $warning;
    $updatetotal = "UPDATE docestimate SET doc_min_total = '" . $min_stack . "',doc_max_total = '" . $max_stack . "',doc_p_total ='".$p_total."',doc_noti = '".$warning."' WHERE doc_id = '" . $doc_id . "';";
    if (mysqli_query($conn, $updatetotal)) {
        header("location:appraisal.php?doc_id=" . $doc_id."&warning=" . $warning);
    }
}

if (isset($_GET['select'])) {
    $lxsql = "SELECT * FROM labxray WHERE lab_status = 1 ORDER BY lab_type;";
    if (isset($_GET['newses'])) {
        $_SESSION['newlab'] = $_GET['newses'];
    }
    if ($res = mysqli_query($conn, $lxsql)) {
        while ($rw = mysqli_fetch_array($res)) {
            if ($_SESSION['newlab'] == $rw['lab_id']) {
                echo  "<option value=" . $rw['lab_id'] . " selected>" . $rw['lab_type'] . " : " . $rw['lab_name'] . " (" . $rw['lab_cost'] . " บาท)" . "</option>";
            } else {
                echo  "<option value=" . $rw['lab_id'] . ">" . $rw['lab_type'] . " : " . $rw['lab_name'] . " (" . $rw['lab_cost'] . " บาท)" . "</option>";
            }
        }
    }
    $_SESSION['newlab'] = null;
}

function insert_item($doc_id, $Item_id, $Item_name, $Item_set, $Item_type, $Item_min_amount, $Item_max_amount, $item_price, $item_uc_price, $item_ofc_price, $item_ss_price, $detail_min_total, $detail_max_total, $conn)
{

    $insert = "INSERT INTO `docdetail`(`doc_id`
    , `Item_id`, `Item_name`, `Item_set`, `Item_type`, `Item_min_amount`, `Item_max_amount`, `item_price`
    , `item_uc_price`, `item_ofc_price`, `item_ss_price`, `detail_min_total`, `detail_max_total`) 
    VALUES ('" . $doc_id . "','" . $Item_id . "','" . htmlentities($Item_name) . "','" . $Item_set . "'
    ,'" . $Item_type . "','" . $Item_min_amount . "','" . $Item_max_amount . "','" . $item_price . "','" . $item_uc_price . "'
    ,'" . $item_ofc_price . "','" . $item_ss_price . "','" . $detail_min_total . "','" . $detail_max_total . "');";
//echo $insert;
    if (mysqli_query($conn, $insert) == true) {
        // echo $Item_id . ":Success" . "<br>";
    }
}
function sessionValue($ses, $value)
{
    $_SESSION[$ses] = $value;
}
