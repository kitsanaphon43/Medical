<?php
include "connect.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

session_start();

if (isset($_POST['import'])) {
    if ($_FILES["fileload"]["error"] >= 0) {
        $reader = IOFactory::createReader('Xlsx');
        $tmp_name = $_FILES["fileload"]["name"];
        $path = $_FILES["fileload"]["tmp_name"];
        $setid = $_POST['importset'];
        echo $setid;
        $loader = $reader->load($path);
        $sheet = $loader->getSheet(0);
        $row_length = $sheet->getHighestRow();
        $col_length = $sheet->getHighestColumn();
        $data = $sheet->toArray();
        $json = [];
        $checker1 = $data[0][0];
        $checker2 = $data[0][1];

        if (is_int(intval(substr($checker1, 0, 5))) == true) {
            if (intval(substr($checker1, 5, 4))) {
                echo "<br>" . $checker1 . "=" . substr($checker1, 0, 5) . "<br>";

                /*  echo $checker1."///".$checker2;
                 echo "row = " . $row_length;
                echo "col = " . count($data[0]);*/
                for ($i = 2; $i < $row_length; $i++) {
                    $row = [
                        "item_code" => $data[$i][0],
                        "item_name" => $data[$i][1],
                        "item_category" => $data[$i][2],
                        "item_unitprice" => $data[$i][3],
                        "item_uc" => $data[$i][4],
                        "item_ofc" => $data[$i][5],
                    ];
                    array_push($json, $row);
                }

                $i = 0;
                for ($i = 0; $i < count($json); $i++) {
                    $item_code = $json[$i]['item_code'];
                    $item_name = $json[$i]['item_name'];
                    $item_category = $json[$i]['item_category'];
                    $item_unit = $json[$i]['item_unitprice'];
                    $item_uc = $json[$i]['item_uc'];
                    $item_ofc = $json[$i]['item_ofc'];
                    if (is_numeric($item_uc) == false) {
                        $item_uc = 0;
                    }
                    if (is_numeric($item_ofc) == false) {
                        $item_ofc = 0;
                    }
                    $fsql = "SELECT count(item_code) as c_item FROM item WHERE item_code = '$item_code'";

                    $r = mysqli_fetch_assoc(mysqli_query($conn, $fsql));
                    if ($r['c_item'] < 1) {
                        add_item($item_code, $item_name, $item_category, $item_unit, $item_uc, $item_ofc, $conn);
                    } else {
                        update_item($item_code, $item_name, $item_category, $item_unit, $item_uc, $item_ofc, $conn);
                    }
                    add_set($setid, $item_code, $conn);
                }
                if ($i == count($json)) {
                    echo "<br>complete";
                    header("location:setmanager.php?id=" . $setid);
                } else {
                    echo $_FILES["fileload"]["error"];
                }
            }else {
                header("location:setmanager.php?er=true");
            }
        } else {
            header("location:setmanager.php?er=true");
        }
    }
}
function add_item($item_code, $item_name, $item_category, $item_unit, $item_uc, $item_ofc, $conn)
{
    if ($item_code != "") {
        $item_unit = becomeNumber($item_unit, 0);
        $item_uc = becomeNumber($item_uc, 0);
        $item_ofc = becomeNumber($item_ofc, 0);
        $sql = "INSERT INTO item VALUES ('$item_code','$item_name','$item_category','$item_unit','$item_uc
    ','$item_ofc')";
        echo $sql;
        if (mysqli_query($conn, $sql)) {
            echo $item_code . ":success";
        }
    }
}
function add_set($set_id, $item_code, $conn)
{
    if ($item_code != "") {
        $checksql = "SELECT COUNT(*) as c_set , std_amount FROM set_detail WHERE set_id = '$set_id' AND item_code = '$item_code'";
        if ($row = mysqli_fetch_assoc(mysqli_query($conn, $checksql))) {
            $dup = $row['c_set'];
            if ($dup < 1) {
                $sqlset = "INSERT INTO `set_detail`(`set_id`, `item_code`) VALUES ('$set_id','$item_code')";
            } else {
                $sqlset = "";
            }
        }
        echo $sqlset;
        if ($sqlset != "") {
            if (mysqli_query($conn, $sqlset)) {
                echo "<br>" . $item_code . ":success";
            }
        } else {
            echo "<br>" . $item_code . ":is already item";
        }
    }
}
function update_item($item_code, $item_name, $item_category, $item_unit, $item_uc, $item_ofc, $conn)
{
    if ($item_code != "") {
        $item_unit = becomeNumber($item_unit, 0);
        $item_uc = becomeNumber($item_uc, 0);
        $item_ofc = becomeNumber($item_ofc, 0);
        $updatesql = "UPDATE item SET item_name='" . $item_name . "'
    ,item_name='" . $item_name . "'
    ,item_category='" . $item_category . "'
    ,item_unitprice='" . $item_unit . "'
    ,item_uc_price='" . $item_uc . "'
    ,item_ofc_price='" . $item_ofc . "'
    WHERE item_code = '" . $item_code . "'";

        if (mysqli_query($conn, $updatesql)) {
            echo $item_code . ":updated";
        }
    }
}
function becomeNumber($num, $status)
{
    if ($status == 0) {
        $a = str_replace(',', '', $num);
    }
    return $a;
}
