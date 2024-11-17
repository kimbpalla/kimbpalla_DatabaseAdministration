<?php
include('connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $userInfoID = $_POST['userInfoID'];
    $addressID = $_POST['addressID'];
    $userID = $_POST['userID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $birthDay = $_POST['birthDay'];

    $checkQuery = "SELECT * FROM userinfo WHERE userInfoID = '$userInfoID'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['errorMessage'] = "Error: The userInfoID already exists. The information has already been saved.";
    } else {
        $query = "INSERT INTO userinfo (userInfoID, addressID, userID, firstName, lastName, birthDay) 
                  VALUES ('$userInfoID', '$addressID', '$userID', '$firstName', '$lastName', '$birthDay')";

        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['successMessage'] = "User added successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['errorMessage'] = "Error adding user: " . mysqli_error($conn);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $userInfoID = $_POST['userInfoID'];

    $deleteQuery = "DELETE FROM userinfo WHERE userInfoID = '$userInfoID'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        $_SESSION['successMessage'] = "User deleted successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error deleting user: " . mysqli_error($conn);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$query = "SELECT userInfoID, addressID, userID, firstName, lastName, birthDay FROM userinfo";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Information System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="shared/css/style.css">
    <link rel="icon" href="image/icon.png" sizes="32x32">
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

        .form-container {
            margin-top: 10px;
        }

        .userList {
            margin-top: 90px;
        }

        .text-end {
            margin-top: 10px;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body id="body" data-bs-theme="dark">
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
        <div class="container-fluid">
            <span class="navbar-text">
                <i class="bi bi-person-circle"> User Information System </i> 
            </span>
            <button id="toggleUserListBtn" class="btn btn-secondary ms-auto">Show Existing Users</button>
        </div>
    </nav>

    <div class="container d-flex flex-column">
        <div class="form-container">
            <form id="userForm" method="POST" action="">
                <div class="mb-3">
                    <label for="userInfoID" class="form-label">UserInfo ID</label>
                    <input type="number" class="form-control" id="userInfoID" name="userInfoID" required>
                </div>
                <div class="mb-3">
                    <label for="addressID" class="form-label">Address ID</label>
                    <input type="number" class="form-control" id="addressID" name="addressID" required>
                </div>
                <div class="mb-3">
                    <label for="userID" class="form-label">User ID</label>
                    <input type="number" class="form-control" id="userID" name="userID" required>
                </div>
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                </div>
                <div class="mb-3">
                    <label for="birthDay" class="form-label">Birthday</label>
                    <input type="date" class="form-control" id="birthDay" name="birthDay" required>
                </div>

                <div class="text-end" style="margin-bottom: 20px;">
                    <button type="submit" name="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>

        <div class="alert-container">
            <?php if (isset($_SESSION['successMessage'])): ?>
                <div class="alert alert-success mt-3">
                    <?php echo $_SESSION['successMessage']; ?>
                    <?php unset($_SESSION['successMessage']); ?>
                </div>
            <?php elseif (isset($_SESSION['errorMessage'])): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo $_SESSION['errorMessage']; ?>
                    <?php unset($_SESSION['errorMessage']); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="userList mt-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>UserInfo ID</th>
                        <th>Address ID</th>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Birthday</th>
                        <th>Actions</th>
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
                                <td><?php echo htmlspecialchars($user['userID']); ?></td>
                                <td><?php echo htmlspecialchars($user['firstName']); ?></td>
                                <td><?php echo htmlspecialchars($user['lastName']); ?></td>
                                <td><?php echo htmlspecialchars($user['birthDay']); ?></td>
                                <td>
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="userInfoID"
                                            value="<?php echo htmlspecialchars($user['userInfoID']); ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">No users found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        document.getElementById('toggleUserListBtn').addEventListener('click', function () {
            const userList = document.querySelector('.userList');
            userList.classList.toggle('d-none');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pzjw8f+ua7Kw1TIq0q6k6VZ4u9VuRsK8g5XfNqveNfA9Xqj9yHL4tETP1OgWwsSh"
        crossorigin="anonymous"></script>
</body>

</html>