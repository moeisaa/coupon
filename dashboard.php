<?php
include('db_connect.php');
// Add this at the top of dashboard.php after database connection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['positions'])) {
    $positions = json_decode($_POST['positions'], true);
    foreach ($positions as $position => $couponId) {
        $stmt = $conn->prepare("UPDATE coupons SET position = ? WHERE id = ?");
        $stmt->bind_param("ii", $position, $couponId);
        $stmt->execute();
    }
    // Return JSON response instead of plain text
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
    exit;
}

// Get page_id from query parameter
$pageId = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;
if ($pageId <= 0) {
    die("Invalid Page ID.");
}

// First fetch page details
$stmt = $conn->prepare("SELECT * FROM pages WHERE id = ?");
$stmt->bind_param("i", $pageId);
$stmt->execute();
$page = $stmt->get_result()->fetch_assoc();
$stmt->close();

// posotions 
$stmt = $conn->prepare("SELECT * FROM coupons WHERE page_id = ? ORDER BY position");
$stmt->bind_param("i", $pageId);
$stmt->execute();
$coupons = $stmt->get_result();
$stmt->close(); 

// Fetch coupons associated with the page with proper ordering
$stmt = $conn->prepare("SELECT * FROM coupons WHERE page_id = ? ORDER BY position ASC, id ASC");
$stmt->bind_param("i", $pageId);
$stmt->execute();
$coupons = $stmt->get_result();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($page['route']); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
       <!-- Drag Drop -->
       <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>

        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f8f9fa;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --text-color: #2c3e50;
            --border-color: #e9ecef;
            --hover-bg: #f8f9fa;
        }

        body {
            background-color: #f5f7fb;
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
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

        .btns {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .create-btn {
            background-color: var(--primary-color);
            color: white;
        }

        .extend-btn {
            background-color: var(--success-color);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .table-container h2 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        .table {
            margin: 0;
            width: 100% !important;
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
        }

        .table tbody tr:hover {
            background-color: var(--hover-bg);
        }

        /* Action Buttons */
        .action-btn-group {
            display: flex;
            gap: 0.5rem;
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
            padding: 0.5rem 1rem;
        }

        .delete-btn {
            background-color: var(--danger-color);
            color: white;
            padding: 0.5rem 1rem;
        }

        .no-coupons {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            color: var(--text-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .header-nav {
                flex-direction: column;
                gap: 1rem;
            }

            .btns {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .table-responsive {
                margin-right: 0 !important;
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





        /* Drag Drop */
                /* Add to your existing styles */
                .sortable-row {
            cursor: move;
        }
        .ui-sortable-helper {
            background: white !important;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .sort-handle {
            cursor: move;
            padding: 10px;
            color: #666;
        }
        .sort-handle:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div id="main-content">
        <div class="header-nav d-flex justify-content-between align-items-center">
            <h1>Dashboard for Store: <?php echo htmlspecialchars($page['store_name']); ?></h1>
            <div class="btns">
                <a href="create_coupon.php?page_id=<?php echo $pageId; ?>" class="btn create-btn">
                    <i class="fas fa-plus"></i>
                    Create New Coupon
                </a>
                <a href="extend_coupon_expiry.php?page_id=<?php echo $pageId; ?>" class="btn extend-btn">
                    <i class="fas fa-clock"></i>
                    Extend Expiry Dates
                </a>
            </div>
        </div>

        <?php if ($coupons->num_rows > 0): ?>
            <div class="table-container">
                <h2>Manage Coupons</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Expire Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-coupons">
        <?php while ($coupon = $coupons->fetch_assoc()): ?>
            <tr class="sortable-row" data-id="<?php echo $coupon['id']; ?>">
                <td>
                    <i class="fas fa-grip-vertical sort-handle"></i>
                    <?php echo htmlspecialchars($coupon['description']); ?>
                </td>
                <td><?php echo htmlspecialchars($coupon['type']); ?></td>
                <td><?php echo htmlspecialchars($coupon['expire_date']); ?></td>
                <td>
                    <div class="action-btn-group">
                        <a class="btn btn-success" href="edit_coupon.php?id=<?php echo $coupon['id']; ?>">
                            <i class='fas fa-edit'></i>
                        </a>
                        <a class="btn delete-btn" href="delete_coupon.php?id=<?php echo $coupon['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this coupon?')">
                            <i class='fas fa-trash'></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
                    </table>


                    <!-- Add this HTML after your table -->
<div id="successMessage" class="alert alert-success position-fixed top-0 end-0 m-3" style="display: none; z-index: 1050;">
    Order updated successfully!
</div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-coupons">
                <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                <h3>No Coupons Found</h3>
                <p>Start by creating a new coupon for this page.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('expanded');
        });
    </script>

    <!-- Drag Drop -->
     
    <script>
$(document).ready(function() {
    let isDragging = false;
    
    $("#sortable-coupons").sortable({
        handle: ".sort-handle",
        axis: "y",
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        start: function(event, ui) {
            isDragging = true;
        },
        update: function(event, ui) {
            let positions = {};
            $('.sortable-row').each(function(index) {
                positions[index] = $(this).data('id');
            });

            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: {
                    positions: JSON.stringify(positions)
                },
                success: function(response) {
                    if(response.success) {
                        // Show success message
                        $('#successMessage')
                            .fadeIn()
                            .delay(2000)
                            .fadeOut();
                            
                        // Optional: Refresh the page after a short delay
                        // setTimeout(function() {
                        //     window.location.reload();
                        // }, 2500);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating positions:', error);
                    alert('Error updating positions. Please try again.');
                }
            });
        }
    }).disableSelection();
});
</script>
<style>
.alert {
    transition: opacity 0.5s ease-in-out;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.ui-sortable-helper {
    display: table;
    background: white !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.sortable-row {
    background: white;
}

.sortable-row.ui-sortable-helper {
    opacity: 0.9;
}

.sortable-placeholder {
    background-color: #f8f9fa !important;
    height: 56px;
    border: 2px dashed #dee2e6;
}

.sort-handle {
    display: inline-block;
    margin-right: 10px;
    color: #6c757d;
    cursor: move;
}

.sort-handle:hover {
    color: #343a40;
}
</style>
</body>
</html>