<?php 
$env = parse_ini_file(__DIR__ . '../../.env');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <script>
        // Redirect to another website
        window.location.href = "<?php echo $env['REDIRECT']; ?>"; // Using URL from .env
        </script>
</head>
<body>
<p>Redirecting to <a href="<?php echo $env['REDIRECT']; ?>"><?php echo $env['REDIRECT']; ?></a>...</p>
</body>
</html>
