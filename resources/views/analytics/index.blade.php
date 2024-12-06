<x-app-layout class="bg-gradient-to-br from-yellow-50 to-yellow-200 dark:from-gray-900 dark:to-gray-800 min-h-screen">
    <div class="flex-1 p-2 sm:p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black mb-4 sm:mb-6 md:mb-8 dark:text-gray-100 tracking-tight text-center sm:text-left">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Task Analytics
                </span>
            </h1>

            <!-- Monthly Analysis Card -->
            <div class="mb-4 sm:mb-6 md:mb-8 bg-white/80 backdrop-blur-sm dark:bg-gray-700 rounded-xl sm:rounded-2xl shadow-xl p-3 sm:p-4 md:p-6 h-auto border border-gray-100 dark:border-gray-600">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-purple-400">Monthly Analysis</h2>
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-4">
                        <select id="monthSelect" class="w-full sm:w-auto rounded-lg border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm sm:text-base">
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $month == date('n') ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                </option>
                            @endforeach
                        </select>
                        <select id="yearSelect" class="w-full sm:w-auto rounded-lg border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm sm:text-base">
                            @foreach(range(date('Y'), date('Y')-2) as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <button 
                            onclick="updateCharts()"
                            class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-purple-600 text-white text-sm sm:text-base rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Update
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                    <!-- All Tasks Bar Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-3 sm:p-4 h-[300px] sm:h-[350px] md:h-[400px] border border-black border-opacity-35 dark:border-gray-600">
                        <canvas id="dailyTasksChart"></canvas>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Basic Stats Card -->
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Monthly Stats Card -->
                            <div class="bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-xl sm:rounded-2xl shadow-xl p-3 sm:p-4 border border-black border-opacity-35 dark:border-gray-600">
                                <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white">Monthly Statistics</h2>
                                </div>

                                <div class="space-y-1">
                                    <div class="flex justify-between items-center p-2 rounded-xl bg-green-100 dark:bg-gray-700/50">
                                        <span class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium">Completion Rate</span>
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full bg-purple-600"></div>
                                            <span class="text-xl sm:text-2xl font-black text-purple-600 dark:text-purple-400" id="completion-rate">
                                                @php
                                                    $completed = $viewData['monthly_data']['status_distribution']['completed'] ?? 0;
                                                    $total = $viewData['monthly_data']['total_tasks'] ?? 0;
                                                    $completionRate = $total > 0 ? round(($completed / $total) * 100) : 0;
                                                @endphp
                                                {{ $completionRate }}%
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium">Total Tasks</span>
                                        <span class="text-xl sm:text-2xl font-black text-purple-600 dark:text-purple-400" id="total-tasks">
                                            {{ $viewData['monthly_data']['total_tasks'] ?? 0 }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center p-2 rounded-xl hover:bg-green-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium">Completed Tasks</span>
                                        <span class="text-xl sm:text-2xl font-black text-green-600 dark:text-green-400" id="completed-tasks">
                                            {{ $viewData['monthly_data']['status_distribution']['completed'] ?? 0 }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center p-2 rounded-xl hover:bg-red-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <span class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium">Overdue Tasks</span>
                                        <span class="text-xl sm:text-2xl font-black text-red-600 dark:text-red-400" id="overdue-tasks">
                                            {{ $viewData['monthly_data']['status_distribution']['overdue'] ?? 0 }}
                                        </span>
                                    </div>

                                    <div id="today-tasks-row" class="flex justify-between items-center p-2 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700/50 transition-colors"
                                         x-data
                                         x-show="currentChartData?.monthly_data?.is_current_month">
                                        <span class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium">Today's Tasks</span>
                                        <span class="text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400" id="today-tasks">
                                            {{ $viewData['monthly_data']['status_distribution']['today'] ?? 0 }}
                                        </span>
                                    </div>

                                    <div id="pending-tasks-row" class="flex justify-between items-center p-2 rounded-xl hover:bg-purple-50 dark:hover:bg-gray-700/50 transition-colors"
                                         x-data
                                         x-show="currentChartData?.monthly_data?.is_current_month">
                                        <span class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium">Pending Tasks</span>
                                        <span class="text-xl sm:text-2xl font-black text-purple-600 dark:text-purple-400" id="pending-tasks">
                                            {{ $viewData['monthly_data']['status_distribution']['pending'] ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="bg-white border border-black border-opacity-35 dark:bg-gray-800 rounded-xl p-3 sm:p-4 md:p-6 h-[300px] sm:h-[350px] md:h-[390px] transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl dark:border-gray-600 hover:border-purple-500 dark:hover:border-purple-400">
                            <div class="relative h-full">
                                <canvas id="statusDistributionChart" class="!absolute inset-0"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let dailyTasksChart;
        let statusDistributionChart;
        let currentChartData = null;

        window.addEventListener('theme-changed', function() {
            if (currentChartData) {
                initializeCharts(currentChartData);
            }
        });

        function initializeCharts(data) {
            currentChartData = data;
            
            console.log('Initializing charts with data:', data);
            console.log('Monthly data structure:', {
                days_in_month: data.monthly_data?.days_in_month,
                daily_completion: data.monthly_data?.daily_completion,
                daily_status_completion: data.monthly_data?.daily_status_completion,
                has_completed: data.monthly_data?.daily_status_completion?.completed,
                has_pending: data.monthly_data?.daily_status_completion?.pending
            });

            console.log('Raw data received:', data);
            
            const darkMode = document.documentElement.classList.contains('dark');
            const textColor = darkMode ? '#e5e7eb' : '#374151';
            const gridColor = darkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.2)';
            const backgroundColor = darkMode ? '#1f2937' : '#ffffff';

            if (dailyTasksChart) dailyTasksChart.destroy();
            if (statusDistributionChart) statusDistributionChart.destroy();

            const dailyCtx = document.getElementById('dailyTasksChart');
            if (!dailyCtx) {
                console.error('Could not find dailyTasksChart canvas element');
                return;
            }

            const ctx = dailyCtx.getContext('2d');
            if (!ctx) {
                console.error('Could not get 2d context for dailyTasksChart');
                return;
            }

            console.log('Creating chart with data:', {
                completed: data.monthly_data.daily_status_completion?.completed || [],
                pending: data.monthly_data.daily_status_completion?.pending || [],
                overdue: data.monthly_data.daily_status_completion?.overdue || []
            });

            dailyTasksChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Array.from({length: data.monthly_data.days_in_month}, (_, i) => i + 1),
                    datasets: [{
                        label: 'Total Tasks',
                        data: data.monthly_data.daily_completion,
                        backgroundColor: 'rgba(147, 51, 234, 0.8)',
                        borderColor: 'rgb(147, 51, 234)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Daily Tasks',
                            color: textColor,
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: textColor
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Day of Month',
                                color: textColor
                            },
                            grid: { 
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: { color: textColor }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Tasks',
                                color: textColor
                            },
                            grid: { 
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: { 
                                color: textColor,
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
            const labels = ['Completed', 'Overdue'];
            const chartData = [
                data.monthly_data.status_distribution.completed,
                data.monthly_data.status_distribution.overdue
            ];
            const backgroundColors = [
                'rgba(34, 197, 94, 0.9)',  // green
                'rgba(239, 68, 68, 0.9)'   // red
            ];
            const borderColors = [
                'rgb(22, 163, 74)',        // darker green
                'rgb(220, 38, 38)'         // darker red
            ];

            // Add today's tasks and pending only for current month
            if (data.monthly_data.is_current_month) {
                labels.push('Today\'s Tasks', 'Pending');
                chartData.push(
                    data.monthly_data.status_distribution.today,
                    data.monthly_data.status_distribution.pending
                );
                backgroundColors.push(
                    'rgba(59, 130, 246, 0.9)',  // blue
                    'rgba(147, 51, 234, 0.9)'   // purple
                );
                borderColors.push(
                    'rgb(37, 99, 235)',         // darker blue
                    'rgb(126, 34, 206)'         // darker purple
                );
            }

            statusDistributionChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: chartData,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 2,
                        hoverBorderWidth: 3,
                        hoverBorderColor: borderColors,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    radius: '100%',
                    plugins: {
                        title: {
                            display: true,
                            text: `Task Status Distribution (Total: ${data.monthly_data.total_tasks})`,
                            color: textColor,
                            font: {
                                size: 16,
                                weight: 'bold',
                                family: "'Inter', sans-serif"
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                padding: 15,
                                font: {
                                    size: 12,
                                    weight: 500,
                                    family: "'Inter', sans-serif",
                                    lineHeight: 1.5
                                },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (!data.labels?.length || !data.datasets?.length) {
                                        return [];
                                    }
                                    
                                    const dataset = data.datasets[0];
                                    const total = dataset.data.reduce((acc, val) => acc + (val || 0), 0);
                                    
                                    return data.labels.map((label, i) => {
                                        const value = dataset.data[i] || 0;
                                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        
                                        return {
                                            text: `${label}: ${value} (${percentage}%)`,
                                            fillStyle: dataset.backgroundColor[i],
                                            strokeStyle: dataset.borderColor[i],
                                            lineWidth: 2,
                                            fontColor: textColor,
                                            hidden: false,
                                            index: i,
                                            usePointStyle: true,
                                            boxWidth: 6,
                                            boxHeight: 6
                                        };
                                    });
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            },
                            backgroundColor: darkMode ? 'rgba(17, 24, 39, 0.8)' : 'rgba(255, 255, 255, 0.9)',
                            titleColor: darkMode ? '#ffffff' : '#374151', 
                            bodyColor: darkMode ? '#e5e7eb' : '#4b5563',
                            borderColor: darkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const viewData = @json($viewData ?? null);
            if (viewData) {
                initializeCharts(viewData);
                console.log('View data loaded:', viewData);
            }
        });

        async function updateCharts() {
            const month = document.getElementById('monthSelect').value;
            const year = document.getElementById('yearSelect').value;
            
            try {
                const button = document.querySelector('button[onclick="updateCharts()"]');
                button.disabled = true;
                button.innerHTML = 'Loading...';
                
                const response = await fetch(`/api/analytics/monthly?year=${year}&month=${month}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to update charts');
                }
                
                const data = await response.json();
                
                // Update charts
                initializeCharts(data);
                
                // Update statistics
                const completionRate = data.monthly_data.total_tasks > 0 
                    ? Math.round((data.monthly_data.status_distribution.completed / data.monthly_data.total_tasks) * 100)
                    : 0;
                    
                document.getElementById('completion-rate').textContent = `${completionRate}%`;
                document.getElementById('total-tasks').textContent = data.monthly_data.total_tasks;
                document.getElementById('completed-tasks').textContent = data.monthly_data.status_distribution.completed;
                document.getElementById('overdue-tasks').textContent = data.monthly_data.status_distribution.overdue;
                
                // Only update these if it's current month
                if (data.monthly_data.is_current_month) {
                    document.getElementById('today-tasks').textContent = data.monthly_data.status_distribution.today;
                    document.getElementById('pending-tasks').textContent = data.monthly_data.status_distribution.pending;
                }

                // Show/hide rows based on current month
                document.getElementById('today-tasks-row').style.display = data.monthly_data.is_current_month ? '' : 'none';
                document.getElementById('pending-tasks-row').style.display = data.monthly_data.is_current_month ? '' : 'none';

            } catch (error) {
                console.error('Error updating charts:', error);
                alert('Failed to update charts: ' + error.message);
            } finally {
                const button = document.querySelector('button[onclick="updateCharts()"]');
                button.disabled = false;
                button.innerHTML = 'Update';
            }
        }
    </script>
</x-app-layout>