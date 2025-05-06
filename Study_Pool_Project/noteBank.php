<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Bank</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #007bff;
            padding: 10px 20px;
            align-items: center;
        }
        .navbar img {
            height: 50px;
        }
        .navbar input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .menu-icon {
            display: none;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }
        .menu {
            display: flex;
            gap: 20px;
        }
        .menu a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<div class="navbar">
    <a href="dashboard.php">
        <img src="https://www.studypool.com/images/logo.png" width="150px" alt="Study Pool Logo">
    </a>
    <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
    <div class="menu">
        <a href="myProfile.php">My Profile</a>
        <a href="postQuestion.php">Post a Question</a>
        <a href="noteBank.php">Notebank</a>
        <a href="bookGuides.php">Book Guides</a>
        <a href="howItWorks.php">How It Works</a>
        <a href="myTutors.php">Tutors</a>
        <a href="settings.php">Settings</a>
        <a href="signout.php">Logout</a>
    </div>
</div>

<!-- Notes Section -->
<div class="container mt-5">
    <h2 class="mb-4">Available Notes</h2>
    <div class="row">
        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "studypool_clone");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch notes from the database
        $sql = "SELECT * FROM notes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($row['content'], 0, 100)) . '...'; ?></p>
                            <p class="text-muted">Price: $<?php echo htmlspecialchars($row['fee']); ?></p>
                            <a href="viewNote.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Note</a>

                            <?php
                            // Check payment and attachment if user is logged in
                            if (isset($_SESSION['user_id'])) {
                                $user_id = $_SESSION['user_id'];
                                $note_id = $row['id'];

                                $stmt = $conn->prepare("SELECT * FROM tbl_payment WHERE user_id = ? AND note_id = ? AND payment_status = 'completed'");
                                $stmt->bind_param("ii", $user_id, $note_id);
                                $stmt->execute();
                                $paymentResult = $stmt->get_result();

                                if ($paymentResult->num_rows > 0) {
                                    $attachment = $row['attachment_url'];
                                    $attachment = $row['attachment_url'];
                                    if (!empty($attachment) && file_exists($attachment)) {
                                        echo '<a href="downloadNote.php?id=' . $note_id . '" class="btn btn-success mt-2">Download</a>';
                                    } else {
                                        echo '<p class="text-danger mt-2">No attachment available</p>';
                                    }
                                } else {
                                    echo '<a href="payment-form-ui.php?id=' . $note_id . '" class="btn btn-success">Purchase</a>';
                                }
                            } else {
                                echo '<a href="login.php" class="btn btn-secondary">Login to Purchase</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-center'>No notes available.</p>";
        }

        $conn->close();
        ?>
    </div>
</div>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
