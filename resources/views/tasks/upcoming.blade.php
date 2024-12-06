<x-app-layout class="bg-gradient-to-br from-yellow-50 to-yellow-200 dark:from-gray-900 dark:to-gray-800 min-h-screen">
    <div class="flex-1 p-0 sm:p-8">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-center lg:text-left text-3xl sm:text-4xl font-black mb-6 sm:mb-8 dark:text-gray-100 tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Upcoming Tasks
                </span>
            </h1>
            
            <!-- Task List Section -->
            <div x-data="upcomingTasks()" class="space-y-4 sm:space-y-8">
                <!-- Today's Tasks -->
                <div class="bg-gray-300 border border-black border-opacity-35 dark:bg-gray-700 rounded-2xl shadow-xl p-4 sm:p-8 transform transition-all duration-300 hover:scale-[1.02]">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Today
                    </h2>
                    <template x-if="!hasTodayTasks">
                        <div class="text-center py-4 sm:py-8">
                            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 italic">Your schedule is clear for today ✨</p>
                        </div>
                    </template>
                    <ul class="space-y-3 sm:space-y-4">
                        <template x-for="task in todayTasks" :key="task.id">
                            <li x-html="renderTask(task)" class="transform transition-all duration-300 hover:scale-[1.01]"></li>
                        </template>
                    </ul>
                </div>

                <!-- Tomorrow's Tasks -->
                <div class="bg-gray-300 border border-black border-opacity-35 dark:bg-gray-700 rounded-2xl shadow-xl p-4 sm:p-8 transform transition-all duration-300 hover:scale-[1.02]">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
                        </svg>
                        Tomorrow
                    </h2>
                    <template x-if="!hasTomorrowTasks">
                        <div class="text-center py-4 sm:py-8">
                            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 italic">Tomorrow's looking peaceful 🌅</p>
                        </div>
                    </template>
                    <ul class="space-y-3 sm:space-y-4">
                        <template x-for="task in tomorrowTasks" :key="task.id">
                            <li x-html="renderTask(task)" class="transform transition-all duration-300 hover:scale-[1.01]"></li>
                        </template>
                    </ul>
                </div>

                <!-- Day After Tomorrow's Tasks -->
                <div class="bg-gray-300 border border-black border-opacity-35 dark:bg-gray-700 rounded-2xl shadow-xl p-4 sm:p-8 transform transition-all duration-300 hover:scale-[1.02]">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span x-text="getDayAfterTomorrowHeader()"></span>
                    </h2>
                    <template x-if="!hasDayAfterTomorrowTasks">
                        <div class="text-center py-4 sm:py-8">
                            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 italic" x-text="'No plans yet for ' + getDayAfterTomorrowHeader().toLowerCase() + ' 🌟'"></p>
                        </div>
                    </template>
                    <ul class="space-y-3 sm:space-y-4">
                        <template x-for="task in dayAfterTomorrowTasks" :key="task.id">
                            <li x-html="renderTask(task)" class="transform transition-all duration-300 hover:scale-[1.01]"></li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function upcomingTasks() {
            return {
                tasks: [],
                
                async init() {
                    await this.loadTasks();
                    // Add smooth fade-in animation on load
                    document.querySelector('.max-w-5xl').classList.add('animate-fade-in');
                },

                getTodayDate() {
                    return new Date().toISOString().split('T')[0];
                },

                getTomorrowDate() {
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    return tomorrow.toISOString().split('T')[0];
                },

                getDayAfterTomorrowDate() {
                    const dayAfterTomorrow = new Date();
                    dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);
                    return dayAfterTomorrow.toISOString().split('T')[0];
                },

                getDayAfterTomorrowHeader() {
                    const dayAfterTomorrow = new Date();
                    dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);
                    return dayAfterTomorrow.toLocaleDateString('en-US', { weekday: 'long' });
                },

                async loadTasks() {
                    try {
                        const response = await fetch('/api/tasks');
                        if (!response.ok) throw new Error('Failed to load tasks');
                        this.tasks = await response.json();
                    } catch (error) {
                        console.error('Error loading tasks:', error);
                    }
                },

                get todayTasks() {
                    return this.tasks.filter(task => task.date === this.getTodayDate());
                },

                get tomorrowTasks() {
                    return this.tasks.filter(task => task.date === this.getTomorrowDate());
                },

                get dayAfterTomorrowTasks() {
                    return this.tasks.filter(task => task.date === this.getDayAfterTomorrowDate());
                },

                get hasTodayTasks() {
                    return this.todayTasks.length > 0;
                },

                get hasTomorrowTasks() {
                    return this.tomorrowTasks.length > 0;
                },

                get hasDayAfterTomorrowTasks() {
                    return this.dayAfterTomorrowTasks.length > 0;
                },

                isTaskPastDue(task) {
                    const taskDate = new Date(task.date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    return taskDate < today;
                },

                renderTask(task) {
                    return `
                        <div class="group flex items-center justify-between bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl transition-all duration-300 hover:shadow-lg hover:scale-[1.02] border border-transparent hover:border-purple-200 dark:hover:border-purple-900"
                            :class="{ 'opacity-75': task.completed }">
                            <div class="flex items-center flex-1 gap-3 sm:gap-4">
                                <div class="relative">
                                    <input
                                        type="checkbox"
                                        ${task.completed ? 'checked' : ''}
                                        @change="updateTask(${task.id}, $event.target.checked)"
                                        ${this.isTaskPastDue(task) ? 'disabled' : ''}
                                        class="w-4 h-4 sm:w-5 sm:h-5 rounded-lg border-2 border-purple-300 dark:border-purple-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                                    />
                                </div>
                                <div class="flex flex-col">
                                    <span class="dark:text-gray-100 font-medium transition-all duration-300 text-sm sm:text-base"
                                          :class="{ 'line-through opacity-75': task.completed }"
                                          x-text="task.text">
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 sm:gap-3 opacity-100 group-hover:opacity-100 transition-opacity duration-200">
                                <!-- Reschedule Button -->
                                <div class="relative">
                                    <button @click="$refs['datePicker_${task.id}'].showPicker()" 
                                            class="p-1.5 sm:p-2 rounded-lg text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    
                                    <input
                                        type="date"
                                        :min="getTodayDate()"
                                        :value="task.date"
                                        @change="validateAndConfirmDateChange($event, ${task.id})"
                                        class="absolute top-0 left-0 opacity-0"
                                        style="clip: rect(0,0,0,0);"
                                        x-ref="datePicker_${task.id}"
                                        required
                                    />
                                </div>

                                <!-- Delete Button -->
                                <button @click="deleteTask(${task.id})" 
                                        class="p-1.5 sm:p-2 rounded-lg text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
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
                        const response = await fetch(`/api/tasks/${taskId}`, {
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

                async deleteTask(taskId) {
                    if (confirm('Are you sure you want to delete this task?')) {
                        try {
                            const response = await fetch(`/api/tasks/${taskId}`, {
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

                async validateAndConfirmDateChange(event, taskId) {
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
                        const task = this.tasks.find(t => t.id === taskId);
                        if (task) {
                            try {
                                const response = await fetch(`/api/tasks/${taskId}`, {
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
                        }
                    } else {
                        const task = this.tasks.find(t => t.id === taskId);
                        if (task) {
                            event.target.value = task.date;
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>