<?php
include('connect.php');

$errorMessage = '';

$query = "SELECT userInfoID, addressID, firstName, lastName, birthDay FROM userinfo";
$result = executeQuery($query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection)); 
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="shared/css/style.css">
    <style>
        body {
            background-color: #343a40; 
            color: #ffffff; 
        }

        .navbar-text {
            font-size: 24px; 
            font-weight: bold; 
        }

        .table {
            background-color: #495057; 
        }

        .table th,
        .table td {
            color: #ffffff; 
        }

        .btn-primary {
            background-color: #007bff; 
            border-color: #007bff; 
        }

        .btn-primary:hover {
            opacity: 0.8; 
        }
    </style>
</head>

<body id="body" data-bs-theme="dark">
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
        <div class="container-fluid">
            <span class="navbar-text">User Information System</span>
            <button id="toggleUserListBtn" class="btn btn-secondary ms-auto">Show Existing Users</button>
        </div>
    </nav>

    <div class="container">
        <div class="userList mt-4" style="display: block;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Address ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Birthday</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($user = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['userInfoID']); ?></td>
                                <td><?php echo htmlspecialchars($user['addressID']); ?></td>
                                <td><?php echo htmlspecialchars($user['firstName']); ?></td>
                                <td><?php echo htmlspecialchars($user['lastName']); ?></td>
                                <td><?php echo htmlspecialchars($user['birthDay']); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">No users found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('toggleUserListBtn').addEventListener('click', function () {
            const userList = document.querySelector('.userList');
            userList.style.display = userList.style.display === 'none' ? 'block' : 'none';
            this.textContent = userList.style.display === 'block' ? 'Hide Existing Users' : 'Show Existing Users';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybR8jH2gCqC2C1j3q4FNT6eDFT6e0bcA6Z0DgEG0o9R6wBz5K" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-OLb9W0fSQc0z0U3w3dWcQYkiG6aI5C7KJc8p3TxB9HRfj2N3Kn8NzPQG1zt5D8oc" crossorigin="anonymous"></script>
</body>

</html>
