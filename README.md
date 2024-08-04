Guest List Application
This project is a simple PHP-based web application for managing a guest list. It uses AWS DynamoDB as the database to store guest information. The application includes a login system, a page for viewing the guest list, and Docker support for containerization.

Prerequisites
Before you start, make sure you have the following installed on your local machine:

AWS CLI: Used to interact with AWS services from the command line.
Docker: Required for containerizing the application.
Composer: Dependency manager for PHP.
Setup Instructions
1. Install AWS CLI
To install the AWS CLI, follow the official AWS documentation.

After installation, configure the CLI with your AWS credentials:

bash terminal

aws configure
Enter your AWS Access Key ID, AWS Secret Access Key, preferred region, and output format. This will allow your local machine to communicate with AWS resources, including DynamoDB.

2. Create Project Directory
Create a directory for your project and navigate into it:

mkdir project2
cd project2
Inside this directory, create the following files:

Dockerfile
login.php
guestlist.php
index.html
composer.json
composer.lock
3. Dockerfile
Create a Dockerfile with the following contents:

Dockerfile
code
# Use the official PHP image as the base image
FROM php:8.2-apache

# Copy the PHP application code to the Apache web root
COPY . /var/www/html/

# Expose port 80 to the outside world
EXPOSE 80
4. Application Files
login.php
This file contains the login logic:

php
code
<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials
    $valid_username = 'admin';
    $valid_password = 'password123';

    if ($username === $valid_username && $password === $valid_password) {
        header('Location: guestlist.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Azubi Africa: Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
guestlist.php
This file displays the guest list retrieved from DynamoDB:

php
Copy code
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Azubi Africa: Guest List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Guest List</h1>
            <button><a href="login.php">Log out</a></button>
        </div>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'vendor/autoload.php';

                use Aws\DynamoDb\DynamoDbClient;
                use Aws\Exception\AwsException;

                // Create a DynamoDB client
                $dynamoDb = new DynamoDbClient([
                    'region'      => 'us-east-1',
                    'version'     => 'latest',
                    'credentials' => [
                        'key'    => 'your-access-key-id',
                        'secret' => 'your-secret-access-key',
                    ]
                ]);

                // Retrieve items from the DynamoDB table
                $tableName = 'GuestBook';

                try {
                    $result = $dynamoDb->scan([
                        'TableName' => $tableName,
                    ]);
                    foreach ($result['Items'] as $item) {
                        $name = isset($item['Name']['S']) ? $item['Name']['S'] : '';
                        $email = isset($item['Email']['S']) ? $item['Email']['S'] : '';
                        $country = isset($item['Country']['S']) ? $item['Country']['S'] : '';

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($name) . '</td>';
                        echo '<td>' . htmlspecialchars($email) . '</td>';
                        echo '<td>' . htmlspecialchars($country) . '</td>';
                        echo '</tr>';
                    }
                } catch (AwsException $e) {
                    echo '<p>Error retrieving data: ' . $e->getMessage() . '</p>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
index.html
This is a placeholder file for the homepage. Customize it as needed.

5. Composer Configuration
composer.json
json
Copy code
{
    "require": {
        "aws/aws-sdk-php": "^3.0"
    }
}
composer.lock
This file will be automatically generated by Composer.

6. Building and Running the Docker Container
To build the Docker image, run the following command in the project directory:

bash

docker build -t project2 .
To run the Docker container, use:

bash
docker run -p 8181:80 project2
You can access the application at http://localhost:8181.

7. AWS DynamoDB Configuration
Create a DynamoDB table named GuestBook with the following attributes:

Name (String)
Email (String)
Country (String)
Populate the table with sample data using the AWS CLI or AWS Management Console.

8. Accessing the Application
Login Page: Navigate to http://localhost:8181/login.php and use the hardcoded credentials (admin, password123) to log in.
Guest List Page: After logging in, you will be redirected to guestlist.php to view the list of guests.
