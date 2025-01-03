<x-app-layout class="bg-gradient-to-br from-yellow-50 to-yellow-200 dark:from-gray-900 dark:to-gray-800">
    <div class="w-full">
        <div class="max-w-7xl mx-auto px-0 sm:px-4" x-data="taskList()">
            <h1 class="text-center lg:text-left text-3xl sm:text-4xl font-black mb-6 sm:mb-8 dark:text-gray-100 tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Tasks
                </span>
            </h1>

            <!-- Add Task Form -->
            <div class="flex flex-col mb-4 space-y-2">
                <div class="relative flex flex-col sm:flex-row gap-3 p-2 bg-gradient-to-r from-purple-500/10 to-pink-500/10 backdrop-blur-md rounded-2xl shadow-2xl dark:from-purple-900/30 dark:to-pink-900/30 border border-white/20 dark:border-white/5">
                    <input
                        type="text"
                        placeholder="✨ What amazing thing will you accomplish?"
                        x-model="newTask"
                        @keydown.enter="addTask"
                        class="flex-1 h-12 sm:h-16 border-0 bg-white/5 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-base sm:text-xl font-medium placeholder:text-gray-400 focus:ring-2 focus:ring-purple-500 dark:text-gray-100 transition-all duration-300 hover:bg-white/10" />
                    <div class="flex flex-row gap-2 sm:gap-3">
                        <input
                            type="date"
                            x-model="taskDate"
                            :min="getTodayDate()"
                            @input="validateDate($event)"
                            @keydown.prevent
                            onclick="this.showPicker()"
                            class="h-12 sm:h-16 border-0 bg-white/5 backdrop-blur-sm rounded-xl p-3 sm:p-4 dark:text-gray-100 cursor-pointer hover:bg-purple-500/20 transition-all duration-300 focus:ring-2 focus:ring-purple-500 flex-1 sm:flex-none"
                            style="-webkit-appearance: none; -moz-appearance: none;" />
                        <button
                            @click="addTask"
                            class="h-12 sm:h-16 px-4 sm:px-8 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 hover:shadow-xl hover:shadow-purple-500/20 active:translate-y-0.5 transition-all duration-300 font-bold text-base sm:text-lg flex items-center gap-2 sm:gap-3 flex-1 sm:flex-none justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">ADD TASK</span>
                            <span class="sm:hidden">ADD</span>
                        </button>
                    </div>
                </div>
            </div>


            <!-- Filter and Sort Section -->
            <div class="flex flex-col sm:flex-row flex-wrap gap-4 mb-8">
                <!-- Filter Controls -->
                <div class="relative group w-full sm:w-auto">
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-purple-500/10 to-pink-500/10 backdrop-blur-md rounded-xl border border-white/20 dark:border-white/5 shadow-lg hover:shadow-xl transition-all duration-300 dark:from-purple-900/30 dark:to-pink-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <select x-model="filterStatus"
                            class="w-full sm:w-auto bg-gray-50 dark:bg-purple-900 border-0 rounded-lg text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-purple-500 font-medium backdrop-blur-sm py-2 px-4 appearance-none cursor-pointer hover:bg-gray-100 dark:hover:bg-purple-900 transition-colors duration-300">
                            <option value="all">✨ All Tasks</option>
                            <option value="pending">⏳ Pending</option>
                            <option value="overdue">⚠️ Overdue</option>
                            <option value="completed">✅ Completed</option>
                            <option value="incomplete">🎯 In Progress</option>
                        </select>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 blur-xl -z-10 group-hover:blur-2xl transition-all duration-300 opacity-50"></div>
                </div>

                <!-- Sort Controls -->
                <div class="relative group w-full sm:w-auto">
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-purple-500/10 to-pink-500/10 backdrop-blur-md rounded-xl border border-white/20 dark:border-white/5 shadow-lg hover:shadow-xl transition-all duration-300 dark:from-purple-900/30 dark:to-pink-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <select x-model="sortCriteria"
                            class="w-full sm:w-auto bg-gray-50 dark:bg-purple-900 border-0 rounded-lg text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-purple-500 font-medium backdrop-blur-sm py-2 px-4 appearance-none cursor-pointer hover:bg-gray-100 dark:hover:bg-purple-900 transition-colors duration-300">
                            <option value="date-desc">🔽 Newest First</option>
                            <option value="date-asc">🔼 Oldest First</option>
                            <option value="name-asc">🔤 A to Z</option>
                            <option value="name-desc">🔤 Z to A</option>
                        </select>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 blur-xl -z-10 group-hover:blur-2xl transition-all duration-300 opacity-50"></div>
                </div>
            </div>
            <h1 class="text-center lg:text-left text-3xl sm:text-4xl font-black mb-6 sm:mb-8 dark:text-gray-100 tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    All Tasks
                </span>
            </h1>
            <!-- Tasks List -->
            <div class="space-y-6">
                <div class="bg-gray-300 dark:bg-gray-700 rounded-2xl shadow-xl p-4 sm:p-6 border border-black border-opacity-40">
                    <template x-if="!filteredTasks.length">
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <svg class="w-16 h-16 mx-auto text-purple-400 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No tasks yet</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Create your first task to get started!</p>
                        </div>
                    </template>

                    <ul class="space-y-4">
                        <template x-for="task in filteredTasks" :key="task.id">
                            <li class="group flex flex-row sm:flex-row items-start sm:items-center justify-between bg-white border border-black border-opacity-40 dark:border-opacity-0 dark:bg-gray-800 p-4 sm:p-5 rounded-xl transition-all duration-300 hover:shadow-lg hover:scale-[1.02] hover:border-purple-400 dark:hover:border-purple-900"
                                :class="{ 'opacity-75': task.completed }">
                                <div class="flex items-center flex-1 gap-3 sm:gap-4 mb-3 sm:mb-0">
                                    <div class="relative">
                                        <input
                                            type="checkbox"
                                            :checked="task.completed"
                                            @change="updateTask(task.id, $event.target.checked)"
                                            :disabled="isTaskPastDue(task)"
                                            class="w-4 h-4 sm:w-5 sm:h-5 rounded-lg border-2 border-purple-300 dark:border-purple-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300" />
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="dark:text-gray-100 font-medium transition-all duration-300 text-sm sm:text-base"
                                            :class="{ 'line-through opacity-75': task.completed }"
                                            x-text="task.text">
                                        </span>
                                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400" x-text="formatDate(task.date)"></span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 sm:gap-3 w-auto sm:w-auto justify-end">
                                    <!-- Reschedule Button -->
                                    <div class="relative" x-data="{ isOpen: false }" :class="{ 'hidden': isTaskPastDue(task) }">
                                        <button @click="$refs.datePicker.showPicker ? $refs.datePicker.showPicker() : $refs.datePicker.focus()"
                                            :disabled="isTaskPastDue(task)"
                                            class="p-1.5 sm:p-2 rounded-lg text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
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
                                            :disabled="isTaskPastDue(task)"
                                            style="position: absolute; clip: rect(0,0,0,0);" />
                                    </div>

                                    <!-- Delete Button -->
                                    <button @click="deleteTask(task.id)"
                                        class="p-1.5 sm:p-2 rounded-lg text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200">
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
        // Comment out WASM initialization for now
        /*
        let wasmModule = null;

        // Load the WebAssembly module
        async function initWasm() {
            try {
                const response = await fetch('/wasm/task_operations.wasm');
                const wasmBuffer = await response.arrayBuffer();
                const wasmInstance = await WebAssembly.instantiate(wasmBuffer, {
                    env: {
                        memory: new WebAssembly.Memory({ initial: 256 })
                    }
                });
                wasmModule = wasmInstance.instance;
            } catch (error) {
                console.error('Failed to load WebAssembly module:', error);
            }
        }

        // Initialize the WebAssembly module when the page loads
        initWasm();
        */

        function taskList() {
            return {
                tasks: [],
                newTask: '',
                taskDate: new Date().toISOString().split('T')[0],
                filterStatus: 'all',
                sortCriteria: 'date-desc',

                async init() {
                    await this.loadTasks();
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


                get hasTasks() {
                    return this.tasks.length > 0;
                },

                get filteredTasks() {
                    // Always use JavaScript implementation for now
                    return this.filteredTasksJS();

                    /* WASM implementation for future use
                    const wasmResult = processTasks(this.tasks, this.filterStatus, this.sortCriteria);
                    if (wasmResult) {
                        return wasmResult;
                    }
                    return this.filteredTasksJS();
                    */
                },

                // Keep the JavaScript implementation as main implementation
                filteredTasksJS() {
                    let filtered = [...this.tasks];

                    if (this.filterStatus !== 'all') {
                        filtered = filtered.filter(task => {
                            const isOverdue = this.isTaskPastDue(task);

                            switch (this.filterStatus) {
                                case 'completed':
                                    return task.completed;
                                case 'incomplete':
                                    return !task.completed;
                                case 'overdue':
                                    return isOverdue && !task.completed;
                                case 'pending':
                                    return !isOverdue && !task.completed;
                                default:
                                    return true;
                            }
                        });
                    }

                    filtered.sort((a, b) => {
                        switch (this.sortCriteria) {
                            case 'date-desc':
                                return new Date(b.date) - new Date(a.date);
                            case 'date-asc':
                                return new Date(a.date) - new Date(b.date);
                            case 'name-asc':
                                return a.text.localeCompare(b.text);
                            case 'name-desc':
                                return b.text.localeCompare(a.text);
                            default:
                                return 0;
                        }
                    });

                    return filtered;
                },

                formatDate(date) {
                    return new Date(date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                getTodayDate() {
                    return new Date().toISOString().split('T')[0];
                },

                isTaskPastDue(task) {
                    const taskDate = new Date(task.date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    taskDate.setHours(0, 0, 0, 0);
                    return taskDate < today;
                },

                async updateTask(taskId, completed) {
                    const task = this.tasks.find(t => t.id === taskId);
                    if (!task) return;

                    const action = completed ? 'mark as completed' : 'mark as incomplete';
                    if (!confirm(`Are you sure you want to ${action} this task?`)) {
                        await this.loadTasks(); // Reset checkbox state if cancelled
                        return;
                    }

                    try {
                        const response = await fetch(`taskmaster-pro/public/api/tasks/${taskId}`, {
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
                        event.target.value = task.date; // Reset to original date
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
                            await this.loadTasks(); // Reload tasks to show updated state
                        } catch (error) {
                            console.error('Error updating task:', error);
                            event.target.value = task.date; // Reset to original date if update fails
                        }
                    } else {
                        event.target.value = task.date; // Reset to original date if user cancels
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

                async addTask() {
                    if (!this.newTask.trim()) {
                        alert('Please enter a task');
                        return;
                    }

                    if (!this.taskDate) {
                        alert('Please select a date');
                        return;
                    }

                    try {
                        const response = await fetch('/taskmaster-pro/public/api/tasks', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                text: this.newTask,
                                date: this.taskDate
                            })
                        });

                        if (!response.ok) throw new Error('Failed to add task');

                        // Clear the input fields
                        this.newTask = '';
                        this.taskDate = new Date().toISOString().split('T')[0];

                        // Reload tasks to show the new task
                        await this.loadTasks();
                    } catch (error) {
                        console.error('Error adding task:', error);
                        alert('Failed to add task. Please try again.');
                    }
                },

                validateDate(event) {
                    const selectedDate = event.target.value;
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (new Date(selectedDate) < today) {
                        alert('Cannot select a past date');
                        this.taskDate = today.toISOString().split('T')[0];
                    }
                }
            }
        }
    </script>
</x-app-layout>