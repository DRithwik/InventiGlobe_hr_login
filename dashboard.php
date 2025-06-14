<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get current section from URL parameter
$current_section = $_GET['section'] ?? 'dashboard';

// Function to check if a section is active
function is_section_active($section) {
    global $current_section;
    return $current_section === $section ? 'active-nav' : '';
}

// Function to check if a section content should be hidden
function is_section_hidden($section) {
    global $current_section;
    return $current_section === $section ? '' : 'hidden';
}

// Include database connection for employees section
if ($current_section === 'employees') {
    require_once 'config/database.php';
    
    // Handle Delete
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        try {
            $stmt = $pdo->prepare("DELETE FROM employees WHERE emp_id = ?");
            $stmt->execute([$id]);
            header("Location: dashboard.php?section=employees&message=Employee deleted successfully");
            exit();
        } catch(PDOException $e) {
            $error = "Error deleting employee: " . $e->getMessage();
        }
    }

    // Fetch all employees
    try {
        $stmt = $pdo->query("SELECT * FROM employees ORDER BY created_at DESC");
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error fetching employees: " . $e->getMessage();
    }
}

// Fetch total employees for dashboard
$total_employees = 0;
if ($current_section === 'dashboard') {
    require_once 'config/database.php';
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM employees");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_employees = $row ? $row['count'] : 0;
    } catch(PDOException $e) {
        $total_employees = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .nav-text {
            display: none;
        }
        .sidebar.collapsed .logo-text {
            display: none;
        }
        .sidebar.collapsed .nav-item {
            justify-content: center;
        }
        .content {
            transition: all 0.3s;
        }
        .content.expanded {
            margin-left: 70px;
        }
        .active-nav {
            background-color: #3b82f6;
            color: white !important;
        }
        .active-nav i {
            color: white !important;
        }
        .table-container {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 flex flex-col fixed h-full z-10">
            <div class="p-4 flex items-center border-b border-blue-700">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <span class="logo-text ml-3 text-xl font-bold">HR System</span>
                <button id="toggleSidebar" class="ml-auto text-white focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto">
                <div class="p-4">
                    <div class="mb-6">
                        <h3 class="text-xs uppercase font-semibold text-blue-300 mb-3 tracking-wider">Main</h3>
                        <ul>
                            <li class="mb-1">
                                <a href="?section=dashboard" class="nav-item flex items-center p-2 rounded hover:bg-blue-700 transition <?php echo is_section_active('dashboard'); ?>" data-section="dashboard">
                                    <i class="fas fa-tachometer-alt mr-3 text-blue-300"></i>
                                    <span class="nav-text">Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-xs uppercase font-semibold text-blue-300 mb-3 tracking-wider">Management</h3>
                        <ul>
                            <li class="mb-1">
                                <a href="?section=employees" class="nav-item flex items-center p-2 rounded hover:bg-blue-700 transition <?php echo is_section_active('employees'); ?>" data-section="employees">
                                    <i class="fas fa-users mr-3 text-blue-300"></i>
                                    <span class="nav-text">Employees</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="?section=departments" class="nav-item flex items-center p-2 rounded hover:bg-blue-700 transition <?php echo is_section_active('departments'); ?>" data-section="departments">
                                    <i class="fas fa-building mr-3 text-blue-300"></i>
                                    <span class="nav-text">Departments</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="?section=leave-requests" class="nav-item flex items-center p-2 rounded hover:bg-blue-700 transition <?php echo is_section_active('leave-requests'); ?>" data-section="leave-requests">
                                    <i class="fas fa-calendar-minus mr-3 text-blue-300"></i>
                                    <span class="nav-text">Leave Requests</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="?section=attendance" class="nav-item flex items-center p-2 rounded hover:bg-blue-700 transition <?php echo is_section_active('attendance'); ?>" data-section="attendance">
                                    <i class="fas fa-clipboard-check mr-3 text-blue-300"></i>
                                    <span class="nav-text">Attendance</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-xs uppercase font-semibold text-blue-300 mb-3 tracking-wider">Settings</h3>
                        <ul>
                            <li class="mb-1">
                                <a href="?section=settings" class="nav-item flex items-center p-2 rounded hover:bg-blue-700 transition <?php echo is_section_active('settings'); ?>" data-section="settings">
                                    <i class="fas fa-cog mr-3 text-blue-300"></i>
                                    <span class="nav-text">Settings</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="?section=api" class="nav-item flex items-center p-2 rounded hover:bg-blue-700 transition <?php echo is_section_active('api'); ?>" data-section="api">
                                    <i class="fas fa-code mr-3 text-blue-300"></i>
                                    <span class="nav-text">API Access</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-blue-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-user text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                        <div class="text-xs text-blue-300"><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
                    </div>
                    <a href="logout.php" class="ml-auto text-white focus:outline-none">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content flex-1 flex flex-col ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800"><?php echo ucfirst($current_section); ?></h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="notificationBtn" class="text-gray-600 hover:text-gray-900 focus:outline-none relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white flex items-center justify-center text-xs text-white">1</span>
                        </button>
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded shadow-lg z-50">
                            <div class="p-4 text-gray-700">No new notifications.</div>
                        </div>
                    </div>
                    <div class="relative">
                        <button id="messageBtn" class="text-gray-600 hover:text-gray-900 focus:outline-none relative">
                            <i class="fas fa-envelope text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white flex items-center justify-center text-xs text-white">2</span>
                        </button>
                        <div id="messageDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded shadow-lg z-50">
                            <div class="p-4 text-gray-700">No new messages.</div>
                        </div>
                    </div>
                    <div class="relative">
                        <button id="themeToggle" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <i id="themeIcon" class="fas fa-moon text-xl"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php if ($current_section === 'dashboard'): ?>
                    <!-- Dashboard Content -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-users text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Total Employees</p>
                                    <p class="text-2xl font-semibold text-gray-700"><?php echo $total_employees; ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- Add more dashboard widgets here -->
                    </div>
                <?php elseif ($current_section === 'employees'): ?>
                    <!-- Employees Section -->
                    <?php if (isset($_GET['message'])): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo htmlspecialchars($_GET['message']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Employee Management</h2>
                        <a href="employees/create.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus"></i> Add New Employee
                        </a>
                    </div>

                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gmail</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (isset($employees) && !empty($employees)): ?>
                                    <?php foreach ($employees as $employee): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($employee['emp_id']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($employee['emp_name']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($employee['emp_phone']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($employee['emp_role']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($employee['emp_gmail']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($employee['emp_join_date']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="employees/edit.php?id=<?php echo $employee['emp_id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="?section=employees&delete=<?php echo $employee['emp_id']; ?>" class="text-red-600 hover:text-red-900" 
                                               onclick="return confirm('Are you sure you want to delete this employee?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No employees found. Add your first employee!
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('expanded');
        });

        // Notification dropdown
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
            messageDropdown.classList.add('hidden');
        });

        // Message dropdown
        const messageBtn = document.getElementById('messageBtn');
        const messageDropdown = document.getElementById('messageDropdown');
        messageBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            messageDropdown.classList.toggle('hidden');
            notificationDropdown.classList.add('hidden');
        });

        // Hide dropdowns when clicking outside
        window.addEventListener('click', function() {
            notificationDropdown.classList.add('hidden');
            messageDropdown.classList.add('hidden');
        });

        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        let darkMode = false;
        themeToggle.addEventListener('click', function() {
            darkMode = !darkMode;
            if (darkMode) {
                document.documentElement.classList.add('dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                document.documentElement.classList.remove('dark');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        });
    </script>
</body>
</html> 