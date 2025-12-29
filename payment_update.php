<?php
require_once "header.php";
try {
    $payment_id ="" ;
    $student_id ="" ;
    $name ="" ;
    $activity ="" ;
    $amount ="" ;
    $status ="" ;
    $payment_date ="" ;
    $msg="";

  if ($_GET) {
    require_once 'db.php';
    $action = $_GET["action"]??"";
    if ($action=="confirmed"){
      //update data
      $payment_id = $_GET["payment_id"];
      $activity = $_POST["activity"];
      $status = $_POST["status"];
      $amount = $_POST["amount"];
      $payment_date = $_POST["payment_date"];
      $sql="update payments set activity =?, amount =?, status =?, payment_date =? where payment_id=?";

      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "sisss", $activity, $amount, $status, $payment_date, $payment_id);
      $result = mysqli_stmt_execute($stmt);
      header('location:payment.php');
      exit;
    }
    else{
      //show data
      $payment_id = $_GET["payment_id"];
      $sql="select student_id, name, activity, amount, status, payment_date from payments where payment_id=?";    
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $payment_id);
      $res = mysqli_stmt_execute($stmt);
      if ($res){
        mysqli_stmt_bind_result($stmt, $student_id, $name, $activity, $amount, $status, $payment_date); //這個函式是 依序連接變數
        mysqli_stmt_fetch($stmt); //照順序{放入}stmt
      }
    }
    mysqli_close($conn);
  }
} catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
?>
<div class="container mt-3 mb-3">
<h2>修改繳費記錄</h2>
<form action="payment_update.php?payment_id=<?=$payment_id?>&action=confirmed" method="post">

    <div class="mb-3 row">
      <label for="_student_id" class="col-sm-2 col-form-label">學號</label>
      <div class="col-sm-10">
        <p class="form-control-plaintext" id="_student_id"><?=$student_id?></p>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_name" class="col-sm-2 col-form-label">姓名</label>
      <div class="col-sm-10">
        <p class="form-control-plaintext" id="_name"><?=$name?></p>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_activity" class="col-sm-2 col-form-label">活動</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="activity" id="_activity" placeholder="活動" value="<?=$activity?>">
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_amount" class="col-sm-2 col-form-label">金額</label>
      <div class="col-sm-10">
        <input type="number" class="form-control" name="amount" id="_amount" placeholder="金額" value="<?=$amount?>">
      </div>
    </div>

    <div class="mb-3 row">
        <label for="_status" class="col-sm-2 col-form-label">繳費狀態</label>
        <div class="col-sm-10">
            <select id="_status" name="status" class="form-select">
                <option value="" disabled>請選擇一個狀態</option>
                <option value="已繳" <?= ($status == '已繳') ? 'selected' : '' ?>>已繳</option>
                <option value="未繳" <?= ($status == '未繳') ? 'selected' : '' ?>>未繳</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
      <label for="_payment_date" class="col-sm-2 col-form-label">日期</label>
      <div class="col-sm-10">
        <input type="date" class="form-control" name="payment_date" id="_payment_date" placeholder="日期" value="<?=$payment_date?>">
      </div>
    </div>
    
    <input class="btn btn-primary" type="submit" value="更新">
    <a href="payment.php" class="btn btn-secondary">取消</a>
    <?=$msg?>
</form>
</div>
<?php
require_once "footer.php";
?>