<?php

require_once "db.php"; 

$sql_expenses_sum = "SELECT category, SUM(amount) AS total_amount
    FROM expense
    GROUP BY category
    ORDER BY total_amount DESC";

$result_expenses = mysqli_query($conn, $sql_expenses_sum);

$total_all_expenses = 0; // 總支出金額
$chart_data = [];        // 儲存活動名稱和金額

if ($result_expenses) {
    // 遍歷結果，計算總支出並儲存數據
    while ($row = mysqli_fetch_assoc($result_expenses)) {
        $activity = $row['category'];
        $amount = (float)$row['total_amount']; // 確保金額是數字類型

        $total_all_expenses += $amount;
        
        $chart_data[] = [
            'activity' => $activity,
            'amount' => $amount
        ];
    }
    mysqli_free_result($result_expenses);
} else {
    // 查詢錯誤處理
    error_log("查詢支出數據時發生錯誤: " . mysqli_error($conn));
}

// 準備 Chart.js 所需的 PHP 陣列
$chart_labels = [];
$chart_data_values = [];

// 定義顏色組 (如果活動多於顏色，顏色會循環使用)
$chart_colors = [
    'rgba(224, 102, 91, 1)', 
    'rgba(222, 172, 172, 1)', 
    'rgba(230, 146, 179, 1)', 
    'rgb(255, 99, 132)', 
    'rgba(118, 35, 35, 1)' 
];
$background_colors = [];

if ($total_all_expenses > 0) {
    $i = 0;
    foreach ($chart_data as $data) {
        $activity = $data['activity'];
        $amount = $data['amount'];
        
        // 計算比例並取整數
        $percentage = round(($amount / $total_all_expenses) * 100); 

        // 填充 Chart.js 所需的陣列
        // 標籤格式為 "活動名稱 (金額)"，讓滑鼠懸停時更清楚
        $chart_labels[] = $activity . ' (NT$' . number_format($amount, 0) . ')'; 
        
        // 數據使用計算好的比例
        $chart_data_values[] = $percentage; 
        
        // 循環使用顏色
        $background_colors[] = $chart_colors[$i % count($chart_colors)];
        $i++;
    }
}

// 將 PHP 陣列安全地轉換為 JSON 格式，以便在 JavaScript 中使用
$json_labels = json_encode($chart_labels);
$json_data = json_encode($chart_data_values);
$json_colors = json_encode($background_colors);

?>

<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="card p-3">
    <div class="d-flex flex-column align-items-center">
        
        <h5 class=" text-center mb-3">各類別支出比例</h5>
        
        <div style="position: relative; height: 300px; width: 100%; max-width: 450px;">
            <canvas id="activityPieChart"></canvas>
        </div>
        
    </div>
</div>

<script>
    // =================================================================
    // 步驟 3: JavaScript 接收 PHP 數據並繪製圖表
    // =================================================================
    
    // 接收 PHP 輸出的 JSON 數據
    const dynamicLabels = <?php echo $json_labels; ?>;
    const dynamicData = <?php echo $json_data; ?>;
    const dynamicColors = <?php echo $json_colors; ?>;

    // 圓餅圖數據
    const pieData = {
        labels: dynamicLabels, // 使用動態生成的標籤 (含金額)
        datasets: [{
            data: dynamicData, // 使用動態計算的百分比
            backgroundColor: dynamicColors, // 使用動態顏色
            hoverOffset: 4
        }]
    };

    // 繪製圓餅圖的配置
    const pieConfig = {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom', // 將圖例放在底部
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            // 這裡只在提示中顯示計算出來的百分比，因為活動名稱和金額已在 Label 顯示
                            return context.parsed + '%';  
                        },
                        title: function(context) {
                            // 顯示完整的標籤 (活動名稱 + 金額)
                            return context[0].label;
                        }
                    }
                }
            }
        }
    };

    // 創建圖表實例
    new Chart(
        document.getElementById('activityPieChart'),
        pieConfig
    );
</script>

</body>
</html>