<?php 
// Read and parse .env file
$env = parse_ini_file(__DIR__ . '../../.env');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Contact Us</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --background-color: #f8f9fa;
            --border-color: #e9ecef;
            --text-color: #2c3e50;
            --accent-color: #4a90e2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.1rem;
            color: var(--secondary-color);
        }

        .contact-info {
            display: grid;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .contact-card {
            background: #ffffff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
        }

        .contact-card h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-card i {
            color: var(--accent-color);
        }

        .contact-card p {
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .contact-card a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .contact-card a:hover {
            color: var(--primary-color);
        }

        .business-hours {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
        }

        .business-hours h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .business-hours p {
            margin-bottom: 0.5rem;
        }

        .floating-button {
            position: fixed;
            bottom: 100px;
            right: 15px;
            width: 50px;
            height: 50px;
            background-color: var(--accent-color);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .floating-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background-color: #357abd;
            color: white;
        }

        .floating-button i {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2rem;
            }

            .floating-button {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        
        <div class="welcome-message">
            <p>We're here to help! Feel free to reach out to us using any of the contact methods below.</p>
            <p>Our friendly team is always ready to assist you with any questions or concerns.</p>
        </div>

        <div class="contact-info">
            <div class="contact-card">
                <h2><i class="fas fa-phone"></i> Phone Support</h2>
                <p>Customer Service:</p>
                <p><a href="tel:+1-555-123-4567">+1 (555) 123-4567</a></p>
                <p>Technical Support:</p>
                <p><a href="tel:+1-555-987-6543">+1 (555) 987-6543</a></p>
            </div>

            <div class="contact-card">
                <h2><i class="fas fa-envelope"></i> Email</h2>
                <p>General Inquiries:</p>
                <p><a href="mailto:info@aliexpone.com">info@aliexpone.com</a></p>
                <p>Support:</p>
                <p><a href="mailto:support@aliexpone.com">support@aliexpone.com</a></p>
            </div>

            <div class="contact-card">
                <h2><i class="fas fa-location-dot"></i> Location</h2>
                <p>Aliexpone Headquarters</p>
                <p>123 Business Avenue</p>
                <p>Tech District, CA 94105</p>
                <p>United States</p>
            </div>
        </div>

        <div class="business-hours">
            <h2><i class="fas fa-clock"></i> Business Hours</h2>
            <p>Monday - Friday: 9:00 AM - 6:00 PM (PST)</p>
            <p>Saturday: 10:00 AM - 4:00 PM (PST)</p>
            <p>Sunday: Closed</p>
            <p><small>* Holiday hours may vary</small></p>
        </div>
    </div>

    <!-- Floating Back Button -->
    <a href="<?php echo $env['REDIRECT'] ?>" class="floating-button">
        <i class="fas fa-home"></i>
    </a>
</body>
</html>