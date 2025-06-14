<?php
// Dashboard section content
?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Employees</p>
                <h3 class="text-2xl font-bold">124</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <i class="fas fa-building text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Departments</p>
                <h3 class="text-2xl font-bold">8</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <i class="fas fa-calendar-minus text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pending Leaves</p>
                <h3 class="text-2xl font-bold">7</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                <i class="fas fa-user-clock text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Absent Today</p>
                <h3 class="text-2xl font-bold">3</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Recent Leave Requests</h2>
            <a href="?section=leave-requests" class="text-blue-600 text-sm">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // Example data - In a real application, this would come from a database
                    $leave_requests = [
                        [
                            'name' => 'Sarah Johnson',
                            'department' => 'Marketing',
                            'type' => 'Sick Leave',
                            'dates' => 'Jun 12 - Jun 14',
                            'status' => 'Pending'
                        ],
                        [
                            'name' => 'Michael Chen',
                            'department' => 'Engineering',
                            'type' => 'Vacation',
                            'dates' => 'Jun 20 - Jun 25',
                            'status' => 'Approved'
                        ],
                        [
                            'name' => 'Emily Rodriguez',
                            'department' => 'HR',
                            'type' => 'Personal',
                            'dates' => 'Jun 5 - Jun 6',
                            'status' => 'Rejected'
                        ]
                    ];

                    foreach ($leave_requests as $request): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/women/12.jpg" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($request['name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($request['department']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($request['type']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($request['dates']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $status_class = [
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Approved' => 'bg-green-100 text-green-800',
                                    'Rejected' => 'bg-red-100 text-red-800'
                                ][$request['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($request['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Today's Attendance</h2>
            <a href="?section=attendance" class="text-blue-600 text-sm">View All</a>
        </div>
        <div class="space-y-4">
            <?php
            // Example data - In a real application, this would come from a database
            $attendance = [
                [
                    'name' => 'John Smith',
                    'department' => 'Sales',
                    'status' => 'Present'
                ],
                [
                    'name' => 'Lisa Wong',
                    'department' => 'Marketing',
                    'status' => 'Present'
                ],
                [
                    'name' => 'Robert Davis',
                    'department' => 'Engineering',
                    'status' => 'Late'
                ],
                [
                    'name' => 'Maria Garcia',
                    'department' => 'Finance',
                    'status' => 'Absent'
                ]
            ];

            foreach ($attendance as $record): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium"><?php echo htmlspecialchars($record['name']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($record['department']); ?></p>
                        </div>
                    </div>
                    <?php
                    $status_class = [
                        'Present' => 'bg-green-100 text-green-800',
                        'Late' => 'bg-yellow-100 text-yellow-800',
                        'Absent' => 'bg-red-100 text-red-800'
                    ][$record['status']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($record['status']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Employee Statistics</h2>
        <div class="flex space-x-2">
            <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded">Monthly</button>
            <button class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded">Quarterly</button>
            <button class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded">Yearly</button>
        </div>
    </div>
    <div class="h-64">
        <!-- Chart placeholder -->
        <div class="w-full h-full bg-gray-100 rounded flex items-center justify-center">
            <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
        </div>
    </div>
</div> 