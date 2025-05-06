<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Answer Attachment</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            color: #333;
        }
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
        .answer-content {
            white-space: pre-line;
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
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="TutorDashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="view_questions.php">View Questions</a></li>
                <li class="nav-item"><a class="nav-link" href="upload_notes.php">Upload Notes</a></li>
                <li class="nav-item"><a class="nav-link" href="upload_book_guides.php">Upload Book Guides</a></li>
                <li class="nav-item"><a class="nav-link" href="my_uploads.php">Your Uploads</a></li>
                <li class="nav-item"><a class="nav-link" href="tutorSettings.php">Settings</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="signout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="container mt-5">
        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "studypool_clone");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if 'id' is set in the URL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $answer_id = intval($_GET['id']);

            // Join answers and questions table
            $sql = "SELECT a.*, q.question_text 
                    FROM answers a 
                    JOIN questions q ON a.question_id = q.id 
                    WHERE a.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $answer_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($row['question_text']); ?></h2>
                        <p class="card-text answer-content">
                            <strong>Answer Description:</strong><br>
                            <?php echo nl2br(htmlspecialchars($row['answer_text'])); ?>
                        </p>

                        <?php if (!empty($row['attachment_url'])): ?>
                            <p>
                                <strong>Attachment:</strong><br>
                                <a href="<?php echo htmlspecialchars($row['attachment_url']); ?>" target="_blank" class="btn btn-primary">
                                    View/Download Attachment
                                </a>
                            </p>
                        <?php else: ?>
                            <p class="text-warning">No attachment available.</p>
                        <?php endif; ?>

                        <a href="my_uploads.php" class="btn btn-secondary mt-3">Back to Uploads</a>
                    </div>
                </div>
                <?php
            } else {
                echo "<p class='text-danger'>Answer not found.</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='text-danger'>Invalid Answer ID.</p>";
        }

        $conn->close();
        ?>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
