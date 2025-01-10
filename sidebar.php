<?php
$logo_query = "SELECT setting_value FROM settings WHERE setting_key = 'sidebar_logo' LIMIT 1";
$logo_result = $conn->query($logo_query);
$logo_path = 'static_pages/images/logo (1).svg'; // Default path

if ($logo_result && $logo_result->num_rows > 0) {
    $logo_path = $logo_result->fetch_assoc()['setting_value'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Static Page</title>
    <!-- Bootstrap CSS for responsive design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Scrollbar Styling */
/* For Webkit browsers (Chrome, Safari) */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: #a3b1c6;
    border-radius: 8px;
    border: 2px solid #f1f1f1;
    transition: all 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background: #888;
}

::-webkit-scrollbar-corner {
    background: #f1f1f1;
}

/* For Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: #a3b1c6 #f1f1f1;
}

/* Table specific scrollbar */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 6px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 6px;
    border: 2px solid #f8f9fa;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Chart container scrollbar */
.chart-container::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.chart-container::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 4px;
}

.chart-container::-webkit-scrollbar-thumb {
    background: #94a3b8;
    border-radius: 4px;
    border: 1px solid #f8fafc;
}

.chart-container::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* Main content scrollbar */
#main-content::-webkit-scrollbar {
    width: 12px;
}

#main-content::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 8px;
}

#main-content::-webkit-scrollbar-thumb {
    background: #94a3b8;
    border-radius: 8px;
    border: 3px solid #f1f5f9;
}

#main-content::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* For modal scrollbars if you have any */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 6px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 6px;
    border: 2px solid #f8fafc;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --primary-color: #4a90e2;
            --hover-color: #f5f9ff;
            --text-color: #2c3e50;
            --active-color: #e8f1fc;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 1000;
            padding-top: 1rem;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        #toggle-sidebar {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            display: none;
        }

        #toggle-sidebar:hover {
            background: var(--hover-color);
        }

        .logo-container {
            padding: 1rem;
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .logo-container img {
            max-width: 80%;
            height: auto;
        }

        #sidebar a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.2rem 0.8rem;
            border-radius: 8px;
        }

        #sidebar a:hover {
            background: var(--hover-color);
            transform: translateX(5px);
        }

        #sidebar a.active {
            background: var(--active-color);
            color: var(--primary-color);
            font-weight: 500;
        }

        #sidebar a i {
            min-width: 2rem;
            font-size: 1.2rem;
            margin-right: 0.8rem;
            transition: all 0.3s ease;
        }

        #sidebar a.orange-color {
            color: #ff6b6b;
            margin-top: auto;
        }

        #sidebar a.orange-color:hover {
            background: #fff1f1;
        }

        @media (max-width: 768px) {
            #toggle-sidebar {
                display: block;
            }

            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.active {
                transform: translateX(0);
            }
        }

        /* Glassmorphism effect for active items */
        #sidebar a.active::before {
            content: '';
            position: absolute;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
            border-radius: 0 4px 4px 0;
        }

        /* Hover animation for icons */
        #sidebar a:hover i {
            transform: scale(1.1);
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <button id="toggle-sidebar" class="btn">
        <i class="fas fa-bars"></i>
    </button>

    <?php
        $current_page = basename($_SERVER['PHP_SELF']);
    ?>

    <nav id="sidebar">

    <div class="logo-container">
    <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="Logo" class="logo">
</div>

        <a class="navbar-brand <?php if ($current_page == 'admin.php') echo 'active'; ?>" href="admin.php">
            <i class="fas fa-home"></i> 
            <span>Home</span>
        </a>
        <a href="create_page.php" class="<?php if ($current_page == 'create_page.php') echo 'active'; ?>">
            <i class="fas fa-plus"></i>
            <span>Create New Page</span>
        </a>
        <a href="extend_coupons.php" class="<?php if ($current_page == 'extend_coupons.php') echo 'active'; ?>">
            <i class="fas fa-clock"></i>
            <span>Extend Coupons</span>
        </a>
        <a href="todo_list.php" class="<?php if ($current_page == 'todo_list.php') echo 'active'; ?>">
            <i class="fas fa-tasks"></i>
            <span>Add New Task</span>
        </a>
        <a href="users_management.php" class="<?php if ($current_page == 'users_management.php') echo 'active'; ?>">
            <i class="fas fa-users"></i>
            <span>Users Management</span>
        </a>

        <a href="language_management.php" class="<?php if ($current_page == 'language_management.php') echo 'active'; ?>">
    <i class="fas fa-language"></i>
    <span>Languages</span>
</a>

        <a href="settings.php" class="<?php if ($current_page == 'settings.php') echo 'active'; ?>">
    <i class="fas fa-cog"></i>
    <span>Settings</span>
</a>

        <a href="logout.php" class="orange-color <?php if ($current_page == 'logout.php') echo 'active'; ?>">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        
    </nav>

    <script>
        // Toggle sidebar on mobile
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Add active class to current page link
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('#sidebar a').forEach(el => 
                    el.classList.remove('active')
                );
                this.classList.add('active');
            });
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>