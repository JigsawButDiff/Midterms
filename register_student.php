<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Initialize variables
$errorMessages = [];
$students = isset($_SESSION['students']) ? $_SESSION['students'] : [];

// Handle register student form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registerStudent"])) {
    $studentId = trim($_POST["studentId"]);
    $studentFirstName = trim($_POST["studentFirstName"]);
    $studentLastName = trim($_POST["studentLastName"]);

    // Validate empty fields
    if (empty($studentId)) {
        $errorMessages[] = "Student ID is required.";
    }
    if (empty($studentFirstName)) {
        $errorMessages[] = "First name is required.";
    }
    if (empty($studentLastName)) {
        $errorMessages[] = "Last name is required.";
    } else {
        // Check for duplicate student ID
        $duplicateFound = false;
        foreach ($students as $student) {
            if ($student['id'] === $studentId) {
                $duplicateFound = true;
                break;
            }
        }

        if ($duplicateFound) {
            $errorMessages[] = "A student with this ID already exists.";
        } else {
            // Add the student if no duplicates found
            $students[] = ['id' => $studentId, 'firstName' => $studentFirstName, 'lastName' => $studentLastName];
            $_SESSION['students'] = $students;
            $successMessage = "Student registered successfully!";
        }
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $students = array_filter($students, function ($student) use ($studentId) {
        return $student['id'] !== $studentId;
    });
    $_SESSION['students'] = $students;
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh to update the list
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .container { width: 80%; max-width: 600px; text-align: center; }
        .error { color: #a94442; background-color: #f2dede; padding: 10px; border: 1px solid #ebccd1; border-radius: 5px; margin-bottom: 20px; }
        .form-section, .student-list { border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin-top: 20px; }
        .form-section label, .form-section input { width: 100%; display: block; margin: 10px 0; }
        .form-section input { padding: 8px; }
        .form-section button { width: 100%; padding: 10px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .action-btn { color: #fff; border: none; padding: 5px 10px; cursor: pointer; }
        .edit-btn { background-color: #007bff; }
        .delete-btn { background-color: #d9534f; }
        a.action-link { color: #007bff; text-decoration: none; }
        a.action-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-section">
        <h2>Register Student</h2>
        <?php if (!empty($errorMessages)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errorMessages as $message): ?>
                        <li><?php echo htmlspecialchars($message); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (isset($successMessage)): ?>
            <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="studentId">Student ID</label>
            <input type="text" name="studentId" required>

            <label for="studentFirstName">First Name</label>
            <input type="text" name="studentFirstName" required>

            <label for="studentLastName">Last Name</label>
            <input type="text" name="studentLastName" required>

            <button type="submit" name="registerStudent">Register Student</button>
        </form>

        <br>
        <a href="login.php?page=dashboard"><button type="button">Back to Dashboard</button></a>
    </div>

    <!-- Student List Section -->
    <?php if (!empty($students)): ?>
    <div class="student-list">
        <h3>Student List</h3>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                    <td><?php echo htmlspecialchars($student['firstName']); ?></td>
                    <td><?php echo htmlspecialchars($student['lastName']); ?></td>
                    <td>
                        <!-- Edit and Delete Buttons -->
                        <button class="action-btn edit-btn" onclick="window.location.href='edit.php?id=<?php echo urlencode($student['id']); ?>'">Edit</button>
                        <button class="action-btn delete-btn" onclick="return confirmDelete(<?php echo htmlspecialchars(json_encode($student['id'])); ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p>No student record found.</p>
    <?php endif; ?>
</div>

<!-- Confirmation for Deleting -->
<script>
function confirmDelete(studentId) {
    if (confirm("Are you sure you want to delete this student?")) {
        window.location.href = "?action=delete&id=" + studentId;
    }
    return false; // prevent the default action
}
</script>

</body>
</html>