<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit;
}

$env = parse_ini_file(__DIR__ . '/.env');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = $env['DB_HOST'];
    $username = $env['DB_USERNAME'];
    $password = $env['DB_PASSWORD'];
    $dbname = $env['DB_NAME'];
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                
                // Instead of redirecting immediately, show the loader
                echo '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Loading...</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                    <style>
                        body {
                            margin: 0;
                            padding: 0;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-height: 100vh;
                            background: white;
                            font-family: "Segoe UI", sans-serif;
                        }
                        
                        .loader-container {
                            text-align: center;
                        }
                        
                        .brand-name {
                            font-size: 2.5rem;
                            font-weight: 700;
                            color: #000;
                            margin-bottom: 0.5rem;
                            opacity: 0;
                            animation: fadeIn 0.8s ease-out forwards;
                        }
                        
                        .edition {
                            font-size: 1.2rem;
                            color: #666;
                            margin-bottom: 0.5rem;
                            opacity: 0;
                            animation: fadeIn 0.8s ease-out 0.2s forwards;
                        }
                        
                        .owner {
                            font-size: 1rem;
                            color: #888;
                            margin-bottom: 2rem;
                            opacity: 0;
                            animation: fadeIn 0.8s ease-out 0.4s forwards;
                        }
                        
                        .loader {
                            width: 120px;
                            height: 120px;
                            position: relative;
                            margin: 0 auto;
                        }
                        
                        .loader-ring {
                            width: 100%;
                            height: 100%;
                            border-radius: 50%;
                            border: 3px solid transparent;
                            border-top-color: #000;
                            animation: spin 1s linear infinite;
                            position: absolute;
                        }
                        
                        .loader-ring:nth-child(2) {
                            width: 80%;
                            height: 80%;
                            top: 10%;
                            left: 10%;
                            border-top-color: transparent;
                            border-right-color: #000;
                            animation: spin 1.5s linear reverse infinite;
                        }
                        
                        .percentage {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            font-size: 1.5rem;
                            font-weight: 600;
                            color: #000;
                        }
                        
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        
                        @keyframes fadeIn {
                            from { opacity: 0; transform: translateY(10px); }
                            to { opacity: 1; transform: translateY(0); }
                        }
                    </style>
                </head>
                <body>
                    <div class="loader-container">
                        <div class="brand-name">Couponation</div>
                        <div class="edition">Unique Edition</div>
                        <div class="owner">for Mr. Mahmoud Eisa</div>
                        
                        <div class="loader">
                            <div class="loader-ring"></div>
                            <div class="loader-ring"></div>
                            <div class="percentage">0%</div>
                        </div>
                    </div>

                    <script>
                        // Update percentage and redirect
                        const percentage = document.querySelector(".percentage");
                        let progress = 0;
                        
                        const updateProgress = () => {
                            if (progress < 100) {
                                progress += Math.floor(Math.random() * 10) + 5;
                                if (progress > 100) progress = 100;
                                percentage.textContent = progress + "%";
                                
                                if (progress === 100) {
                                    setTimeout(() => {
                                        window.location.href = "admin.php";
                                    }, 500);
                                } else {
                                    setTimeout(updateProgress, 100);
                                }
                            }
                        };
                        
                        setTimeout(updateProgress, 500);
                        
                        // Fallback redirect
                        setTimeout(() => {
                            window.location.href = "admin.php";
                        }, 3000);
                    </script>
                </body>
                </html>';
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }

        $stmt->close();
    } else {
        $error = "Please fill in both fields.";
    }

    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f8f9fa;
            --text-color: #2c3e50;
            --error-color: #e74c3c;
            --gradient-start: #4a90e2;
            --gradient-end: #67b26f;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            margin: 0;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .bg-animation span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.6s ease-out forwards;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--text-color);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-label {
            color: var(--text-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .input-group:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .input-group-text {
            background: white;
            border: none;
            color: var(--primary-color);
            padding: 0.75rem 1rem;
        }

        .form-control {
            border: none;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            background: white;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: transparent;
        }

        .login-btn {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            padding: 0.75rem 2.5rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .login-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shine 2s infinite;
        }

        .alert {
            background: rgba(231, 76, 60, 0.1);
            border: none;
            color: var(--error-color);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes animate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes shine {
            100% {
                left: 100%;
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Generate floating elements for background */
        <?php
        for($i = 1; $i <= 10; $i++) {
            echo "
            .bg-animation span:nth-child($i) {
                left: " . rand(0, 100) . "%;
                width: " . rand(30, 100) . "px;
                height: " . rand(30, 100) . "px;
                animation-delay: " . ($i * 0.2) . "s;
                animation-duration: " . rand(10, 30) . "s;
            }
            ";
        }
        ?>
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation">
        <?php for($i = 1; $i <= 10; $i++) echo "<span></span>"; ?>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Welcome Back!</h2>
                <p class="text-muted">Please login to continue</p>
            </div>
            
            <form method="post" action="login.php">
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               class="form-control" 
                               required 
                               autocomplete="username" 
                               placeholder="Enter your username">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               required 
                               autocomplete="current-password" 
                               placeholder="Enter your password">
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Sign In
                </button>

                <?php if (isset($error)): ?>
                    <div class="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
