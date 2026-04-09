<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #667eea;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            color: #27ae60;
            text-align: center;
            margin-bottom: 15px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $address = $_POST['address'];
            $telephone = $_POST['telephone'];
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];
            
            $errors = array();
            
            // Validation
            if (empty($first_name)) {
                $errors[] = "First Name is required";
            }
            if (empty($last_name)) {
                $errors[] = "Last Name is required";
            }
            if (empty($address)) {
                $errors[] = "Address is required";
            }
            if (empty($telephone)) {
                $errors[] = "Telephone is required";
            }
            if (empty($user_name)) {
                $errors[] = "User Name is required";
            }
            if (empty($password)) {
                $errors[] = "Password is required";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters";
            }
            
            if (empty($errors)) {
                // Database connection
                $servername = "localhost";
                $db_username = "root";
                $db_password = "";
                $dbname = "AccountDB";
                
                $conn = new mysqli($servername, $db_username, $db_password, $dbname);
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                // Check if user name already exists
                $check_sql = "SELECT id FROM Credentials WHERE USER_NAME = ?";
                $stmt = $conn->prepare($check_sql);
                $stmt->bind_param("s", $user_name);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    echo "<div class='error'>User Name already exists</div>";
                } else {
                    // Insert new user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $insert_sql = "INSERT INTO Credentials (FIRST_NAME, LAST_NAME, ADDRESS, TELEPHONE, USER_NAME, PASSWORD) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("ssssss", $first_name, $last_name, $address, $telephone, $user_name, $hashed_password);
                    
                    if ($stmt->execute()) {
                        echo "<div class='success'>Registration successful! You can now login.</div>";
                    } else {
                        echo "<div class='error'>Error: " . $stmt->error . "</div>";
                    }
                }
                
                $stmt->close();
                $conn->close();
            } else {
                foreach ($errors as $error) {
                    echo "<div class='error'>" . $error . "</div>";
                }
            }
        }
        ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            
            <div class="form-group">
                <label for="telephone">Telephone:</label>
                <input type="text" id="telephone" name="telephone" required>
            </div>
            
            <div class="form-group">
                <label for="user_name">User Name:</label>
                <input type="text" id="user_name" name="user_name" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" name="signup" class="btn" style="flex: 1;">Signup</button>
                <button type="button" onclick="window.location.href='login.php'" class="btn" style="flex: 1; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">Cancel</button>
            </div>
        </form>
        
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
