<?php
require_once "header.php";
try {
  require_once 'db.php';
  $msg="";
  if ($_POST) {
    // insert data
    $name = $_POST["name"];
    $category = $_POST["category"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];
    $description = $_POST["description"];

    $sql="INSERT INTO expense (name, category, amount, date, description) values (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssdss", $name, $category, $amount, $date, $description);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
      header('location:expense.php');
    }
    else {
      $msg = "無法新增資料";
    }
  }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="income_insert.css" />
    <title>新增支出</title>
</head>

<body>
<div class="container">
<h1 class="page-title">新增支出</h1>
<p class="page-subtitle">請填寫以下資訊來新增一筆支出記錄</p>

<form action="expense_insert.php" method="post">
<div class="form-group">
                <label for="name">
                    活動名稱
                    <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="例如：迎新晚會" 
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="category">
                    類別
                    <span class="required">*</span>
                </label>
                <select id="category" name="category" required>
                    <option value="">請選擇類別</option>
                    <option value="場地費用">場地費用</option>
                    <option value="餐飲費用">餐飲費用</option>
                    <option value="行政支出">行政支出</option>
                    <option value="設備費用">設備費用</option>
                    <option value="物品採購">物品採購</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="amount">
                    金額 (NT$)
                    <span class="required">*</span>
                </label>
                <input 
                    type="number" 
                    id="amount" 
                    name="amount" 
                    placeholder="0" 
                    min="0" 
                    step="1"
                    required
                >
                <div class="help-text">請輸入正整數金額</div>
            </div>
            
            <div class="form-group">
                <label for="date">
                    日期
                    <span class="required">*</span>
                </label>
                <input 
                    type="date" 
                    id="date" 
                    name="date" 
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="description">
                    說明
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="請輸入詳細說明（選填）"
                ></textarea>
                <div class="help-text">此欄位為選填，可以留空</div>
            </div>
            
            <div class="form-buttons">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='income_list.php'">
                    取消
                </button>
                <button type="submit" class="btn btn-submit">
                    儲存
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // 自動設定今天的日期為預設值
        document.getElementById('date').valueAsDate = new Date();
        
        // 表單驗證
        document.querySelector('form').addEventListener('submit', function(e) {
            const amount = document.getElementById('amount').value;
            
            if (amount <= 0) {
                e.preventDefault();
                alert('金額必須大於 0');
                return false;
            }
        });
    </script>
</body>
</html>

</form>
</div>

<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}?>
<?php
require_once "footer.php";
?>

