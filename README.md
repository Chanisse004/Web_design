# Web Design - Student Management System

## Project Overview
This project implements a complete student management system with user authentication and marks management functionality.

## Features

### Question 1: User Authentication System
- **Registration Form**: Create new user accounts with validation
- **Login Form**: Secure authentication with database verification
- **Database**: AccountDB with Credentials table
- **Session Management**: Secure login sessions with logout functionality

### Question 2: Student Marks Management System
- **Marks Entry Form**: Input student details and marks for different assessments
- **Calculations**:
  - Total Marks calculation
  - Average Marks calculation
  - Clear/Delete all fields functionality
- **Database Operations**:
  - Save records to MarksDB.RecordsTB table
  - Retrieve and display all records from database
- **Print Functionality**: Print the entire page
- **Database**: MarksDB with RecordsTB table

## File Structure
```
PHP Assignment/
├── index.php                 # Entry point (redirects to login)
├── login.php                 # Login form and authentication
├── register.php              # Registration form
├── marks_management.php      # Main marks management interface
├── logout.php                # Logout functionality
├── setup_databases.php       # Database setup script
├── README.md                 # This file
```

## Setup Instructions

### 1. Prerequisites
- XAMPP or similar web server with PHP and MySQL
- Web browser

### 2. Database Setup
1. Start Apache and MySQL services in XAMPP
2. Open your web browser and navigate to: `http://localhost/web%20design/setup_databases.php`
3. This will create:
   - AccountDB database with Credentials table
   - MarksDB database with RecordsTB table

### 3. Using the System

#### Registration and Login
1. Navigate to: `http://localhost/web%20design/`
2. Click on registration link to create a new account
3. After registration, login with your credentials
4. Successful login redirects to the marks management system

#### Marks Management
1. Enter student name and module name
2. Input marks for Test 1, Test 2, Assignment, and Exam
3. Use the buttons to:
   - **TOTAL MARKS**: Calculate sum of all marks
   - **AVERAGE**: Calculate average of all marks
   - **DELETE**: Clear all form fields
   - **SAVE in DB**: Save current record to database
   - **RETRIEVE from DB**: Display all saved records
   - **PRINT**: Print the current page

## Database Schema

### Database 1: AccountDB (Question 1 - User Authentication)

#### Credentials Table
| Field | Type | Description |
|-------|------|-------------|
| id | INT(6) UNSIGNED AUTO_INCREMENT | Primary Key |
| FIRST_NAME | VARCHAR(50) | First name |
| LAST_NAME | VARCHAR(50) | Last name |
| ADDRESS | TEXT | Address |
| TELEPHONE | VARCHAR(20) | Telephone number |
| USER_NAME | VARCHAR(50) | Unique user name |
| PASSWORD | VARCHAR(255) | Hashed password |
| created_at | TIMESTAMP | Account creation time |

### Database 2: MarksDB (Question 2 - Student Marks Management)

#### RecordsTB Table
| Field | Type | Description |
|-------|------|-------------|
| id | INT(6) UNSIGNED AUTO_INCREMENT | Primary Key |
| student_name | VARCHAR(100) | Student's full name |
| module_name | VARCHAR(100) | Module/course name |
| cat1_marks | DECIMAL(5,2) | CAT 1 marks (0-30) |
| cat2_marks | DECIMAL(5,2) | CAT 2 marks (0-30) |
| fat_marks | DECIMAL(5,2) | FAT marks (0-40) |
| total_marks | DECIMAL(5,2) | Calculated total marks (0-100) |
| average_marks | DECIMAL(5,2) | Calculated average marks |
| created_at | TIMESTAMP | Record creation time |

## Security Features
- Password hashing using PHP's password_hash()
- SQL injection prevention using prepared statements
- Session-based authentication
- Input validation and sanitization
- CSRF protection through form validation

## Technical Implementation
- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7+
- **Database**: MySQL/MariaDB
- **Styling**: Responsive design with gradient backgrounds
- **Validation**: Client-side and server-side validati
- Print functionality hides buttons and optimizes layout for printing
- All database operations use prepared statements for security
