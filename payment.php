<?php include('header.php');
try {
  require_once 'db.php';
  $sql="select * from payment";
  $result = mysqli_query($conn, $sql);
?>
<div class="container">
<table class="table table-bordered table-striped">
 <tr>
  <th>求才廠商</th>
  <th>求才內容</th>
  <th>日期</th>
 </tr>
 <?php
 while($row = mysqli_fetch_assoc($result)) {?>
 <tr>
  <td><?=$row["company"]?></td>
  <td><?=$row["content"]?></td>
  <td><?=$row["pdate"]?></td>
 </tr>
 <?php
  }
 ?>
</table>
</div>
<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>
<?php include('footer.php'); ?>