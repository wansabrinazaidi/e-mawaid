<?php
session_start();
$conn = new mysqli("localhost", "root", "", "e-tempahan");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if ($password === $user['password']) {

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];  // Store user ID in the session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($role === 'admin') {
                header("Location: Admin Home.php");
            } else if ($role === 'user') {
                header("Location: Halaman Utama.php");
            }
            exit;
        } else {
            $error = "Invalid login credentials.";
        }
    } else {
        $error = "Invalid login credentials.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #EDF1F7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;
            font-size: 1.3rem;
            font-weight: bold;
            color: #2c3e50;
            text-align: left;
            padding: 10px 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .container {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            width: 90%;
            max-width: 1000px;
            display: flex;
            overflow: hidden;
        }

        .left-section {
            flex: 1;
            background-color: #f5f5f5;
            text-align: center;
            padding: 50px;
        }

        .left-section .logo img {
            max-width: 120px;
            margin-bottom: 20px;
        }

        .left-section h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .left-section h2 {
            font-size: 1.2rem;
            color: #7f8c8d;
        }

        .right-section {
            flex: 1;
            background-color: #fff;
            padding: 50px;
        }

        .form {
            width: 95%;
        }

        .role-selection {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .role-selection label {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            margin: 0 5px;
            background-color: #e8f4fd;
            border: 2px solid #3498db;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            font-size: 0.9rem;
            color: #3498db;
        }

        .role-selection input {
            display: none;
        }

        .role-selection input:checked+label {
            background-color: #3498db;
            color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 1rem;
            color: #34495e;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background-color: #4154F1;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            width: 30%;
        }

        button:hover {
            background-color: #3730a3;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-section {
                padding: 20px;
            }

            .right-section {
                padding: 20px;
            }

            .role-selection label {
                font-size: 0.8rem;
            }

            button {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <header>e-Mawāʿid , مواعيد </header>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <img src="logo.png" alt="JAHEAIK Logo">
            </div>
            <h1>TEMPAHAN PENGGUNAAN</h1>
            <h2>Bilik Mesyuarat dan Dewan</h2>
        </div>
        <div class="right-section">

            <form class="form" method="POST" action="login.php">
                <div class="role-selection">
                    <input type="radio" id="admin" name="role" value="admin" required>
                    <label for="admin">Admin</label>

                    <input type="radio" id="user" name="role" value="user" required>
                    <label for="user">Pengguna</label>
                </div>

                <div class="form-group">
                    <label for="username">Nama Pengguna</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group" style="position: relative;">
                    <label for="password">Kata Laluan</label>
                    <input type="password" id="password" name="password" required>
                    <img id="toggle-password" src="show.png" alt="Show Password" style="position: absolute; top: 69%; right: 5px; transform: translateY(-50%); width: 20px; cursor: pointer;">
                </div>

                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>

                <button type="submit">Masuk</button>
            </form>


            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePassword.src = 'hide.png';
                togglePassword.alt = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                togglePassword.src = 'show.png';
                togglePassword.alt = 'Show Password';
            }
        });
    </script>


</body>

</html>
