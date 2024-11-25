<?php
include('connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['submit']) || isset($_POST['edit']))) {
    $userInfoID = $_POST['userInfoID'];
    $addressID = $_POST['addressID'];
    $userID = $_POST['userID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $birthDay = $_POST['birthDay'];

    if (isset($_POST['edit'])) {
        $query = "UPDATE userinfo SET addressID = '$addressID', userID = '$userID', firstName = '$firstName', lastName = '$lastName', birthDay = '$birthDay' WHERE userInfoID = '$userInfoID'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['successMessage'] = "User details updated successfully!";
        } else {
            $_SESSION['errorMessage'] = "Error updating user: " . mysqli_error($conn);
        }
    } else {
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
            } else {
                $_SESSION['errorMessage'] = "Error adding user: " . mysqli_error($conn);
            }
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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

if (isset($_GET['edit'])) {
    $editID = $_GET['edit'];
    $editQuery = "SELECT * FROM userinfo WHERE userInfoID = '$editID'";
    $editResult = mysqli_query($conn, $editQuery);

    if ($editResult && mysqli_num_rows($editResult) > 0) {
        $editUser = mysqli_fetch_assoc($editResult);
    } else {
        $_SESSION['errorMessage'] = "Error fetching user details: " . mysqli_error($conn);
    }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            margin-top: 20px; 
        }

        .userList {
            margin-top: 20px; 
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
            <button class="btn btn-secondary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#userList" aria-expanded="false" aria-controls="userList">
                Show Existing Users
            </button>
        </div>
    </nav>

    <div class="container d-flex flex-column">
        <div class="form-container">
            <form id="userForm" method="POST" action="">
                <div class="mb-3">
                    <label for="userInfoID" class="form-label">UserInfo ID</label>
                    <input type="number" class="form-control" id="userInfoID" name="userInfoID"
                        value="<?php echo isset($editUser) ? htmlspecialchars($editUser['userInfoID']) : ''; ?>"
                        <?php echo isset($editUser) ? 'readonly' : ''; ?> required>
                </div>
                <div class="mb-3">
                    <label for="addressID" class="form-label">Address ID</label>
                    <input type="number" class="form-control" id="addressID" name="addressID"
                        value="<?php echo isset($editUser) ? htmlspecialchars($editUser['addressID']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="userID" class="form-label">User ID</label>
                    <input type="number" class="form-control" id="userID" name="userID"
                        value="<?php echo isset($editUser) ? htmlspecialchars($editUser['userID']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName"
                        value="<?php echo isset($editUser) ? htmlspecialchars($editUser['firstName']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName"
                        value="<?php echo isset($editUser) ? htmlspecialchars($editUser['lastName']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="birthDay" class="form-label">Birthday</label>
                    <input type="date" class="form-control" id="birthDay" name="birthDay"
                        value="<?php echo isset($editUser) ? htmlspecialchars($editUser['birthDay']) : ''; ?>" required>
                </div>

                <div class="text-end" style="margin-bottom: 20px;">
                    <button type="submit" name="submit" class="btn btn-primary" <?php echo isset($editUser) ? 'disabled' : ''; ?>>Add User</button>
                    <button type="submit" name="edit" class="btn btn-warning" <?php echo isset($editUser) ? '' : 'disabled'; ?>>Edit User</button>
                </div>
            </form>
        </div>

        <div class="alert-container">
            <?php if (isset($_SESSION['successMessage'])): ?>
                <div class="alert alert-success mt-3">
                    <?php echo $_SESSION['successMessage']; ?>
                    <?php unset($_SESSION['successMessage']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['errorMessage'])): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo $_SESSION['errorMessage']; ?>
                    <?php unset($_SESSION['errorMessage']); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="collapse userList" id="userList">
            <table class="table table-striped table-bordered table-hover table-dark mt-4">
                <thead>
                    <tr>
                        <th>UserInfo ID</th>
                        <th>Address ID</th>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Birthday</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['userInfoID']); ?></td>
                            <td><?php echo htmlspecialchars($user['addressID']); ?></td>
                            <td><?php echo htmlspecialchars($user['userID']); ?></td>
                            <td><?php echo htmlspecialchars($user['firstName']); ?></td>
                            <td><?php echo htmlspecialchars($user['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($user['birthDay']); ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline-block;">
                                    <input type="hidden" name="userInfoID" value="<?php echo htmlspecialchars($user['userInfoID']); ?>">
                                    <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <a href="?edit=<?php echo htmlspecialchars($user['userInfoID']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
