<?php
session_start();
include 'dbConnection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// Ensure the database connection is valid
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes_title = trim($_POST['title']);
    $notes_content = trim($_POST['content']);
    $subject = trim($_POST['subject']);
    $fee = $_POST['fee'];
    $user_id = $_SESSION['user_id'];

    // Handle file upload
    $attachment_url = NULL; // Default if no file uploaded
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = 'uploads/notes/';
        $file_name = basename($_FILES['attachment']['name']);
        $file_path = $upload_dir . $file_name;

        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move the uploaded file to the desired location
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
            $attachment_url = $file_path;  // Save the URL in the database
        } else {
            echo "<script>alert('Error uploading the file.');</script>";
        }
    }

    // Insert the note into the database, including attachment_url
    $sql = "INSERT INTO notes (title, content, user_id, created_at, subject, fee, attachment_url) 
            VALUES (?, ?, ?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $notes_title, $notes_content, $user_id, $subject, $fee, $attachment_url);

    if ($stmt->execute()) {
        echo "<script>alert('Note uploaded successfully.'); window.location.href='TutorDashboard.php';</script>";
    } else {
        echo "<script>alert('Error uploading note. Please try again.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://media.istockphoto.com/id/1218737747/vector/learning-online-e-learning-video-call-chat-with-class-distance-education.jpg?s=612x612&w=0&k=20&c=fFFwc3CTP4XtvmruZLiK8EzAbzvAxJL_kw5BsA7z7w8=');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #333;
        }
        .navbar {
            background-color: #26778a;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: #fff;
            font-weight: bold;
        }
        .navbar-nav .nav-link:hover {
            color: #f0e68c;
        }
        .footer {
            background-color: #26778a;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            min-height: 100vh;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
        <img src="https://www.studypool.com/images/logo.png" width="150px" alt="Study Pool Logo">
        </a>
        <div class="collapse navbar-collapse">
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
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h2 class="text-center mb-4">Upload Notes</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="fee" class="form-label">Fee</label>
                        <input type="number" class="form-control" id="fee" name="fee" required>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" id="attachment" name="attachment" accept=".pdf,.docx,.pptx,.txt">
                    </div>
                    <button type="submit" class="btn" style="background-color: #26778a; color: #fff; width: 100%;">Submit Note</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2024 Study Pool. All Rights Reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
