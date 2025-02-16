<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Получение данных для графика посещений по часам
$stmt = $pdo->prepare("SELECT strftime('%Y-%m-%d %H:00', timestamp) as hour, COUNT(DISTINCT ip) as visits FROM visits GROUP BY hour ORDER BY hour DESC LIMIT 24");
$stmt->execute();
$visitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение данных для круговой диаграммы по городам
$stmt = $pdo->prepare("SELECT city, COUNT(*) as count FROM visits GROUP BY city ORDER BY count DESC LIMIT 10");
$stmt->execute();
$cityData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Статистика Посещений</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h2>Статистика Посещений</h2>
    <p><a href="logout.php">Выйти</a></p>

    <h3>Уникальные посещения по часам (Последние 24 часа)</h3>
    <div class="chart-container">
        <canvas id="visitsChart"></canvas>
    </div>

    <h3>Распределение по городам</h3>
    <div class="chart-container">
        <canvas id="citiesChart"></canvas>
    </div>
</body>
<script>
    // Данные для графика посещений
    const visitData = <?php echo json_encode($visitData); ?>;
    const visitLabels = visitData.map(item => item.hour);
    const visitCounts = visitData.map(item => item.visits);

    const ctx1 = document.getElementById('visitsChart').getContext('2d');
    const visitsChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: visitLabels,
            datasets: [{
                label: 'Уникальные посещения',
                data: visitCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Время'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Количество посещений'
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        },
        
    });

    // Данные для круговой диаграммы городов
    const cityData = <?php echo json_encode($cityData); ?>;
    const cityLabels = cityData.map(item => item.city);
    const cityCounts = cityData.map(item => item.count);

    const ctx2 = document.getElementById('citiesChart').getContext('2d');
    const citiesChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: cityLabels,
            datasets: [{
                data: cityCounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(199, 199, 199, 0.6)',
                    'rgba(83, 102, 255, 0.6)',
                    'rgba(255, 99, 255, 0.6)',
                    'rgba(99, 255, 132, 0.6)'
                ],
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false

        }
    });
</script>
<style>
    .chart-container {
        width: 400px;
        height: 400px
    }
</style>

</html>