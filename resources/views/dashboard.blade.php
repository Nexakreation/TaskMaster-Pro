<x-app-layout class="bg-blue-100 dark:bg-gray-800">

    <div class="flex-1 p-1 sm:p-0">
        <h1 class="text-center lg:text-left text-3xl sm:text-4xl font-black mb-6 sm:mb-8 dark:text-gray-100 tracking-tight">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                Home
            </span>
        </h1>
        <div class="mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold mb-4 dark:text-gray-100">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    To-Do List
                </span>
            </h2>
            
            <!-- Task Management Section -->
            <div x-data="taskManager()" class="space-y-4">
                <!-- Add Task Form -->
                <div class="flex flex-col mb-4 space-y-2">
                    <div class="relative flex flex-col sm:flex-row gap-3 p-2 bg-gradient-to-r from-purple-500/10 to-pink-500/10 backdrop-blur-md rounded-2xl shadow-2xl dark:from-purple-900/30 dark:to-pink-900/30 border border-white/20 dark:border-white/5">
                        <input
                            type="text"
                            placeholder="✨ What amazing thing will you accomplish?"
                            x-model="newTask"
                            @keydown.enter="addTask"
                            class="flex-1 h-12 sm:h-16 border-0 bg-white/5 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-base sm:text-xl font-medium placeholder:text-gray-400 focus:ring-2 focus:ring-purple-500 dark:text-gray-100 transition-all duration-300 hover:bg-white/10"
                        />
                        <div class="flex flex-row gap-2 sm:gap-3">
                            <input
                                type="date"
                                x-model="taskDate"
                                :min="getTodayDate()"
                                @input="validateDate($event)"
                                @keydown.prevent
                                onclick="this.showPicker()"
                                class="h-12 sm:h-16 border-0 bg-white/5 backdrop-blur-sm rounded-xl p-3 sm:p-4 dark:text-gray-100 cursor-pointer hover:bg-purple-500/20 transition-all duration-300 focus:ring-2 focus:ring-purple-500 flex-1 sm:flex-none"
                                style="-webkit-appearance: none; -moz-appearance: none;"
                            />
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

                <!-- Tasks List -->
                <div class="rounded-2xl shadow-xl">
                    <div class="flex justify-between items-center mb-4 sm:mb-6 bg-gradient-to-r from-purple-600/10 to-pink-600/10 p-2 rounded-xl backdrop-blur-sm">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <span class="flex h-6 w-6 sm:h-8 sm:w-8 items-center justify-center rounded-lg bg-purple-600 bg-opacity-20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </span>
                            <h3 class="text-lg sm:text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">Recent Tasks</h3>
                        </div>
                        <template x-if="tasks.length > 5">
                            <a href="{{ route('tasks.index') }}" 
                               class="group flex items-center space-x-1 sm:space-x-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg bg-purple-600/10 hover:bg-purple-600 transition-all duration-300">
                                <span class="font-medium text-purple-600 group-hover:text-white transition-colors duration-300 text-sm sm:text-base">View All</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </template>
                    </div>
                    
                    <ul class="space-y-2" x-ref="tasksList">
                        <template x-for="task in recentTasks" :key="task.id">
                            <li class="flex items-center justify-between bg-white dark:bg-gray-700 p-2 rounded shadow transition-all duration-300 group hover:shadow-md text-sm sm:text-base"
                                :class="{ 'opacity-75': task.completed }"
                                draggable="true"
                                @dragstart="dragStartTask($event, task)"
                                @dragend="dragEndTask($event)"
                                @dragover.prevent
                                @drop="dropTask($event, task)"
                                @click.away="handleClickOutside($event, task)">
                                
                                <!-- Drag Handle -->
                                <div class="flex items-center cursor-move px-1 sm:px-2 text-gray-400 dark:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8M8 8h8M8 16h8" />
                                    </svg>
                                </div>

                                <div class="flex items-center flex-1 min-w-0">
                                    <input
                                        type="checkbox"
                                        :checked="task.completed"
                                        @change="updateTaskStatus(task.id, $event.target.checked)"
                                        :disabled="isTaskPastDue(task)"
                                        class="mr-2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-600 text-purple-600 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed h-4 w-4 sm:h-5 sm:w-5"
                                    />
                                    <div class="flex flex-col edit-input-container overflow-hidden">
                                        <span x-show="!task.editing" 
                                            class="dark:text-gray-100 transition-all duration-300 truncate"
                                            :class="{ 'line-through opacity-75': task.completed }"
                                            x-text="task.text">
                                        </span>
                                        <span x-show="!task.editing" 
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                            x-text="formatDate(task.date)">
                                        </span>
                                        <div x-show="task.editing" class="flex flex-col gap-2 w-full">
                                            <input
                                                type="text"
                                                x-model="task.editText"
                                                @keydown.enter="updateTask(task)"
                                                @keydown.escape="cancelEditTask(task)"
                                                x-ref="editInput"
                                                class="w-full px-2 py-1 border rounded dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 text-sm sm:text-base"
                                                @focus="$event.target.select()"
                                                x-init="task.editing && $nextTick(() => $refs.editInput.focus())"
                                            />
                                            <input
                                                type="date"
                                                x-model="task.editDate"
                                                :min="getTodayDate()"
                                                @keydown.prevent
                                                @click="$event.target.showPicker()"
                                                @change="validateEditDate($event, task)"
                                                class="w-full px-2 py-1 border rounded dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 cursor-pointer text-sm sm:text-base"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 sm:gap-2 ml-2">
                                    <!-- Edit/Confirm Buttons -->
                                    <template x-if="!isTaskPastDue(task)">
                                        <button @click="task.editing ? updateTask(task) : editTask(task)" 
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200 p-1"
                                                :class="{ 'hover:text-green-600 dark:hover:text-green-500': task.editing }">
                                            <svg x-show="!task.editing" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <svg x-show="task.editing" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </template>

                                    <!-- Delete/Cancel Buttons -->
                                    <button @click="task.editing ? cancelEditTask(task) : deleteTask(task.id)" 
                                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200 p-1"
                                            :class="{ 'hover:text-red-600 dark:hover:text-red-500': task.editing }">
                                        <svg x-show="!task.editing" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <svg x-show="task.editing" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

            <!-- Task Manager Alpine Component -->
            <script>
                function taskManager() {
                    return {
                        tasks: [],
                        newTask: '',
                        taskDate: new Date().toISOString().split('T')[0],
                        draggedTask: null,
                        currentlyEditing: null,

                        async init() {
                            await this.loadTasks();
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

                        validateDate(event) {
                            const selectedDate = new Date(event.target.value);
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);

                            if (selectedDate < today) {
                                this.taskDate = this.getTodayDate();
                                alert('Please select today or a future date');
                            }
                        },

                        async addTask() {
                            if (this.newTask.trim() === '') return;
                            
                            try {
                                const response = await fetch('/api/tasks', {
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

                                if (!response.ok) throw new Error('Failed to create task');
                                
                                this.newTask = '';
                                await this.loadTasks(); // Reload tasks after adding new one
                            } catch (error) {
                                console.error('Error creating task:', error);
                            }
                        },

                        async updateTaskStatus(taskId, completed) {
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
                                I
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

                        dragStartTask(event, task) {
                            this.draggedTask = task;
                            event.target.classList.add('opacity-50');
                        },

                        dragEndTask(event) {
                            event.target.classList.remove('opacity-50');
                        },

                        dropTask(event, targetTask) {
                            event.preventDefault();
                            if (!this.draggedTask || this.draggedTask.id === targetTask.id) return;

                            const draggedIndex = this.tasks.findIndex(task => task.id === this.draggedTask.id);
                            const targetIndex = this.tasks.findIndex(task => task.id === targetTask.id);

                            // Reorder tasks array
                            this.tasks.splice(draggedIndex, 1);
                            this.tasks.splice(targetIndex, 0, this.draggedTask);

                            // Save to localStorage
                            this.saveTasks();
                        },

                        async editTask(task) {
                            if (this.currentlyEditing && this.currentlyEditing !== task.id) {
                                const previousTask = this.tasks.find(t => t.id === this.currentlyEditing);
                                if (previousTask && previousTask.editing) {
                                    await this.updateTask(previousTask);
                                }
                            }

                            this.currentlyEditing = task.id;
                            task.editText = task.text;
                            task.editDate = task.date;
                            task.editing = true;
                        },

                        async updateTask(task) {
                            if (task.editText && task.editText.trim() !== '') {
                                try {
                                    const response = await fetch(`/api/tasks/${task.id}`, {
                                        method: 'PUT',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            text: task.editText.trim(),
                                            date: task.editDate,
                                            completed: task.completed
                                        })
                                    });

                                    if (!response.ok) throw new Error('Failed to update task');
                                    await this.loadTasks(); // Reload tasks after update
                                } catch (error) {
                                    console.error('Error updating task:', error);
                                }
                            }
                            task.editing = false;
                            this.currentlyEditing = null;
                        },

                        async cancelEditTask(task) {
                            task.editing = false;
                            this.currentlyEditing = null;
                            await this.loadTasks(); // Reload tasks to reset any changes
                        },

                        handleClickOutside(event, task) {
                            const isClickInside = event.target.closest('.edit-input-container');
                            const isEditButton = event.target.closest('.edit-button');
                            
                            if (!isClickInside && !isEditButton && task.editing) {
                                this.updateTask(task);
                            }
                        },

                        validateEditDate(event, task) {
                            const selectedDate = event.target.value;
                            
                            // Check if date is valid
                            if (!selectedDate) {
                                alert('Please select a valid date');
                                event.target.value = task.date; // Reset to original date
                                return false;
                            }

                            const dateObj = new Date(selectedDate);
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);

                            // Validate date is not in the past
                            if (dateObj < today) {
                                alert('Cannot select a past date');
                                event.target.value = task.date; // Reset to original date
                                task.editDate = task.date; // Reset the model
                                return false;
                            }

                            const formattedDate = dateObj.toLocaleDateString('en-US', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            if (confirm(`Are you sure you want to change the date to ${formattedDate}?`)) {
                                task.editDate = selectedDate;
                                return true;
                            } else {
                                event.target.value = task.date; // Reset to original date
                                task.editDate = task.date; // Reset the model
                                return false;
                            }
                        },

                        isTaskPastDue(task) {
                            const taskDate = new Date(task.date);
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);
                            return taskDate < today;
                        },

                        get recentTasks() {
                            return [...this.tasks]
                                .sort((a, b) => {
                                    // First sort by updated_at/created_at (most recent first)
                                    const aDate = new Date(a.updated_at || a.created_at);
                                    const bDate = new Date(b.updated_at || b.created_at);
                                    if (aDate > bDate) return -1;
                                    if (aDate < bDate) return 1;
                                    
                                    // If dates are equal, sort by scheduled date
                                    return new Date(a.date) - new Date(b.date);
                                })
                                .slice(0, 5);
                        }
                    }
                }
            </script>
   <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-4 my-4 sm:my-6 dark:text-gray-100">
      <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
         Sticky Notes
      </span>
   </h2>

        <div class="sticky-wall" x-data="stickyWall()">
            <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-purple-600/10 to-pink-600/10 p-4 rounded-xl backdrop-blur-sm">
                <div class="flex items-center space-x-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-600 bg-opacity-20">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5 text-purple-600" 
                             fill="none" 
                             viewBox="0 0 24 24" 
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    <h3 class="text-lg sm:text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">Recent Notes</h3>
                </div>
                <template x-if="notes.length > 8">
                    <a href="{{ route('notes.index') }}" 
                       class="group flex items-center space-x-2 px-4 py-2 rounded-lg bg-purple-600/10 hover:bg-purple-600 transition-all duration-300">
                        <span class="font-medium text-sm sm:text-base text-purple-600 group-hover:text-white transition-colors duration-300">View All</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </template>
            </div>

            <!-- Notes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Add Note Button -->


                <button @click="showModal = true" 
                class="fixed bottom-8 right-8 w-14 h-14 flex items-center justify-center bg-purple-600 text-white rounded-full shadow-lg hover:bg-purple-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 z-[100]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>

                <template x-for="note in recentNotes" :key="note.id">
                    <div class="note w-full h-64 flex flex-col relative group z-10 hover:z-20" 
                        :class="[
                            note.color,
                            'shadow-md rounded-2xl transition-all duration-300'
                        ]"
                        :style="'transform-origin: center; transform: scale(1);'"
                        @mouseenter="$el.style.transform = 'scale(1.05)'"
                        @mouseleave="$el.style.transform = 'scale(1)'">
                        
                        <!-- Action Buttons -->
                        <div class="absolute top-2 right-2 flex gap-2 transition-opacity duration-200
                                    lg:opacity-0 lg:group-hover:opacity-100">
                            <button @click="editNote(note)" 
                                    class="p-1.5 rounded-full hover:bg-black/10 dark:hover:bg-white/10"
                                    :class="$root.darkMode ? 'text-gray-300' : 'text-gray-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button @click="deleteNote(note.id)" 
                                    class="p-1.5 rounded-full hover:bg-black/10 dark:hover:bg-white/10"
                                    :class="$root.darkMode ? 'text-gray-300' : 'text-gray-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Note Content -->
                        <div class="px-3 pt-3 pb-1">
                            <h3 x-text="note.title" 
                                class="font-bold text-lg"
                                :class="$root.darkMode ? 'text-gray-100' : 'text-gray-900'">
                            </h3>
                        </div>
                        
                        <div class="flex-1 px-3 overflow-auto">
                            <p x-text="note.content" 
                               class="text-sm whitespace-pre-wrap"
                               :class="$root.darkMode ? 'text-gray-300' : 'text-gray-700'">
                            </p>
                        </div>
                        
                        <div class="px-3 pb-3">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in note.tags" :key="tag">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs"
                                          :class="$root.darkMode ? 'bg-white/10 text-gray-300' : 'bg-black/10 text-gray-700'">
                                        <span x-text="tag"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Add Note Modal -->
            <div x-show="showModal" 
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[110]"
                @click.self="showModal = false">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full md:max-w-md max-w-[90%]">
                    <h3 class="text-xl font-bold mb-4 dark:text-gray-100" x-text="editingNote ? 'Edit Note' : 'Add New Note'"></h3>
                    <form @submit.prevent="editingNote ? updateNote() : addNote()">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Title</label>
                            <input type="text" x-model="newNote.title" 
                                class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Content</label>
                            <textarea x-model="newNote.content" 
                                class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                                rows="4"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Tags</label>
                            <div class="flex flex-wrap gap-2 p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
                                <template x-for="tag in newNote.tags" :key="tag">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-sm bg-purple-100 text-purple-700 dark:bg-gray-600 dark:text-gray-200">
                                        <span x-text="tag"></span>
                                        <button type="button" @click="removeTag(tag)" class="ml-1 hover:text-purple-900 dark:hover:text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                                <input
                                    type="text"
                                    x-ref="tagInput"
                                    @keydown.enter.prevent="addTag($event.target.value)"
                                    @keydown.comma.prevent="addTag($event.target.value)"
                                    @keydown.space.prevent="addTag($event.target.value)"
                                    placeholder="Add tags (press Enter or comma to add)"
                                    class="flex-1 min-w-[120px] outline-none border-0 focus:ring-0 p-0 text-sm dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-400"
                                >
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Press Enter, comma, or space to add tags
                            </p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Color</label>
                            <div class="flex space-x-2">
                                <template x-for="color in colors" :key="color">
                                    <button type="button"
                                        @click="newNote.color = color"
                                        :class="[
                                            color,
                                            'w-8 h-8 rounded-full transition-transform hover:scale-110',
                                            newNote.color === color ? 'ring-2 ring-offset-2 ring-purple-600' : ''
                                        ]">
                                    </button>
                                </template>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" 
                                @click="showModal = false; editingNote = null; newNote = { title: '', content: '', color: 'bg-amber-200 dark:bg-amber-300', tags: [] };" 
                                class="px-4 py-2 border rounded dark:border-gray-600 dark:text-gray-300">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                <span x-text="editingNote ? 'Update Note' : 'Save Note'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function stickyWall() {
                return {
                    notes: [],
                    showModal: false,
                    editingNote: null,
                    colors: [
                        'bg-amber-200 dark:bg-amber-300',
                        'bg-rose-200 dark:bg-rose-300',
                        'bg-blue-200 dark:bg-blue-300',
                        'bg-green-200 dark:bg-green-300',
                        'bg-purple-200 dark:bg-purple-300'
                    ],
                    newNote: {
                        title: '',
                        content: '',
                        color: 'bg-amber-200 dark:bg-amber-300',
                        tags: []
                    },
                    draggedNote: null,

                    init() {
                        this.loadNotes();
                    },

                    addTag(value) {
                        const tag = value.trim().toLowerCase();
                        if (tag && !this.newNote.tags.includes(tag)) {
                            this.newNote.tags.push(tag);
                        }
                        this.$refs.tagInput.value = '';
                    },

                    removeTag(tagToRemove) {
                        this.newNote.tags = this.newNote.tags.filter(tag => tag !== tagToRemove);
                    },

                    async loadNotes() {
                        try {
                            const response = await fetch('/api/notes');
                            this.notes = await response.json();
                        } catch (error) {
                            console.error('Error loading notes:', error);
                        }
                    },

                    async addNote() {
                        if (!this.newNote.title || !this.newNote.content) return;

                        try {
                            const response = await fetch('/api/notes', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(this.newNote)
                            });
                            
                            if (!response.ok) throw new Error('Failed to add note');
                            
                            this.showModal = false;
                            this.newNote = { 
                                title: '', 
                                content: '', 
                                color: 'bg-amber-200 dark:bg-amber-300',
                                tags: []
                            };
                            
                            await this.loadNotes();
                        } catch (error) {
                            console.error('Error adding note:', error);
                            alert('Failed to add note. Please try again.');
                        }
                    },

                    initDragAndDrop() {
                        this.$refs.notesContainer.addEventListener('dragover', (e) => {
                            e.preventDefault();
                        });
                    },

                    dragStart(event, note) {
                        this.draggedNote = note;
                        event.target.classList.add('opacity-50');
                    },

                    dragEnd(event) {
                        event.target.classList.remove('opacity-50');
                    },

                    async drop(event, targetNote) {
                        if (!this.draggedNote || this.draggedNote.id === targetNote.id) return;

                        const draggedIndex = this.notes.findIndex(note => note.id === this.draggedNote.id);
                        const targetIndex = this.notes.findIndex(note => note.id === targetNote.id);

                        // Reorder notes array
                        this.notes.splice(draggedIndex, 1);
                        this.notes.splice(targetIndex, 0, this.draggedNote);

                        try {
                            await fetch('/api/notes/reorder', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    noteId: this.draggedNote.id,
                                    targetIndex: targetIndex
                                })
                            });
                        } catch (error) {
                            console.error('Error reordering notes:', error);
                        }
                    },

                    async deleteNote(noteId) {
                        if (!confirm('Are you sure you want to delete this note?')) return;
                        
                        try {
                            await fetch(`/api/notes/${noteId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });
                            
                            this.notes = this.notes.filter(note => note.id !== noteId);
                        } catch (error) {
                            console.error('Error deleting note:', error);
                        }
                    },

                    editNote(note) {
                        this.editingNote = note;
                        this.newNote = {
                            title: note.title,
                            content: note.content,
                            color: note.color || 'bg-amber-200 dark:bg-amber-300',
                            tags: [...note.tags]
                        };
                        this.showModal = true;
                    },

                    async updateNote() {
                        if (!this.editingNote) return this.addNote();

                        try {
                            const response = await fetch(`/api/notes/${this.editingNote.id}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(this.newNote)
                            });

                            if (!response.ok) throw new Error('Failed to update note');
                            
                            this.showModal = false;
                            this.editingNote = null;
                            this.newNote = {
                                title: '',
                                content: '',
                                color: 'bg-amber-200 dark:bg-amber-300',
                                tags: []
                            };
                            
                            await this.loadNotes();
                        } catch (error) {
                            console.error('Error updating note:', error);
                            alert('Failed to update note. Please try again.');
                        }
                    },

                    get recentNotes() {
                        return [...this.notes]
                            .sort((a, b) => {
                                // First sort by updated_at/created_at (most recent first)
                                const aDate = new Date(a.updated_at || a.created_at);
                                const bDate = new Date(b.updated_at || b.created_at);
                                return bDate - aDate;
                            })
                            .slice(0, 8);
                    }
                }
            }
        </script>













        <!-- <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</x-app-layout>
