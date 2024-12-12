<?php
include("connect.php");

$aircraftTypeFilter = isset($_GET['aircraftType']) ? $_GET['aircraftType'] : '';
$airlineNameFilter = isset($_GET['airlineName']) ? $_GET['airlineName'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : '';

$flightQuery = "SELECT * FROM flightlogs";

if ($aircraftTypeFilter != '' || $airlineNameFilter != '') {
    $flightQuery .= " WHERE";

    if ($aircraftTypeFilter != '') {
        $flightQuery .= " aircraftType='$aircraftTypeFilter'";
    }

    if ($aircraftTypeFilter != '' && $airlineNameFilter != '') {
        $flightQuery .= " AND";
    }

    if ($airlineNameFilter != '') {
        $flightQuery .= " airlineName='$airlineNameFilter'";
    }
}

if ($sort) {
    $flightQuery .= " ORDER BY $sort";
    if ($order) {
        $flightQuery .= " $order";
    }
}

$flightResults = executeQuery($flightQuery);

$aircraftTypeQuery = "SELECT DISTINCT(aircraftType) FROM flightlogs";
$aircraftTypeResults = executeQuery($aircraftTypeQuery);

$airlineNameQuery = "SELECT DISTINCT(airlineName) FROM flightlogs";
$airlineNameResults = executeQuery($airlineNameQuery);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flight Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="shared/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="img/iconairplane.png" sizes="32x32">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            overflow-x: hidden;
        }

        .navbar-text {
            font-size: 1.75rem;
            font-weight: bold;
            font-style: italic;
        }

        .table-container {
            overflow-x: auto;
            display: flex;
            justify-content: center;
        }

        .table {
            background-color: #495057;
            width: 100%;
            table-layout: fixed;
            color: #ffffff;
            margin: 0 auto;
        }

        .table th,
        .table td {
            color: #ffffff;
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            opacity: 0.8;
        }

        .form-container {
            background-color: #495057;
            padding: 20px;
            border-radius: 10px;
        }

        .form-container .form-label {
            color: #ffffff;
        }

        .form-control {
            width: 100%;
        }

        .form-container select {
            margin-bottom: 10px;
        }
    </style>
</head>

<body id="body" data-bs-theme="dark">
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
        <div class="container-fluid">
            <span class="navbar-text"><i class="bi bi-airplane"></i> PUP AIRPORT</span>
        </div>
    </nav>

    <form method="GET">
        <div class="container">
            <div class="row my-5">
                <div class="col-md-12">
                    <div class="form">
                        <div class="h6 mb-3">
                            Filter and Sort
                        </div>
                        <div class="mb-3">
                            <label for="aircraftType" class="form-label">Aircraft Type</label>
                            <select name="aircraftType" id="aircraftType" class="form-control">
                                <option value="">Any</option>
                                <?php while ($row = mysqli_fetch_assoc($aircraftTypeResults)) { ?>
                                <option value="<?php echo $row['aircraftType']; ?>"
                                  <?php if ($aircraftTypeFilter == $row['aircraftType']) echo "selected"; ?>>
                                  <?php echo $row['aircraftType']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="airlineName" class="form-label">Airline</label>
                            <select name="airlineName" id="airlineName" class="form-control">
                                <option value="">Any</option>
                                <?php while ($row = mysqli_fetch_assoc($airlineNameResults)) { ?>
                                <option value="<?php echo $row['airlineName']; ?>"
                                  <?php if ($airlineNameFilter == $row['airlineName']) echo "selected"; ?>>
                                  <?php echo $row['airlineName']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select id="sort" name="sort" class="form-control">
                                <option value="">None</option>
                                <option value="arrivalAirportCode" <?php if ($sort == "arrivalAirportCode") echo "selected"; ?>>
                                    Arrival Airport Code
                                </option>
                                <option value="departureDatetime" <?php if ($sort == "departureDatetime") echo "selected"; ?>>
                                    Departure Date 
                                </option>
                                <option value="departureAirportCode" <?php if ($sort == "departureAirportCode") echo "selected"; ?>>
                                    Departure Airport Code
                                </option>
                                <option value="flightNumber" <?php if ($sort == "flightNumber") echo "selected"; ?>>
                                   Flight Number
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Order</label>
                            <select name="order" id="order" class="form-control">
                                <option value="ASC" <?php if ($order == "ASC") echo "selected"; ?>>Ascending</option>
                                <option value="DESC" <?php if ($order == "DESC") echo "selected"; ?>>Descending</option>
                            </select>
                        </div>

                        <button class="btn btn-primary mt-4" style="width: 100%">Submit</button>
                    </div>
                </div>
            </div>

            <div class="row my-5">
                <div class="col">
                    <div class="card p-4 rounded-5">
                        <div class="table-container">
                            <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Flight Number</th>
                                                <th>Departure Airport</th>
                                                <th>Arrival Airport</th>
                                                <th>Departure DateTime</th>
                                                <th>Arrival DateTime</th>
                                                <th>Flight Duration (Minutes)</th>
                                                <th>Airline Name</th>
                                                <th>Aircraft Type</th>
                                                <th>Passenger Count</th>
                                                <th>Ticket Price</th>
                                                <th>creditCardNumber</th>
                                                <th>creditCardType</th>
                                                <th>pilotName</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($flightResults->num_rows > 0) {
                                                while ($row = $flightResults->fetch_assoc()) {
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $row['flightNumber']; ?></th>
                                                        <td><?php echo $row['departureAirportCode']; ?></td>
                                                        <td><?php echo $row['arrivalAirportCode']; ?></td>
                                                        <td><?php echo $row['departureDatetime']; ?></td>
                                                        <td><?php echo $row['arrivalDatetime']; ?></td>
                                                        <td><?php echo $row['flightDurationMinutes']; ?></td>
                                                        <td><?php echo $row['airlineName']; ?></td>
                                                        <td><?php echo $row['aircraftType']; ?></td>
                                                        <td><?php echo $row['passengerCount']; ?></td>
                                                        <td><?php echo $row['ticketPrice']; ?></td>
                                                        <td><?php echo $row['creditCardNumber']; ?></td>
                                                        <td><?php echo $row['creditCardType']; ?></td>
                                                        <td><?php echo $row['pilotName']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="12" class="text-center">No records found</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                                crossorigin="anonymous"></script>
</body>

</html>