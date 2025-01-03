<x-app-layout class="bg-gradient-to-br from-yellow-50 to-yellow-200 dark:from-gray-900 dark:to-gray-800">
    <div class="w-full">
        <div class="max-w-4xl mx-auto px-0 sm:px-4">
            <h1 class="text-center lg:text-left text-3xl sm:text-4xl font-black mb-6 sm:mb-8 dark:text-gray-100 tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Today's Tasks
                </span>
            </h1>

            <!-- Task List Section -->
            <div x-data="todayTasks()" class="space-y-4 sm:space-y-6">
                <!-- Tasks List -->
                <div class="bg-gray-300 border border-black border-opacity-35 dark:bg-gray-700 rounded-2xl shadow-xl p-4 sm:p-6 transform transition-all duration-300 hover:scale-[1.01]">
                    <template x-if="!hasTodayTasks">
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-purple-400 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-base sm:text-lg font-medium">Your schedule is clear for today ✨</p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs sm:text-sm mt-2">Time to set some goals!</p>
                        </div>
                    </template>

                    <ul class="space-y-4">
                        <template x-for="task in todayTasks" :key="task.id">
                            <li class="group flex items-center justify-between bg-white dark:bg-gray-800 p-5 rounded-xl transition-all duration-300 hover:shadow-lg hover:scale-[1.02] border border-transparent hover:border-purple-200 dark:hover:border-purple-900"
                                :class="{ 'opacity-75': task.completed }">
                                <div class="flex items-center flex-1 gap-4">
                                    <div class="relative">
                                        <input
                                            type="checkbox"
                                            :checked="task.completed"
                                            @change="updateTask(task.id, $event.target.checked)"
                                            :disabled="isTaskPastDue(task)"
                                            class="w-5 h-5 rounded-lg border-2 border-purple-300 dark:border-purple-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300" />
                                        <div x-show="task.completed" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <svg class="w-3 h-3 text-purple-600 transform scale-0 transition-transform duration-300" :class="{ 'scale-100': task.completed }" fill="currentColor" viewBox="0 0 12 12">
                                                <path d="M3.72 7.96l1.44 1.44L9.72 4.8l1.44 1.44L6.48 12 2.28 7.8l1.44-1.44z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="dark:text-gray-100 font-medium transition-all duration-300 text-sm sm:text-base"
                                            :class="{ 'line-through opacity-75': task.completed }"
                                            x-text="task.text">
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 opacity-100 group-hover:opacity-100 transition-opacity duration-200">
                                    <!-- Reschedule Button -->
                                    <div class="relative" x-data="{ isOpen: false }">
                                        <button @click="$refs.datePicker.showPicker ? $refs.datePicker.showPicker() : $refs.datePicker.focus()"
                                            class="p-2 rounded-lg  text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </button>

                                        <input
                                            type="date"
                                            :min="getTodayDate()"
                                            @change="validateAndConfirmDateChange($event, task)"
                                            class="absolute top-full left-0 mt-1 opacity-0 cursor-pointer"
                                            x-ref="datePicker"
                                            required
                                            :value="task.date"
                                            style="position: absolute; clip: rect(0,0,0,0);" />
                                    </div>

                                    <!-- Delete Button -->
                                    <button @click="deleteTask(task.id)"
                                        class="p-2 rounded-lg  text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function todayTasks() {
            return {
                tasks: [],

                async init() {
                    await this.loadTasks();
                    this.$watch('tasks', () => {
                        this.updateTasksAnimation();
                    });
                },

                updateTasksAnimation() {
                    this.$nextTick(() => {
                        const tasks = document.querySelectorAll('[x-for="task in todayTasks"]');
                        tasks.forEach((task, index) => {
                            task.style.animation = `slideIn 0.3s ease forwards ${index * 0.1}s`;
                        });
                    });
                },

                getTodayDate() {
                    return new Date().toISOString().split('T')[0];
                },

                get todayTasks() {
                    const today = new Date().toISOString().split('T')[0];
                    return this.tasks.filter(task => task.date === today);
                },

                get hasTodayTasks() {
                    return this.todayTasks.length > 0;
                },

                async loadTasks() {
                    try {
                        const response = await fetch('/taskmaster-pro/public/api/tasks');
                        if (!response.ok) throw new Error('Failed to load tasks');
                        this.tasks = await response.json();
                    } catch (error) {
                        console.error('Error loading tasks:', error);
                    }
                },

                // async loadTasks() {
                //     try {
                //         const response = await fetch('taskmaster-pro/public/taskmaster-pro/public/api/tasks');
                //         if (!response.ok) {
                //             const errorDetails = await response.text();
                //             throw new Error(Failed to load tasks: ${response.status} ${response.statusText} - ${errorDetails});
                //         }
                //         this.tasks = await response.json();
                //     } catch (error) {
                //         console.error('Error loading tasks:', error);
                //     }
                // },


                async updateTask(taskId, completed) {
                    const task = this.tasks.find(t => t.id === taskId);
                    if (!task) return;

                    const action = completed ? 'mark as completed' : 'mark as incomplete';
                    if (!confirm(`Are you sure you want to ${action} this task?`)) {
                        await this.loadTasks(); // Reset checkbox state if cancelled
                        return;
                    }

                    try {
                        const response = await fetch(`/taskmaster-pro/public/api/tasks/${taskId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                text: task.text,
                                date: task.date,
                                completed: completed
                            })
                        });

                        if (!response.ok) throw new Error('Failed to update task');
                        await this.loadTasks(); // Reload tasks to show updated state
                    } catch (error) {
                        console.error('Error updating task:', error);
                        await this.loadTasks(); // Reset state if update fails
                    }
                },

                async validateAndConfirmDateChange(event, task) {
                    const selectedDate = event.target.value;

                    if (!selectedDate) {
                        alert('Please select a valid date');
                        return;
                    }

                    const dateObj = new Date(selectedDate);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (dateObj < today) {
                        alert('Cannot select a past date');
                        event.target.value = this.getTodayDate();
                        return;
                    }

                    const formattedDate = dateObj.toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    if (confirm(`Are you sure you want to reschedule this task to ${formattedDate}?`)) {
                        try {
                            const response = await fetch(`/taskmaster-pro/public/api/tasks/${task.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    text: task.text,
                                    date: selectedDate,
                                    completed: task.completed
                                })
                            });

                            if (!response.ok) throw new Error('Failed to update task');
                            await this.loadTasks();
                        } catch (error) {
                            console.error('Error updating task:', error);
                            event.target.value = task.date;
                        }
                    } else {
                        event.target.value = task.date;
                    }
                },

                async deleteTask(taskId) {
                    if (confirm('Are you sure you want to delete this task?')) {
                        try {
                            const response = await fetch(`/taskmaster-pro/public/api/tasks/${taskId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });
                            if (!response.ok) throw new Error('Failed to delete task');
                            await this.loadTasks(); // Reload tasks after deletion
                        } catch (error) {
                            console.error('Error deleting task:', error);
                        }
                    }
                },

                saveTasks() {
                    localStorage.setItem('tasks', JSON.stringify(this.tasks));
                },

                isTaskPastDue(task) {
                    const taskDate = new Date(task.date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    return taskDate < today;
                }
            }
        }
    </script>

    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>