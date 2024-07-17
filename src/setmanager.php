<script>
  var ready_id = "";
</script>
<?php
include("connect.php");
session_start();
if (empty($_SESSION['HN'])) {
  header('Location:index.php');
}
if (isset($_GET['er'])) {
  echo "<script>alert('ไฟล์ Excel นี้ไม่ถูกต้องตามฟอร์มที่กำหนด - กรุณาดาวน์โหลดฟอร์ม')</script>";
}
if (isset($_GET['id'])) {
?>
  <script>
    ready_id = '<?php echo $_GET['id']; ?>';
  </script>
<?php
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
  <title>SetItem Manager</title>
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

    <div class="row">

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
          <center>
            <hr style="width:100px;">
          </center>
          <a href="setmanager.php" class="now">
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
      <div class="col-md-10" style="margin-top:20px;">


        <div class="row">


          <div class="col-md-11">
            <?php
            $sql = "SELECT set_id,set_name FROM standardsetor ";
            if ($result = mysqli_query($conn, $sql)) {

            ?>
              <select class="form-select form-select-lg w-100" name="" id="selectset">
                <option value="start" selected>เลือกรูปแบบการรักษา</option>
                <?php
                while ($row = mysqli_fetch_array($result)) {
                ?>

                  <option value="<?php echo $row['set_id'] ?>"><?php echo $row['set_id'] . "-" . $row['set_name'] ?></option>
              <?php
                }
              }
              ?>
              </select>

          </div>

          <div class="col-md-1"><a class="btn btn-outline-primary btn-lg w-100" id="choosethis" onclick="chooseset()">เลือก</a></div>

          <!-- Modal Body -->
          <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->


          <form action="path.php" method="post">
            <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                      ตั้งหัวข้อชุดข้อมูล
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-12">
                        <input type="text" class="form-control" placeholder="ชื่อหัวข้อชุดข้อมูล" name="setname">
                      </div>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <input type="submit" name="submit" value="สร้างชุดใหม่" class="btn btn-primary w-25">
                    <button type="button" class="btn btn-secondary w-25" data-bs-dismiss="modal">ยกเลิก</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <!---->
          <div class="col-md-5" style="margin-top:20px;">
            <table class="table table-borderless" id="itb">
              <thead>
                <th colspan="4">
                  <div class="row">
                    <div class="col-md-4">
                      <select class="form-select" name="" id="f" onchange="ifilter()" disabled>
                        <option value="all" selected>ทั้งหมด</option>
                        <?php
                        $sql = 'SELECT description_th as type_name FROM type_item';
                        if ($result = mysqli_query($conn, $sql)) {
                          while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <option value="<?php echo $row['type_name']; ?>"><?php echo $row['type_name']; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-8">
                      <input type="text" onkeyup="showResult(this.value)" class="form-control" id="isearch" placeholder="ค้นหา (พิมพ์ * แสดงไอเทมแต่ละหมวด)" disabled>
                    </div>
                  </div>
                </th>

          </div>

          </thead>
          <tbody id="itable" style="height: 250px;">

          </tbody>
          </table>
          <div class="col-md-12 " style="border: 1px solid gray; padding:10px;margin-bottom:10px">
            <form action="excel.php" onsubmit="getpath()" method="POST" enctype="multipart/form-data">
              <label>นำเข้าไฟล์</label>
              <?php
              $sql = "SELECT set_id,set_name FROM standardsetor ";
              if ($result = mysqli_query($conn, $sql)) {

              ?>
                <select class="form-select form-select-md w-100" name="importset" id="importset" onchange="importbtn()">
                  <option value="start" selected>เลือกชุดที่ต้องการนำเข้า</option>
                  <?php
                  while ($row = mysqli_fetch_array($result)) {
                  ?>

                    <option value="<?php echo $row['set_id'] ?>"><?php echo $row['set_id'] . "-" . $row['set_name'] ?></option>
                <?php
                  }
                }
                ?>
                </select>
                <input type="text" name="file_src" id="file_src" style="display: none;">
                <input type="file" accept=".xlsx" name="fileload" id="fileload" class="form-control" style="margin-bottom:10px;" onchange="importbtn()">
                <input type="submit" value="นำเข้า" name="import" id="submitbtn" class="btn btn-secondary w-100" disabled>
            </form>
          </div>
          <div class="col-md-12 " style="border: 1px solid gray; padding:10px;">
            <div class="row">
              <div class="col-md-6">
                <a class="btn btn-outline-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#modalId">เพิ่มชุดผ่าตัดใหม่</a>
              </div>

              <div class="col-md-6">
                <a class="btn btn-outline-danger btn-lg w-100" onclick=" delRow()"> ลบรายการ</a>
              </div>

              <div class="col-md-6">
                <a class="btn btn-outline-secondary btn-lg w-100" href="excel/sheets/OR-items.xlsx" style="margin-top: 20px;" download>ดาวน์โหลดแบบฟอร์ม</a>
              </div>
              <div class="col-md-6">
                <a class="btn btn-success btn-lg w-100" style="margin-top: 20px;" onclick="editItemSet()">บันทึกการเปลี่ยนแปลง</a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-md-7" style="margin-top:20px;">
          <div class="table-responsive-md">
            <table id="EstimateTable" class="table table-borderless">
              <thead>

                <tr>
                  <th scope="col" style="width:10%;">
                    <center><input class="form-check-input" type="checkbox" onclick="allcheck()" value="" id="checkall"></center>
                  </th>
                  <th scope="col" width="15%">ID</th>
                  <th scope="col" width="30%">รายการ</th>
                  <th scope="col" style="width:12%;">จำนวน</th>
                  <th scope="col" style="width:10%;">ราคา/ชิ้น</th>
                  <th scope="col" style="width:10%;">ราคา</th>
                  <th scope="col" style="width:15%;">สิทธิ์</th>

                </tr>

              </thead>
              <tbody id="stdTable">
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3"> </th>

                  <th style="text-align: right;" colspan="2">ทั้งหมด</th>
                  <th id="showtotal" style="text-align: center;"></th>
                  <th>บาท</th>
                </tr>
              </tfoot>
            </table>
          </div>


        </div>

      </div>


      <!---<div class="row" id="savebar">
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
          <a class="btn btn-outline-secondary w-100" href="index.php">กลับสู่หน้าหลัก</a>
        </div>
      
        <div class="col-md-3">
        </div>
       
      </div>-->
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
  //CDN

  var roomcost = 0;
  var diffday = 0;
  var estrest = 1;
  var del_log = [];
  $(document).ready(function() {
    var selected = document.getElementById("selectset");
    console.log(selected.value);
    var optionset = selected.getElementsByTagName("option");
    for (var i = 0; i < optionset.length; i++) {
      if (optionset[i].value == ready_id) {
        optionset[i].selected = true;
        showTable();
      }
    }

  });

  function importbtn() {
    var c1 = document.getElementById('importset');
    var c2 = document.getElementById('fileload');
    var btn = document.getElementById('submitbtn');
    if (c1.value != 'start') {
      if (c2.value) {
        btn.disabled = false;
        btn.setAttribute("class", "btn btn-primary w-100");
      } else {
        btn.disabled = true;
        btn.setAttribute("class", "btn btn-secondary w-100");
      }
    } else {
      btn.disabled = true;
      btn.setAttribute("class", "btn btn-secondary w-100");
    }
  }

  function getpath() {
    var path = document.getElementById('fileload').value;
    var src = document.getElementById('file_src');
    src.value = path;
    console.log(src.value);
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
  //interfaces
  function selectroom(data, name) {
    roomcost = data;
    var rest = diffday;
    var estrest = roomcost * rest;
    console.log(estrest);
    console.log("นอน " + diffday + " คืน<br>" + estrest + " บาท");
  }

  function allcheck() {
    var table = document.getElementById('EstimateTable');
    var checkall = document.getElementById('checkall');
    var del_data = Table_to_Json('stdTable');
    var endcheck = del_data.length;
    for (let i = 0; i < endcheck; i++) {
      var checkid = document.getElementById('check' + del_data[i][1]);
      if (checkall.checked == true) {
        checkid.checked = true;
      } else {
        checkid.checked = false;
      }
    }
  }

  function delRow() {
    var x = confirm("คุณต้องการลบข้อมูลนี้หรือไม่?");
    if (x) {
      var table = document.getElementById('stdTable');
      var checkall = document.getElementById('checkall');
      let i = 0;
      do {
        var del_data = Table_to_Json('stdTable');
        console.log(del_data.length);
        var endcheck = del_data.length;
        console.log(endcheck);
        if (endcheck != 0) {
          var checkid = document.getElementById('check' + del_data[i][1]);
          if (checkid.checked == true) {
            table.deleteRow(i);
            del_log.push(del_data[i][1]);

            total();
            document.getElementById('checkall').checked = false;
            i = 0;
          } else {
            i++;
          }
        }
      } while (i < endcheck)
    }
  }

  function total() {
    var rs = Table_to_Json('EstimateTable');
    var table = document.getElementById('EstimateTable');
    var ilen = table.rows.length;
    let total = 0;
    for (let i = 1; i < ilen - 1; i++) {
      let val = rs[i][5];
      total = parseFloat(total) + parseFloat(val);
    }
    document.getElementById('showtotal').innerHTML = total;
  }
  $('#choosethis').on('click', function() {

  });

  function chooseset() {
    var key = Table_to_Json('stdTable');
    var count_key = key.length;
    if (count_key != 0) {
      var x = confirm("คุณต้องการบันทึกและเปลี่ยนชุดข้อมูลใช่หรือไม่?");
      if (x) {
        editItemSet();
        showTable();
      }
    } else {
      showTable();
    }
  }

  function price(id, value) {
    var unit = document.getElementById('unit' + id).innerHTML;
    var cost = document.getElementById('cost' + id);
    console.log(cost);
    console.log(unit);
    var cal_cost = unit * value;
    cost.innerHTML = cal_cost
    total()
  }
  $(document).on("click", "#choosethis", function() {
    var value = document.getElementById('selectset').value;
    if (value == "start") {
      document.getElementById('isearch').disabled = true;
      document.getElementById('f').disabled = true;
    } else {
      document.getElementById('isearch').disabled = false;
      document.getElementById('f').disabled = false;
    }
  })
</script>
<SCript>
  //ajax
  var setid;

  function showTable() {

    setid = document.getElementById('selectset').value;

    console.log(setid);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('stdTable').innerHTML = this.responseText;
        total();
      }
    };
    xmlhttp.open("GET", "path.php?set_id=" + setid, true);
    xmlhttp.send();
  }



  function Est() {
    var txt = document.getElementById('HN').value;
    console.log(txt);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('idenTable').innerHTML = this.responseText;
      }
    }
    xmlhttp.open("GET", "path.php?hn_id=" + txt, true);
    xmlhttp.send();
  }

  function ifilter() {
    var data = document.getElementById('isearch').value;
    showResult(data);
  }

  function showResult(str) {
    var filter = document.getElementById('f').value;
    console.log(filter)
    var xmlhttp = new XMLHttpRequest();
    if (str) {
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("itable").innerHTML = this.responseText;
        }
      }
      xmlhttp.open("GET", "path.php?search=" + str + "&filter=" + filter, true);
      xmlhttp.send();
    } else {
      document.getElementById("itable").innerHTML = "";

    }
  }

  function adddata(data) {
    var q = 1;
    var table = document.getElementById('stdTable');
    var listTable = Table_to_Json('stdTable');
    var dup = 0;
    for (let i = 0, end = listTable.length; i < end; i++) {
      var checkdup = listTable[i][1];
      if (data == checkdup) {
        var amount_val = +document.getElementById('quality' + checkdup).value;
        amount_val += 1;
        console.log(amount_val);
        document.getElementById('quality' + checkdup).value = amount_val;
        price(data, amount_val);
        total();
        dup = 1;
        break;
      }
    }
    if (dup == 0) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var newitem = this.responseText;
          newitem = JSON.parse(newitem);
          newitem_id = newitem[0];
          console.log(newitem_id);
          newitem_name = newitem[1];
          newitem_unit = newitem[2];
          newitem_privacy = newitem[3];
          var tr = document.createElement('tr');
          var td = document.createElement('td');
          var td1 = document.createElement('td');
          var td2 = document.createElement('td');
          var td3 = document.createElement('td');
          var td4 = document.createElement('td');
          var td5 = document.createElement('td');
          var td6 = document.createElement('td');
          var center = document.createElement('center');
          var checkbox = document.createElement('input');
          checkbox.setAttribute("type", "checkbox");
          checkbox.setAttribute("class", "form-check-input");
          checkbox.setAttribute("id", "check" + newitem_id);
          var num = document.createElement('input');
          num.setAttribute("type", "number");
          num.setAttribute("class", "form-control");
          num.setAttribute("id", "quality" + newitem_id);
          num.setAttribute("onchange", "price(`" + newitem_id + "`,this.value)");
          num.setAttribute("id", "quality" + newitem_id);
          num.setAttribute("value", "1");
          num.setAttribute("min", "1");
          center.appendChild(checkbox);
          td.appendChild(center);
          td1.innerHTML = newitem_id;
          td2.innerHTML = newitem_name;
          td3.appendChild(num);
          td4.setAttribute("id", "unit" + newitem_id);
          td4.innerHTML = newitem_unit;
          td5.setAttribute("id", "cost" + newitem_id);
          td5.innerHTML = newitem_unit;
          td6.innerHTML = newitem_privacy;
          tr.appendChild(td);
          tr.appendChild(td1);
          tr.appendChild(td2);
          tr.appendChild(td3);
          tr.appendChild(td4);
          tr.appendChild(td5);
          tr.appendChild(td6);
          table.appendChild(tr);
          total();
        }
      }
      xmlhttp.open("GET", "path.php?item_id=" + data + "&quality=" + q, true);
      xmlhttp.send();
    }
  }

  function editItemSet() {
    var json = Table_to_Json('stdTable');
    let jlen, arrlen;
    var item_id;
    var xhr = [];
    console.log(json.length);
    if (del_log.length != 0) {
      var xmlhttp = []
      var del_c = del_log.length;
      for (let a = 0; a < del_c; a++) {
        var del_id = del_log[a];
        console.log(setid);
        (function(a, del_id, setid) {
          xmlhttp[a] = new XMLHttpRequest();
          xmlhttp[a].open("GET", "path.php?del_id=" + del_id + "&setid=" + setid, true);
          xmlhttp[a].onreadystatechange = function() {
            if (xmlhttp[a].readyState === 4 && xmlhttp[a].status === 200) {
              console.log('Response delete' + a + ' [ ' + xmlhttp[a].responseText + ']');

            }
          };
          xmlhttp[a].send();
        })(a, del_id, setid);
      }
      del_log = [];
    }
    var tt = document.getElementById('showtotal').innerHTML;
    tt = tt.toString();
    for (let i = 0, jlen = json.length; i < jlen; i++) {
      item_id = json[i][1];
      item_amount = document.getElementById('quality' + item_id).value;
      console.log(setid + " " + i + " " + item_id + " " + item_amount);
      (function(i, setid, item_id, item_amount) {
        xhr[i] = new XMLHttpRequest();
        xhr[i].open("GET", "path.php?setid=" + setid + "&itemid=" + item_id +
          "&item_amount=" + item_amount + "&len=" + (i + 1) + "&itotal=" + tt, true);
        xhr[i].onreadystatechange = function() {
          if (xhr[i].readyState === 4 && xhr[i].status === 200) {
            console.log('Response from request ' + i + ' [ ' + xhr[i].responseText + ']');
          }
        };
        xhr[i].send();
      })(i, setid, item_id, item_amount);
    }
    alert("บันทึกเรียบร้อย");
  }
</SCript>

</html>