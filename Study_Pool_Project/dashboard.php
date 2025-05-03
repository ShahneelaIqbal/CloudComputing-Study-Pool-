<?php
include 'dbConnection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch user data
$user_query = "SELECT username FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

// Student-specific actions
if ($role === 'student') {
    $conn->query("
    UPDATE questions 
    SET status = 'answered' 
    WHERE id IN (SELECT question_id FROM answers) AND status = 'open'
");

// Fetch student's posted questions
$questions_query = "
    SELECT q.*, a.answer_text, a.attachment_url 
    FROM questions q 
    LEFT JOIN answers a ON q.id = a.question_id 
    WHERE q.user_id = $user_id
";
$questions_result = $conn->query($questions_query);

// Fetch ongoing questions 
$ongoing_questions_query = "
    SELECT q.*, a.answer_text 
    FROM questions q 
    LEFT JOIN answers a ON q.id = a.question_id 
    WHERE q.user_id = $user_id AND q.status = 'open'
";
$ongoing_questions_result = $conn->query($ongoing_questions_query);

// Fetch completed questions 
 $answered_questions_query = "
    SELECT q.question_text, a.answer_text 
    FROM questions q 
    LEFT JOIN answers a ON q.id = a.question_id 
    WHERE q.user_id = $user_id AND q.status = 'answered'
";
$answered_questions_result = $conn->query($answered_questions_query);

    // Handle posting new questions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category = $conn->real_escape_string($_POST['category']);
        $question_text = $conn->real_escape_string($_POST['question_text']);
        $budget = $_POST['budget'];

        $insert_query = "INSERT INTO questions (user_id, category, question_text, budget, status, created_at) 
                         VALUES ($user_id, '$category', '$question_text', $budget, 'open', NOW())";

        if ($conn->query($insert_query) === TRUE) {
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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
        .subjects {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }
        .subjects select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .main-body {
            padding: 20px;
        }
        .question-list {
            list-style: none;
            padding: 0;
        }
        .question-list li {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <img src="https://www.studypool.com/images/logo.png" width="150px" alt="Study Pool Logo">
    <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
    <div class="menu">
        <a href="myProfile.php">My Profile</a>
        <a href="postQuestion.php">Post a Question</a>
        <a href="noteBank.php">Notebank</a>
        <a href="bookGuides.php">Book Guides</a>
        <a href="howItWorks.php">How It Works</a>
        <a href="myTutors.php">My Tutors</a>
        <a href="settings.php">Settings</a>
        <a href="signout.php">Logout</a>
    </div>
</div>

<!-- Subject Dropdown -->
<div class="subjects">
    <select id="subjectDropdown">
        <option value="all">All Subjects</option>
        <option value="Business">Business</option>
        <option value="Humanities">Humanities</option>
        <option value="Mathematics">Mathematics</option>
        <option value="Programming">Programming</option>
        <option value="Science">Science</option>
        <option value="Economics">Economics</option>
        <option value="Computer Science">Computer Science</option>
        <option value="Law">Law</option>
        <option value="Medical & Health">Medical & Health</option>
        <option value="Engineering">Engineering</option>
        <option value="Foreign Language">Foreign Language</option>
        <option value="Other">Other</option>
    </select>
</div>

<!-- Main Body -->
<div class="main-body">

    <h2>Your Posted Questions</h2>
    <ul class="question-list" id="postedQuestions">
        <?php while ($row = $questions_result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['category']); ?></strong>: 
                <?php echo htmlspecialchars($row['question_text']); ?>
                (Status: <?php echo ucfirst(htmlspecialchars($row['status'])); ?>)
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Your Ongoing Questions</h2>
    <ul class="question-list" id="ongoingQuestions">
        <?php while ($row = $ongoing_questions_result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['category']); ?></strong>: 
                <?php echo htmlspecialchars($row['question_text']); ?>
                (Status: Ongoing)
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Your Completed Questions</h2>
    <ul class="question-list" id="completedQuestions">
    <?php while ($row = $answered_questions_result->fetch_assoc()): ?>
        <li>
            <div>
                <strong>Question:</strong> <?php echo htmlspecialchars($row['question_text']); ?><br><br>

                <?php if (!empty($row['answer_text'])): ?>
                    <div style="margin-top: 10px; padding: 10px; background-color: #f1f1f1; border-radius: 5px;">
                        <strong>Answer:</strong><br>
                        <?php echo nl2br(htmlspecialchars($row['answer_text'])); ?>
                    </div>
                <?php else: ?>
                    <div style="color: red;">No answer available yet.</div>
                <?php endif; ?>
            </div>
        </li>
    <?php endwhile; ?>
</ul>


</div>

<script>
    document.getElementById('subjectDropdown').addEventListener('change', function () {
        const subject = this.value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetchQuestions.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);

                const postedQuestions = document.getElementById('postedQuestions');
                postedQuestions.innerHTML = data.posted.map(question => `
                    <li>
                        <strong>${question.category}</strong>: ${question.question_text}
                        (Status: ${question.status})
                    </li>
                `).join('');

                const ongoingQuestions = document.getElementById('ongoingQuestions');
                ongoingQuestions.innerHTML = data.ongoing.map(question => `
                    <li>
                        <strong>${question.category}</strong>: ${question.question_text}
                        (Status: Ongoing)
                    </li>
                `).join('');

                const completedQuestions = document.getElementById('completedQuestions');
                completedQuestions.innerHTML = data.completed.map(question => `
                    <li>
                        <strong>${question.category}</strong>: ${question.question_text}
                        (Status: Completed)
                    </li>
                `).join('');
            }
        };
        xhr.send(`subject=${subject}`);
    });
</script>

</body>
</html>
