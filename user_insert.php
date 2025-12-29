<?php
$title = "新增學生記錄";
require_once "header.php";

try {
  require_once 'db.php';
    $msg="";

  if ($_SERVER["REQUEST_METHOD"] == "POST") { //存取方法

    $student_id = $_POST["student_id"];
    $name = $_POST["name"];
    $grade = $_POST["grade"];
    $member_status = $_POST["member_status"];
    $payment_count = $_POST["payment_count"];

    $sql="INSERT INTO user (student_id, name, grade, member_status, payment_count) 
        values (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);//準備一個空的容器（$stmt）來存放之後要執行的 SQL 查詢，並將這個容器與現有的資料庫連線（$conn）綁定。
    mysqli_stmt_prepare($stmt, $sql);//將您的 SQL 查詢放入 $stmt 中。
    mysqli_stmt_bind_param($stmt, "sssss", $student_id, $name, $grade, $member_status, $payment_count); //將變數帶入查詢
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      header('location:user.php');
    }
  else {
      $msg = "無法新增資料";
    }
  }
?>
<div class="container mt-3 mb-3">
<h2>新增學生記錄</h2>
<form action="user_insert.php" method="post">

    <div class="mb-3 row">
      <label for="_student_id" class="col-sm-2 col-form-label">學號</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="student_id" id="_student_id" placeholder="學號" required> <!--必填-->
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_name" class="col-sm-2 col-form-label">姓名</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="_name" placeholder="姓名" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_grade" class="col-sm-2 col-form-label">系級</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="grade" id="_grade" placeholder="系級" required>
      </div>
    </div>

    <div class="mb-3 row">
        <label for="_member_status" class="col-sm-2 col-form-label">會員狀態</label>
        <div class="col-sm-10">
            <select id="_member_status" name="member_status" class="form-select" required>
                <option value="" disabled selected>請選擇一個狀態</option><!--不能填 預選-->
                <option value="會員">會員</option>
                <option value="非會員">非會員</option>
            </select>
        </div>
    </div>
    
    <input class="btn btn-primary" type="submit" value="送出">
    <a href="user.php" class="btn btn-secondary">取消</a>
    <?=$msg?>
</form>
</div>

<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) { //如果 try 區塊中的程式碼拋出了一個「例外」（Exception，即非預期的錯誤），程式的執行流會立即跳轉到 catch 區塊。
  echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>