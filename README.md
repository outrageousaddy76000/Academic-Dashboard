# Academic Dashboard for RGIPT - Currently hosted at https://dashboard.adarshloves.me

Welcome to the Academic Dashboard for Rajiv Gandhi Institute of Petroleum Technology (RGIPT). This dashboard provides comprehensive academic information for students and faculty members.

Do look at the presentation to get a better idea about the project.

## Getting Started

### Prerequisites

1. PHP >= 7.0
2. MySQL >= 5.6
3. Web server (e.g., Apache, Nginx)

### Installation

1. Clone this repository to your local machine:

    ```bash
    git clone https://github.com/your-repo-url.git
    ```

2. Create a `config.php` file in the root directory with the following structure:

    ```php
    <?php
    // Database configuration
    $dbConfig = [
        'host' => '',
        'username' => '',
        'password' => '',
        'database' => ''
    ];

    $mailConfig = [
        'host' => 'smtp.gmail.com',
        'username' => '',
        'password' => ''
    ];
    ?>
    ```

3. Create a MySQL database. The necessary tables will be auto-created upon running the application except the users table so add that manually in the database with the authorised users.

### Database Tables

#### Courses

```sql
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20),
    course_name VARCHAR(100),
    semester ENUM('Odd', 'Even'),
    dept_name VARCHAR(50),
    course_type VARCHAR(20),
    credit INT,
    credit_structure VARCHAR(100),
    year INT,
    elective BOOLEAN
);
```


#### Users

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255)
);
```

#### Students

```sql
CREATE TABLE students (
    roll VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100),
    phone_number VARCHAR(20),
    spi_sem1 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem2 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem3 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem4 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem5 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem6 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem7 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem8 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem1 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem2 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem3 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem4 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem5 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem6 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem7 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem8 DECIMAL(4,2) DEFAULT 0.00,
    positions VARCHAR(255),
    backlog_courses TEXT,
    all_backlog_courses TEXT,
    minor_courses TEXT
);
```

### Usage

Once the installation is complete, users with access to the academic dashboard can log in using their email and password.

### License

This dashboard is free to use for academic purposes.
