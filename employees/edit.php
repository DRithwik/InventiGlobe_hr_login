<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

// Get employee data
if (isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE emp_id = ?");
        $stmt->execute([$_GET['id']]);
        $employee = $stmt->fetch();

        if (!$employee) {
            header("Location: index.php?error=Employee not found");
            exit();
        }
    } catch(PDOException $e) {
        $error = "Error fetching employee: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("UPDATE employees SET 
            emp_name = ?, 
            emp_phone = ?, 
            emp_role = ?, 
            emp_gmail = ?, 
            emp_join_date = ? 
            WHERE emp_id = ?");
        
        $stmt->execute([
            $_POST['emp_name'],
            $_POST['emp_phone'],
            $_POST['emp_role'],
            $_POST['emp_gmail'],
            $_POST['emp_join_date'],
            $_GET['id']
        ]);

        header("Location: index.php?message=Employee updated successfully");
        exit();
    } catch(PDOException $e) {
        $error = "Error updating employee: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - HR System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <i class="fas fa-user-tie text-2xl text-blue-600"></i>
                            <span class="ml-2 text-xl font-bold">HR System</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <a href="../logout.php" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Employee</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Update the employee's information below.
                        </p>
                    </div>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="" method="POST">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                <?php if ($error): ?>
                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="emp_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                                        <input type="text" id="emp_id" disabled
                                               value="<?php echo htmlspecialchars($employee['emp_id']); ?>"
                                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="emp_name" class="block text-sm font-medium text-gray-700">Employee Name</label>
                                        <input type="text" name="emp_name" id="emp_name" required
                                               value="<?php echo htmlspecialchars($employee['emp_name']); ?>"
                                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="emp_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" name="emp_phone" id="emp_phone" required
                                               value="<?php echo htmlspecialchars($employee['emp_phone']); ?>"
                                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="emp_role" class="block text-sm font-medium text-gray-700">Role</label>
                                        <input type="text" name="emp_role" id="emp_role" required
                                               value="<?php echo htmlspecialchars($employee['emp_role']); ?>"
                                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="emp_gmail" class="block text-sm font-medium text-gray-700">Gmail Address</label>
                                        <input type="email" name="emp_gmail" id="emp_gmail" required
                                               value="<?php echo htmlspecialchars($employee['emp_gmail']); ?>"
                                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="emp_join_date" class="block text-sm font-medium text-gray-700">Join Date</label>
                                        <input type="date" name="emp_join_date" id="emp_join_date" required
                                               value="<?php echo htmlspecialchars($employee['emp_join_date']); ?>"
                                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <a href="index.php" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 