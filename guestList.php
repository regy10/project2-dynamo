<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Azubi Africa: Guest List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .header button {
            padding: 10px 20px;
            background-color: red;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .header button a {
            color: #fff;
            text-decoration: none;
        }

        .header button:hover {
            background-color: darkred;
        }

        .styled-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 1em;
            font-family: sans-serif;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            overflow: hidden;
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }
    </style>
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
                <!-- Start of PHP code to retrieve and display data from DynamoDB -->
                <?php
                require 'vendor/autoload.php';

                use Aws\DynamoDb\DynamoDbClient;
                use Aws\Exception\AwsException;

                // Create a DynamoDB client
                $dynamoDb = new DynamoDbClient([
                    'region'      => 'us-east-1',
                    'version'     => 'latest',
                    'credentials' => [
                        'key'    => '',
                        'secret' => '',
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
                <!-- End of PHP code -->
            </tbody>
        </table>
    </div>
</body>
</html>
