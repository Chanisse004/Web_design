<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection for marks operations
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "MarksDB";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle SAVE to database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'save') {
    $student_name = $_POST['student_name'];
    $module_name = $_POST['module_name'];
    $cat1_marks = $_POST['cat1_marks'];
    $cat2_marks = $_POST['cat2_marks'];
    $fat_marks = $_POST['fat_marks'];
    $total_marks = $_POST['total_marks'];
    $average_marks = $_POST['average_marks'];
    
    $sql = "INSERT INTO RecordsTB (student_name, module_name, cat1_marks, cat2_marks, fat_marks, total_marks, average_marks) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddddd", $student_name, $module_name, $cat1_marks, $cat2_marks, $fat_marks, $total_marks, $average_marks);
    
    if ($stmt->execute()) {
        $save_message = "Records saved successfully!";
    } else {
        $save_message = "Error saving records: " . $stmt->error;
    }
    
    $stmt->close();
}

// Handle RETRIEVE from database
$retrieved_records = array();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'retrieve') {
    $sql = "SELECT * FROM RecordsTB ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $retrieved_records[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marks Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #2d3436;
        }
        .user-info {
            text-align: right;
            margin-bottom: 20px;
            color: #2d3436;
        }
        .user-info a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }
        .form-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .input-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #2d3436;
            font-weight: bold;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #dfe6e9;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #74b9ff;
            outline: none;
        }
        .button-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
        }
        .btn-success {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: white;
        }
        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
        .btn-warning {
            background: linear-gradient(135deg, #fdcb6e 0%, #f39c12 100%);
            color: white;
        }
        .btn-info {
            background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
            color: white;
        }
        .db-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        .result-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .records-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .records-table th, .records-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .records-table th {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
        }
        .records-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
            }
            .button-group, .db-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Student Marks Management System</h1>
        </div>
        
        <div class="user-info">
            Welcome, <strong><?php echo $_SESSION['username']; ?></strong> | <a href="logout.php">Logout</a>
        </div>
        
        <?php if (isset($save_message)): ?>
            <div class="message <?php echo strpos($save_message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $save_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" id="marksForm">
            <div class="form-section">
                <div>
                    <div class="input-group">
                        <label for="student_name">Student Name:</label>
                        <input type="text" id="student_name" name="student_name" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="module_name">Module Name:</label>
                        <input type="text" id="module_name" name="module_name" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="cat1_marks">CAT 1 Marks (/30):</label>
                        <input type="number" id="cat1_marks" name="cat1_marks" step="0.01" min="0" max="30" required>
                    </div>
                </div>
                
                <div>
                    <div class="input-group">
                        <label for="cat2_marks">CAT 2 Marks (/30):</label>
                        <input type="number" id="cat2_marks" name="cat2_marks" step="0.01" min="0" max="30" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="fat_marks">FAT Marks (/40):</label>
                        <input type="number" id="fat_marks" name="fat_marks" step="0.01" min="0" max="40" required>
                    </div>
                </div>
            </div>
            
            <div class="button-group">
                <button type="button" class="btn btn-primary" onclick="calculateTotal()">TOTAL MARKS</button>
                <button type="button" class="btn btn-success" onclick="calculateAverage()">AVERAGE</button>
                <button type="button" class="btn btn-danger" onclick="clearFields()">DELETE</button>
            </div>
            
            <div class="input-group">
                <label for="total_marks">Total Marks:</label>
                <input type="number" id="total_marks" name="total_marks" step="0.01" readonly>
            </div>
            
            <div class="input-group">
                <label for="average_marks">Average Marks:</label>
                <input type="number" id="average_marks" name="average_marks" step="0.01" readonly>
            </div>
            
            <div class="db-buttons">
                <button type="submit" class="btn btn-info" name="action" value="save">SAVE in DB</button>
                <button type="submit" class="btn btn-warning" name="action" value="retrieve">RETRIEVE from DB</button>
            </div>
        </form>
        
        <div class="button-group">
            <button type="button" class="btn btn-primary" onclick="window.print()">PRINT</button>
        </div>
        
        <?php if (!empty($retrieved_records)): ?>
            <h3>Retrieved Records from Database</h3>
            <table class="records-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Module Name</th>
                        <th>CAT 1</th>
                        <th>CAT 2</th>
                        <th>FAT</th>
                        <th>Total</th>
                        <th>Average</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($retrieved_records as $record): ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo htmlspecialchars($record['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['module_name']); ?></td>
                        <td><?php echo $record['cat1_marks']; ?></td>
                        <td><?php echo $record['cat2_marks']; ?></td>
                        <td><?php echo $record['fat_marks']; ?></td>
                        <td><?php echo $record['total_marks']; ?></td>
                        <td><?php echo $record['average_marks']; ?></td>
                        <td><?php echo $record['created_at']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <script>
        function calculateTotal() {
            const cat1 = parseFloat(document.getElementById('cat1_marks').value) || 0;
            const cat2 = parseFloat(document.getElementById('cat2_marks').value) || 0;
            const fat = parseFloat(document.getElementById('fat_marks').value) || 0;
            
            const total = cat1 + cat2 + fat;
            document.getElementById('total_marks').value = total.toFixed(2);
        }
        
        function calculateAverage() {
            const cat1 = parseFloat(document.getElementById('cat1_marks').value) || 0;
            const cat2 = parseFloat(document.getElementById('cat2_marks').value) || 0;
            const fat = parseFloat(document.getElementById('fat_marks').value) || 0;
            
            const total = cat1 + cat2 + fat;
            const average = total / 3;
            
            document.getElementById('total_marks').value = total.toFixed(2);
            document.getElementById('average_marks').value = average.toFixed(2);
        }
        
        function clearFields() {
            if (confirm('Are you sure you want to clear all fields?')) {
                document.getElementById('student_name').value = '';
                document.getElementById('module_name').value = '';
                document.getElementById('cat1_marks').value = '';
                document.getElementById('cat2_marks').value = '';
                document.getElementById('fat_marks').value = '';
                document.getElementById('total_marks').value = '';
                document.getElementById('average_marks').value = '';
            }
        }
    </script>
</body>
</html>
