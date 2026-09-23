# student-task-project
Student Task Management System because it naturally supports authentication, CRUD, search/filtering, and a relational database without becoming unnecessarily complicated

# Student Task Management System

A web-based Student Task Management System developed using **HTML, CSS, JavaScript, PHP, and MySQL**.

The system allows students to create an account, securely log in, manage academic tasks, search and filter tasks, and track task progress through a personal dashboard.

## Features

* Student registration
* Secure login and logout
* PHP session authentication
* Password hashing using `password_hash()`
* Password verification using `password_verify()`
* Personal student dashboard
* Create tasks
* View tasks
* Edit tasks
* Delete tasks
* Search tasks by title or description
* Filter tasks by status
* Filter tasks by priority
* Task statistics
* User profile
* Server-side validation
* Prepared SQL statements
* User-specific task access control
* Responsive design for desktop and mobile devices

## Technologies Used

| Technology | Purpose                                 |
| ---------- | --------------------------------------- |
| HTML5      | Page structure                          |
| CSS3       | Styling and responsive design           |
| JavaScript | Client-side validation and interaction  |
| PHP        | Server-side programming                 |
| MySQL      | Relational database                     |
| PDO        | Database access                         |
| Git/GitHub | Version control and source-code hosting |
| XAMPP      | Local development environment           |

## System Requirements

To run the project locally, you need:

* PHP 8.x or compatible version
* MySQL
* Apache
* XAMPP, WAMP, or another PHP development environment
* Web browser
* phpMyAdmin or another MySQL administration tool

## Project Structure

```text
student-task-manager/
│
├── index.php
├── login.php
├── register.php
├── logout.php
├── dashboard.php
├── profile.php
├── add_task.php
├── edit_task.php
├── delete_task.php
│
├── config/
│   └── database.php
│
├── includes/
│   └── auth.php
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── database/
│   └── database.sql
│
└── README.md
```

## Database

The system uses a MySQL database called:

```text
student_task_manager
```

There are two main tables:

### users

Stores registered student accounts.

| Field      | Type         | Description           |
| ---------- | ------------ | --------------------- |
| id         | INT          | Primary key           |
| name       | VARCHAR(100) | Student name          |
| email      | VARCHAR(150) | Unique email address  |
| password   | VARCHAR(255) | Hashed password       |
| created_at | TIMESTAMP    | Account creation time |

### tasks

Stores student tasks.

| Field       | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | INT          | Primary key                        |
| user_id     | INT          | Foreign key referencing users      |
| title       | VARCHAR(150) | Task title                         |
| description | TEXT         | Task description                   |
| priority    | ENUM         | Low, Medium, or High               |
| status      | ENUM         | Pending, In Progress, or Completed |
| due_date    | DATE         | Task deadline                      |
| created_at  | TIMESTAMP    | Task creation time                 |

## Database Relationship

One user can have many tasks.

```text
users
  |
  | 1
  |
  |------< tasks
             *
```

The `tasks.user_id` field is a foreign key referencing `users.id`.

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/afroza054/student-task-project.git
```

Move into the project directory:

```bash
cd student-task-manager
```

### 2. Copy the project to your local server

For XAMPP, place the project inside:

```text
C:\xampp\htdocs\student-task-manager
```

### 3. Start XAMPP

Start:

* Apache
* MySQL

### 4. Create the database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create/import the database using:

```text
database/database.sql
```

The SQL file creates the `student_task_manager` database and required tables.

### 5. Configure the database

Open:

```text
config/database.php
```

For a default XAMPP installation:

```php
$host = "localhost";
$dbname = "student_task_manager";
$username = "root";
$password = "";
```

Change the username/password if your MySQL installation uses different credentials.

### 6. Open the application

Visit:

```text
http://localhost/student-task-manager/
```

### 7. Test the application

Test the following workflow:

```text
Home
  ↓
Register
  ↓
Login
  ↓
Dashboard
  ↓
Create Task
  ↓
View Task
  ↓
Edit Task
  ↓
Search / Filter
  ↓
Delete Task
  ↓
Profile
  ↓
Logout
```

## Security

The application implements several security measures.

### Password Security

Passwords are never stored as plain text.

PHP's password hashing functions are used:

```php
password_hash($password, PASSWORD_DEFAULT);
```

During login:

```php
password_verify($password, $storedHash);
```

### SQL Injection Prevention

Database operations use PDO prepared statements rather than concatenating user input into SQL queries.

Example:

```php
$stmt = $pdo->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$stmt->execute([$email]);
```

### Authentication

Protected pages verify that a user is logged in using the PHP session.

### Authorization

Tasks are always associated with the currently authenticated user.

For example:

```sql
WHERE id = ? AND user_id = ?
```

This prevents a student from editing or deleting another student's task.

### Output Escaping

User-generated content is escaped before being displayed:

```php
htmlspecialchars($value)
```

### Validation

The application validates important inputs on the server, including:

* Required fields
* Email format
* Password length
* Task title length
* Valid priority
* Valid status
* Task ID

## Testing

The application should be tested for:

* Successful registration
* Duplicate registration
* Successful login
* Invalid login
* Task creation
* Task editing
* Task deletion
* Search
* Filtering
* Authentication protection
* Unauthorized task access
* Form validation

Detailed test cases are documented in the project report.

## Deployment

The application can be deployed to a PHP/MySQL hosting provider using a control panel such as cPanel.

The deployment process generally involves:

1. Create a MySQL database.
2. Create a database user.
3. Import `database.sql`.
4. Upload the PHP project files.
5. Update the database configuration.
6. Configure the domain.
7. Enable HTTPS.
8. Test registration and login.
9. Test CRUD operations.
10. Test search and filtering.

## Future Improvements

Possible future improvements include:

* Task categories
* Email reminders
* Password reset
* Pagination
* Calendar view
* Task sorting
* File attachments
* Admin dashboard
* Dark mode
* More detailed statistics
* REST API
* Automated testing

## Academic Project

This project was developed as a CSE471 web application project.

### Group

### Members

| Name       | Student ID | Contribution                          |
| ---------- | ---------- | ------------------------------------- |
| [Member 1] | [ID]       | Frontend/UI                           |
| [Member 2] | [ID]       | PHP Authentication                    |
| [Member 3] | [ID]       | MySQL and CRUD                        |
| [Member 4] | [ID]       | JavaScript, testing and documentation |

## Repository

GitHub:

```text
https://github.com/afroza054/student-task-project
```

## Live Website

```text
[INSERT DEPLOYED WEBSITE URL]
```

## License

This project was created for academic purposes.
