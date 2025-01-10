<?php

// Include database connection
include('db_connect.php');

$env = parse_ini_file(__DIR__ . '/.env');


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
    // Fetch data for charts
    $data = [];
    $chartSql = "SELECT store_name, COUNT(id) AS count FROM pages GROUP BY store_name";
    $chartResult = $conn->query($chartSql);
    if ($chartResult) {
        while ($row = $chartResult->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Fetch data for coupon chart
    $couponData = [];
    $couponSql = "SELECT type AS coupon_type, COUNT(id) AS count FROM coupons GROUP BY type";
    $couponResult = $conn->query($couponSql);
    if ($couponResult) {
        while ($row = $couponResult->fetch_assoc()) {
            $couponData[] = $row;
        }
    }
    // Add this after your existing coupon chart query
    $themeData = [];
    $themeSql = "SELECT COALESCE(theme, 'theme1') as theme, COUNT(id) AS count 
                FROM pages 
                WHERE theme IN ('theme1', 'theme2') 
                GROUP BY COALESCE(theme, 'theme1')";
    $themeResult = $conn->query($themeSql);
    if ($themeResult) {
        while ($row = $themeResult->fetch_assoc()) {
            $themeData[] = $row;
        }
    }


    $usageData = [];
$usageSql = "SELECT p.store_name, p.route, SUM(c.daily_uses) as total_uses 
             FROM pages p 
             JOIN coupons c ON p.id = c.page_id 
             GROUP BY p.id 
             ORDER BY total_uses DESC 
             LIMIT 5";
$usageResult = $conn->query($usageSql);
if ($usageResult) {
    while ($row = $usageResult->fetch_assoc()) {
        $usageData[] = $row;
    }
}


    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Pages</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            :root {
                --primary-color: #4a90e2;
                --secondary-color: #f8f9fa;
                --text-color: #2c3e50;
                --border-color: #e9ecef;
                --danger-color: #dc3545;
                --success-color: #28a745;
            }

            body {
                background-color: #f5f7fb;
                color: var(--text-color);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            #main-content {
                margin-left: 280px;
                padding: 2rem;
                transition: margin-left 0.3s ease;
            }

            .header-nav {
                background: white;
                padding: 1.5rem 2rem;
                border-radius: 15px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                margin-bottom: 2rem;
            }

            .header-nav h1 {
                font-size: 1.8rem;
                font-weight: 600;
                color: var(--text-color);
                margin: 0;
            }

            /* Table Styles */
            .table-container {
                background: white;
                border-radius: 15px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            .table {
                margin: 0;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table thead th {
                background: var(--secondary-color);
                border: none;
                padding: 1rem;
                font-weight: 600;
                color: var(--text-color);
            }

            .table tbody td {
                padding: 1rem;
                vertical-align: middle;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-color);
            }

            .table tbody tr:hover {
                background-color: #f8f9fa;
            }

            /* Button Styles */
            .btn {
                padding: 0.5rem 1rem;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .edit-btn {
                color: var(--primary-color);
            }

            .delete-btn {
                color: var(--danger-color);
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }

            /* Chart Containers */
            .charts-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                margin-top: 2rem;
            }

            .chart-container {
                background: white;
                border-radius: 15px;
                padding: 1.5rem;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }

            .chart-container h2 {
                font-size: 1.2rem;
                margin-bottom: 1.5rem;
                color: var(--text-color);
            }

            /* Route Link Style */
            .route-link {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 500;
            }

            .route-link:hover {
                text-decoration: underline;
            }

            /* Action Buttons Container */
            .action-btns {
                display: flex;
                gap: 0.5rem;
                justify-content: flex-start;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                #main-content {
                    margin-left: 0;
                    padding: 1rem;
                }

                .header-nav {
                    padding: 1rem;
                }

                .table-container {
                    padding: 1rem;
                }

                .charts-section {
                    grid-template-columns: 1fr;
                }
            }

            /* Custom Scrollbar */
            .table-responsive::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            .table-responsive::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }

            .table-responsive::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            
        </style>
    </head>
    <body>
        <?php include('sidebar.php'); ?>

        <div id="main-content">
            <div class="header-nav">
                <h1>Manage Your Pages</h1>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Route</th>
                                <th>Store Name</th>
                                <th>Created At</th>
                                <th>Tag</th>  <!-- New column -->
                                <th>Page Coupons</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT *, created_at FROM pages";
                            $result = $conn->query($sql);

                            if ($result) {
                                while ($row = $result->fetch_assoc()) {
                                    $pageId = $row['id'];
                                    $route = htmlspecialchars($row['route']);
                                    $storeName = htmlspecialchars($row['store_name']);
                                    $createdAt = htmlspecialchars($row['created_at']);
                                    $tag = htmlspecialchars($row['tag'] ?? '');
                                    $fullUrl = $env['DOMAIN']."/".$route;
                                
                                    echo "<tr>
                                        <td><a href='" . $fullUrl . "' class='route-link'>" . $route . "</a></td>
                                        <td>" . $storeName . "</td>
                                        <td>" . $createdAt . "</td>
                                        <td>
                                            <div class='tag-container'>
                                                <span class='page-tag' style='background-color: #e9ecef; padding: 2px 8px; border-radius: 12px; font-size: 0.85em;'>" 
                                                    . ($tag ?: 'No tag') . 
                                                "</span>
                                                <button class='btn btn-sm edit-tag-btn' data-page-id='" . $pageId . "' data-current-tag='" . $tag . "'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <a href='dashboard.php?page_id=$pageId' class='btn position-relative' title='Manage Coupons'>
                                                <i class='fas fa-tags' style='color: #4a90e2; font-size: 1.2em;'></i>
                                                <span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary' style='font-size: 0.7em;'>
                                                    <i class='fas fa-plus'></i>
                                                </span>
                                            </a>
                                        </td>
                                        <td class='action-btns'>
                                            <a href='edit_page.php?id=$pageId' class='btn edit-btn'>
                                                <i class='fas fa-edit'></i>
                                            </a>
                                            <a href='delete_page.php?id=$pageId' class='btn delete-btn' onclick='return confirm(\"Are you sure?\")'>
                                                <i class='fas fa-trash'></i>
                                            </a>
                                        </td>
                                    </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="charts-section">
                <div class="chart-container">
                    <h2>Coupon Types</h2>
                    <canvas id="couponChart"></canvas>
                </div>
        <div class="chart-container">
            <h2>Theme Distribution</h2>
            <canvas id="themeChart"></canvas>
        </div>
            </div>
            <div class="charts-section">

            <div class="chart-container">
                    <h2>Store Distribution</h2>
                    <canvas id="storeChart"></canvas>
                    
                </div>
                <div class="chart-container">
    <h2>Top 5 Pages by Coupon Usage</h2>
    <canvas id="usageChart"></canvas>
</div>
</div>
        </div>

        

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Initialize charts with improved styling
            const ctx1 = document.getElementById('storeChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($data, 'store_name')); ?>,
                    datasets: [{
                        label: 'Number of Pages',
                        data: <?php echo json_encode(array_column($data, 'count')); ?>,
                        backgroundColor: 'rgba(74, 144, 226, 0.2)',
                        borderColor: 'rgba(74, 144, 226, 1)',
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            const ctx2 = document.getElementById('couponChart').getContext('2d');
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_column($couponData, 'coupon_type')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($couponData, 'count')); ?>,
                        backgroundColor: [
                            'rgba(74, 144, 226, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: [
                            'rgba(74, 144, 226, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });



        </script>

    <script>
        const ctx3 = document.getElementById('themeChart').getContext('2d');
    new Chart(ctx3, {
        type: 'polarArea',
        data: {
            labels: <?php 
                $themeLabels = array_map(function($item) {
                    return $item['theme'] === 'theme1' ? 'Classic Theme' : 'Modern Theme';
                }, $themeData);
                echo json_encode($themeLabels);
            ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($themeData, 'count')); ?>,
                backgroundColor: [
                    'rgba(74, 144, 226, 0.7)',  // Blue for Classic
                    'rgba(255, 99, 132, 0.7)'   // Pink for Modern
                ],
                borderColor: [
                    'rgba(74, 144, 226, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true, // Changed to true
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value} pages (${percentage}%)`;
                        }
                    }
                }
            },
            layout: {
                padding: {
                    top: 20,
                    bottom: 20
                }
            },
            cutout: '60%'
        }
    });
    </script>

<script>
const ctx4 = document.getElementById('usageChart').getContext('2d');
new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($usageData, 'store_name')); ?>,
        datasets: [{
            label: 'Total Coupon Uses',
            data: <?php echo json_encode(array_column($usageData, 'total_uses')); ?>,
            backgroundColor: 'rgba(234, 45, 87, 0.2)',
            borderColor: 'rgba(234, 45, 87, 1)',
            borderWidth: 2,
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.raw} uses`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Total Uses'
                }
            }
        }
    }
});
</script>

<script>document.addEventListener('DOMContentLoaded', function() {
    // Handle tag editing
    document.querySelectorAll('.edit-tag-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pageId = this.dataset.pageId;
            const currentTag = this.dataset.currentTag;
            const newTag = prompt('Enter new tag:', currentTag);
            
            if (newTag !== null) {
                // Send AJAX request to update tag
                fetch('update_tag.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `page_id=${encodeURIComponent(pageId)}&tag=${encodeURIComponent(newTag)}`
                })
                .then(async response => {
                    try {
                        const data = await response.json();
                        if (data.success) {
                            // Update the tag display
                            const tagSpan = this.parentElement.querySelector('.page-tag');
                            tagSpan.textContent = newTag || 'No tag';
                            this.dataset.currentTag = newTag;
                            location.reload(); // Refresh the page to update all instances
                        } else {
                            console.error('Server error:', data.message);
                        }
                    } catch (e) {
                        console.error('JSON parsing error:', e);
                        // Still update the UI if the database update was successful
                        const tagSpan = this.parentElement.querySelector('.page-tag');
                        tagSpan.textContent = newTag || 'No tag';
                        this.dataset.currentTag = newTag;
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                });
            }
        });
    });
});
</script>
    </body>
    </html>