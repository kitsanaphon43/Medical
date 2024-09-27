<?php
session_start();
include("connect.php");
if (empty($_SESSION['fname'])) {
    echo $_SESSION['fname'];
    header("location:login.php");
}
$file_Sql = "SELECT `uc_path` FROM `uc` ";

if ($rs = mysqli_query($conn, $file_Sql)) {
    while ($row = mysqli_fetch_assoc($rs)) {
        $set[] = $row['uc_path'];
    }
}

$path_ss = pathinfo($set[3]);
$path_ofc = pathinfo($set[2]);
$path_uc = pathinfo($set[1]);
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


    <div class="container-fluid">

        <div id="tablezone">
            <div class="row">
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
                        <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'superadmin') { ?>
                            <center>
                                <hr style="width:100px;">
                            </center>
                            <a href="access.php" class="choosed">
                                <div class="col-md-12 ">
                                    จัดการสิทธิการเข้าถึง
                                </div>
                            </a>
                            <a href="privilege.php" class="now">
                                <div class="col-md-12 ">
                                    เกณฑ์การใช้สิทธิการรักษา
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-10">
                    <br>
                    <div class="card text-start" style="margin: top 20px;">
                        <div class="card-body">
                            <table border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="80%">
                                            <h2>สิทธิประกันสังคม (SS)</h2>
                                        </td>
                                        <td rowspan="2">
                                            <a href="<?php echo 'docs/' . $path_ss['filename'] . '.pdf' ?>" class="btn btn-primary">ดูเกณฑ์</a>
                                            <!-- Modal -->
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ss_model">
                                                อัปโหลดเกณฑ์
                                            </button>
                                            <div class="modal fade" id="ss_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนเกณฑ์ใหม่</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="newpath.php" method="POST" enctype="multipart/form-data">
                                                                <input type="text" value="UC004" name="target_id" style="display: none;">
                                                                <input type="file" accept=".pdf" class="form-control" name="rule" id="rule">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                            <input type="submit" value="อัปโหลด" name="upload_rule" class="btn btn-primary">
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $path_ss['basename'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="card text-start" style="margin: top 20px;">
                        <div class="card-body">
                            <table border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="80%">
                                            <h2>สิทธิข้าราชการ (OFC)</h2>
                                        </td>
                                        <td rowspan="2">
                                            <a href="<?php echo 'docs/' . $path_ofc['filename'] . '.pdf' ?>" class="btn btn-primary">ดูเกณฑ์</a>
                                            <!-- Modal -->
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ofC_model">
                                                อัปโหลดเกณฑ์
                                            </button>
                                            <div class="modal fade" id="ofC_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนเกณฑ์ใหม่</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="newpath.php" method="POST" enctype="multipart/form-data">
                                                                <input type="text" value="UC003" name="target_id" style="display: none;">
                                                                <input type="file" accept=".pdf" class="form-control" name="rule" id="rule">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                            <input type="submit" value="อัปโหลด" name="upload_rule" class="btn btn-primary">
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $path_ofc['basename'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="card text-start" style="margin: top 20px;">
                        <div class="card-body">
                            <table border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="80%">
                                            <h2>สิทธิการรักษาตามนโยบายรัฐ (UC)</h2>
                                        </td>
                                        <td rowspan="2">
                                            <a href="<?php echo 'docs/' . $path_uc['filename'] . '.pdf' ?>" class="btn btn-primary">ดูเกณฑ์</a>
                                            <!-- Modal -->
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uc_model">
                                                อัปโหลดเกณฑ์
                                            </button>
                                            <div class="modal fade" id="uc_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนเกณฑ์ใหม่</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="newpath.php" method="POST" enctype="multipart/form-data">
                                                                <input type="text" value="UC002" name="target_id" style="display: none;">
                                                                <input type="file" accept=".pdf" class="form-control" name="rule" id="rule">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                            <input type="submit" value="อัปโหลด" name="upload_rule" class="btn btn-primary">
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $path_uc['basename'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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

</html>