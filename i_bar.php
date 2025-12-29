<?php
require_once "db.php"; 

// SQL：按分類 (category) 加總金額 (amount)
$sql_bar_income = "SELECT category, SUM(amount) AS total_amount 
                   FROM income 
                   GROUP BY category 
                   ORDER BY total_amount DESC";

$result_bar = mysqli_query($conn, $sql_bar_income);

$bar_labels = [];
$bar_data_values = [];

if ($result_bar) {
    while ($row = mysqli_fetch_assoc($result_bar)) {
        // 縱軸名稱：A, B, C
        $bar_labels[] = $row['category'];
        // 橫軸數值：金額
        $bar_data_values[] = (float)$row['total_amount'];
    }
    mysqli_free_result($result_bar);
}

// 轉為 JSON 格式供 JavaScript 使用
$json_bar_labels = json_encode($bar_labels);
$json_bar_data = json_encode($bar_data_values);
?>
<div class="card p-3">
    <h5 class="text-center">各類別收入比例</h5>
    <div style="height: 310px;">
        <canvas id="incomeHorizontalBarChart"></canvas>
    </div>
</div>

<script>
(function() {
    const barLabels = <?php echo $json_bar_labels; ?>;
    const barData = <?php echo $json_bar_data; ?>;

    new Chart(document.getElementById('incomeHorizontalBarChart'), {
        type: 'bar', // 類型依然是 bar
        data: {
            labels: barLabels, // 縱軸名稱 (A, B, C)
            datasets: [{
                label: '總收入金額 (NT$)',
                data: barData, // 橫軸長度 (金額)
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)', // 顏色可以依需求調整
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(103, 135, 186, 0.8)'
                ],
                borderColor: [
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(103, 135, 186)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // ⭐ 關鍵：將索引軸設為 Y，使圖表變橫向
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // 隱藏圖例，讓畫面更簡潔
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: { display: true, text: '金額 (NT$)' }
                },
                y: {
                    title: { display: true, text: '收入類別' }
                }
            }
        }
    });
})();
</script>