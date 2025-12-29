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

  if ($_GET) { //如果有使用 get
    require_once 'db.php';
    $action = $_GET["action"]??"";

    if ($action=="confirmed"){
      //delete data
      $payment_id = $_GET["payment_id"];
      $sql="delete from payments where payment_id=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $payment_id);
      $result = mysqli_stmt_execute($stmt);
      header('location:payment.php');
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
        mysqli_stmt_bind_result($stmt, $student_id, $name,$amount, $activity, $status, $payment_date);
        mysqli_stmt_fetch($stmt);
      }
    }//confirmed else
    mysqli_close($conn);
  }
}
 catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
?>
<div class="container mt-3 md-3">
  <table class="table table-bordered table-striped">
    <tr>
      <td>學號</td>
      <td>姓名</td>
      <td>活動</td>
      <td>金額</td>
      <td>狀態</td>
      <td>繳費日期</td>
    </tr>
    <tr>
      <td><?=$student_id?></td>
      <td><?=$name?></td>
      <td><?=$activity?></td>
      <td><?=$amount?></td>
      <td><?=$status?></td>
      <td><?=$payment_date?></td>
    </tr>
  </table>
  <a href="payment_delete.php ?payment_id=<?=$payment_id?>&action=confirmed" class="btn btn-danger">刪除</a>
<!--這個機制就是 $_GET 陣列： student_id=[某個ID] 會被存為 $_GET["student_id"]。 action=confirmed 會被存為 $_GET["action"]。-->
  <a href="payment.php" class="btn btn-secondary">取消</a>
</div>
<?php
require_once "footer.php";
?>