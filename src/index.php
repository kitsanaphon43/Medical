<script>
    var ses;
</script>
<?php
include("connect.php");
session_start();

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
    <link rel="stylesheet" type="text/css" href="css/s.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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


    <div class="container-fluid">


        <div class="row" style="display: block;" >
            <div class="col-md-12" >


                <div class="row">
                    <div class="col-md-12" style="background-color:rgb(69, 158, 214);color:white">
                        <h2 style="text-align: center;margin-top:20px;">ระบบประเมินค่ารักษาพยาบาล</h2>

                    </div>
                    <hr>
                    <div class="col-md-3" id="HNfind">
                        <form action="path.php" method="post">
                            <input type="text" class="form-control" <?php if (!empty($_SESSION['HN'])) { ?> value=<?php echo $hnid; ?><?php } ?> name="hn" id="hn_id" placeholder="HN" aria-label="Recipient's username" aria-describedby="basic-addon2">

                            <div class="input-group-append">
                                <input type="submit" class="btn btn-outline-secondary w-100" value="เลือก" name="hn_btn" id="hnclick" type="button">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody id="idenTable">
                                    <tr>
                                        <td>HN</td>
                                        <td>--</td>
                                        <td>ชื่อ-นามสกุล</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td>ที่อยู่</td>
                                        <td>--</td>
                                        <td>บัตรประชาชน</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td>เบอร์ติดต่อ</td>
                                        <td>--</td>
                                        <td>สิทธิ์</td>
                                        <td>
                                            --
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <hr>
        </div>
        <div id="tablezone">
            <div class="row">

                <!---->
                <!--Estimate Table-->
                <div class="col-md-2" id="menu">
                    <div class="row">
                        <a href="index.php">
                            <div class="col-md-12 now">
                                <img src="img/home.png" width="30px;" alt=""> หน้าแรก
                            </div>
                        </a>
                        <a href="estimate.php">
                            <div class="col-md-12 choosed">
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
                        <a href="setmanager.php">
                            <div class="col-md-12 choosed">
                                จัดการชุดผ่าตัด
                            </div>
                        </a>
                        <a href="lab_xray.php">
                            <div class="col-md-12 choosed">
                                จัดการชุดแล็ปและเอกซเรย์
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-10">
                    <table id="myTable" class="table table-bordered" style="width:100%;border:1px">
                        <thead>
                            <tr>
                                <th style="text-align: center;">รหัส</th>
                                <th style="text-align: center;">ใบที่</th>
                                <th style="text-align: center;">การวินิจฉัย</th>
                                <th style="text-align: center;">สิทธิ์</th>
                                <th style="text-align: center;">ราคาประมาณ</th>
                                <th style="text-align: center;">วันที่ออกใบประเมิน</th>
                                <th style="text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody id="history">
                            <?php
                            if (!empty($hnid)) {
                                $sql = "SELECT d.doc_id,d.doc_no,d.doc_name,uc.uc_name,v.HN_id,d.doc_total,d.doc_date,d.doc_status
                                    FROM docestimate d LEFT JOIN visit v ON d.HN_id = v.HN_id LEFT JOIN uc 
                                    ON d.doc_privacy = uc.uc_id
                                    WHERE d.HN_id = '" . $hnid . "'";
                                if ($res = mysqli_query($conn, $sql)) {
                                    while ($row = mysqli_fetch_array($res)) {
                            ?>
                                        <script>
                                            var doc_total = <?php echo $row['doc_total']; ?>;
                                            doc_total = doc_total.toLocaleString();
                                        </script>
                                        <tr>
                                            <td width="15%"><?php echo $row['doc_id'] ?></td>
                                            <td width="5%" style="text-align:center;"><?php echo $row['doc_no'] ?></td>
                                            <td width="15%"><?php echo $row['doc_name'] ?></td>
                                            <td width="5%"><?php echo $row['uc_name'] ?></td>
                                            <td width="15%" style="text-align: right;">
                                                <script>
                                                    document.write(doc_total + " บาท");
                                                </script>
                                            </td>
                                            <td style="text-align: right;" width="20%"><?php echo $row['doc_date'] ?></td>
                                            <td width="10%">
                                                <center>
                                                    <a href="appraisal.php?doc_id=<?php echo $row['doc_id']; ?>" id="print_btn" class='btn btn-outline-primary' onmouseover="img('<?php echo 'img' . $row['doc_id'] ?>','img/pw.png')" onmouseout="img('<?php echo 'img' . $row['doc_id'] ?>','img/open_eye.png')">
                                                        <img src='img/open_eye.png' id="<?php echo 'img' . $row['doc_id'] ?>" width='20px'>
                                                    </a>
                                                    <a href="Estimate.php?doc_id=<?php echo $row['doc_id']; ?>" id="dt_btn" class='btn btn-outline-warning' onmouseover="img('<?php echo 'img' . $row['doc_id'].'_2' ?>','img/clone_w.png')" onmouseout="img('<?php echo 'img' . $row['doc_id'].'_2' ?>','img/clone_y.png')">
                                                        <img src='img/clone_y.png' id="<?php echo 'img' . $row['doc_id'].'_2' ?>" width='20px'>
                                                    </a>
                                                </center>
                                            </td>

                                        </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!---->
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
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