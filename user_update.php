<?php
require_once "header.php";
try {
    $student_id ="" ;
    $name ="" ;
    $grade ="" ;
    $member_status ="" ;
    $msg="";

  if ($_GET) {
    require_once 'db.php';
    $action = $_GET["action"]??"";
    if ($action=="confirmed"){
      //update data
      $student_id = $_GET["student_id"];
      $grade = $_POST["grade"];
      $member_status = $_POST["member_status"];
      $sql="update user set grade =?, member_status =? where student_id=?";

      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "sss", $grade, $member_status, $student_id);
      $result = mysqli_stmt_execute($stmt);

      mysqli_close($conn);
      header('location:user.php');
      exit;
    }
    else{
      //show data
      $student_id = $_GET["student_id"];
      $sql="select student_id, name, grade, member_status from user where student_id=?";    
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $student_id);
      $res = mysqli_stmt_execute($stmt);
      if ($res){
        mysqli_stmt_bind_result($stmt, $student_id, $name, $grade, $member_status);
        mysqli_stmt_fetch($stmt);
      }
    }//confirmed else
    mysqli_close($conn);
  }//$_GET
} catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
?>
<div class="container mt-3 mb-3">
<h2>修改繳費記錄</h2>
<form action="user_update.php?student_id=<?=$student_id?>&action=confirmed" method="post">

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
      <label for="_grade" class="col-sm-2 col-form-label">系級</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="grade" id="_grade" placeholder="系級" value="<?=$grade?>">
      </div>
    </div>

    <div class="mb-3 row">
        <label for="_member_status" class="col-sm-2 col-form-label">會員狀態</label>
        <div class="col-sm-10">
            <select id="_member_status" name="member_status" class="form-select">
                <option value="" disabled>請選擇一個狀態</option>
                <option value="會員"  <?= ($member_status == '會員') ? 'selected' : '' ?>>會員</option>
                <option value="非會員"  <?= ($member_status == '非會員') ? 'selected' : '' ?>>非會員</option>
            </select>
        </div>
    </div>
    
    <input class="btn btn-primary" type="submit" value="更新">
    <a href="user.php" class="btn btn-secondary">取消</a>
    <?=$msg?>
</form>
</div>
<?php
require_once "footer.php";
?>