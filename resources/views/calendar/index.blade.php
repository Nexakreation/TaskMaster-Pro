<x-app-layout class="bg-gradient-to-br from-yellow-50 to-yellow-200 dark:from-gray-900 dark:to-gray-800 min-h-screen">
    <div class="flex-1 p-2 sm:p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-center lg:text-left text-3xl sm:text-4xl font-black mb-6 sm:mb-8 dark:text-gray-100 tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Calendar
                </span>
            </h1>

            <div x-data="calendar()" class="space-y-6">
                <!-- View Selector -->
                <div class="flex flex-col lg:flex-row justify-center items-center border border-black border-opacity-35 sm:justify-between bg-gradient-to-br from-white/90 to-purple-50/90 dark:from-gray-800/90 dark:to-gray-950/90 p-4 sm:p-6 rounded-2xl shadow-2xl dark:border-purple-500/20 transform transition-all duration-300">
                    <div class="flex gap-2 mb-4 lg:mb-0">
                        <button 
                            @click="currentView = 'day'"
                            :class="{ 'bg-purple-600 text-white shadow-lg shadow-purple-500/30': currentView === 'day', 'hover:bg-purple-50 dark:hover:bg-gray-700 dark:text-purple-500': currentView !== 'day' }"
                            class="px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Day
                        </button>
                        <button 
                            @click="currentView = 'week'"
                            :class="{ 'bg-purple-600 text-white shadow-lg shadow-purple-500/30': currentView === 'week', 'hover:bg-purple-50 dark:hover:bg-gray-700 dark:text-purple-500': currentView !== 'week' }"
                            class="px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Week
                        </button>
                        <button 
                            @click="currentView = 'month'"
                            :class="{ 'bg-purple-600 text-white shadow-lg shadow-purple-500/30': currentView === 'month', 'hover:bg-purple-50 dark:hover:bg-gray-700 dark:text-purple-500': currentView !== 'month' }"
                            class="px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Month
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4 lg:gap-6 justify-between w-full">
                        <div class="flex lg:items-center gap-3 sm:mr-auto sm:ml-0">
                            <button @click="previousPeriod" class="p-2 sm:p-3 hover:bg-purple-100 dark:hover:bg-gray-700 rounded-xl transition-all duration-300 hover:scale-110 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button @click="today" 
                                :class="{'w-24 sm:w-32': !(currentView === 'week' && !isThisWeek), 'w-32 sm:w-48': currentView === 'week' && !isThisWeek}"
                                class="px-4 sm:px-6 py-2 sm:py-3 text-white bg-purple-600 hover:from-purple-700 hover:to-pink-700 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-purple-500/30 active:scale-95 text-sm sm:text-base">
                                <span x-text="currentView === 'day' ? (isToday ? 'Today' : formatButtonDate(currentDate)) : 
                                             currentView === 'week' ? (isThisWeek ? 'This&nbsp;Week' : formatWeekRange(currentDate)) : 
                                             (isThisMonth ? 'This&nbsp;Month' : formatMonthText(currentDate))">
                                </span>
                            </button>
                            <button @click="nextPeriod" class="p-2 sm:p-3 hover:bg-purple-100 dark:hover:bg-gray-700 rounded-xl transition-all duration-300 hover:scale-110 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        <h2 class="text-center sm:text-left text-lg sm:text-xl font-black bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600" x-text="currentPeriodLabel"></h2>
                    </div>
                </div>

                <!-- Calendar Views -->
                <div class="bg-gradient-to-br from-white/90 to-purple-50/90 dark:from-gray-900/90 dark:to-gray-800/90 rounded-2xl shadow-2xl backdrop-blur-lg border border-black border-opacity-35 dark:border-purple-900/20 p-4 sm:p-6 lg:p-8 transform transition-all duration-300 hover:shadow-purple-500/20">
                    <!-- Day View -->
                    <div x-show="currentView === 'day'" class="space-y-4">
                        <div class="flex items-center gap-2 sm:gap-4 py-2 sm:py-3 border-b dark:border-gray-700/50 group">
                            <div class="flex-1 min-h-[4rem] rounded-xl group-hover:bg-gradient-to-r from-purple-50 to-pink-50 dark:group-hover:from-gray-800 dark:group-hover:to-gray-700 transition-all duration-300 p-2 sm:p-4">
                                <template x-if="renderDayItems(currentDate)">
                                    <div x-html="renderDayItems(currentDate)"></div>
                                </template>
                                <template x-if="!renderDayItems(currentDate)">
                                    <div class="text-center py-4 sm:py-8 space-y-2 sm:space-y-4">
                                        <div class="flex justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 sm:h-16 sm:w-16 text-purple-400/50 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="space-y-1 sm:space-y-2">
                                            <p class="text-lg sm:text-xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                                <span x-text="isToday ? 'Your Schedule is Clear!' : `No Plans for ${formatDate(currentDate)}`"></span>
                                            </p>
                                            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">
                                                <span x-text="isToday ? 'Time to make something amazing happen!' : 'Add a task to make the day'"></span>
                                            </p>
                                        </div>
                                        <a href="{{ route('tasks.index') }}"
                                                class="mt-2 sm:mt-4 px-4 sm:px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 hover:shadow-xl hover:shadow-purple-500/20 active:scale-95 transition-all duration-300 inline-block text-center text-sm sm:text-base">
                                            Add Task
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Week View -->
                    <div x-show="currentView === 'week'" class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-4 lg:gap-6">
                        <template x-for="(day, index) in weekDays" :key="day">
                            <div class="space-y-2 sm:space-y-4 group">
                                <div class="text-center font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 text-sm sm:text-base" x-text="day"></div>
                                <div class="h-48 sm:h-64 lg:h-[32rem] rounded-xl group-hover:bg-gradient-to-br from-purple-50 to-pink-50 dark:group-hover:from-gray-800 dark:group-hover:to-gray-700 transition-all duration-300 p-2 sm:p-4 overflow-y-auto"
                                    :class="{ 'border-2 border-purple-500 dark:border-purple-400 shadow-lg shadow-purple-500/20': isTodayDate(getWeekDate(index)) }">
                                    <div x-html="renderDayItems(getWeekDate(index))"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Month View -->
                    <div x-show="currentView === 'month'" class="overflow-x-auto">
                        <div class="min-w-[800px]"> <!-- Minimum width to ensure readability -->
                            <!-- Days of Week Header -->
                            <div class="grid grid-cols-7 gap-2 mb-2">
                                <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
                                    <div class="text-center font-bold py-2 bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 text-sm" x-text="day"></div>
                                </template>
                            </div>

                            <!-- Calendar Grid -->
                            <div class="grid grid-cols-7 gap-2">
                                <template x-for="(day, index) in monthDays" :key="index">
                                    <div class="min-h-[100px] p-2 border border-black border-opacity-35 dark:border-purple-900/20 hover:bg-gradient-to-br from-purple-50 to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700 transition-all duration-300 rounded-xl overflow-y-auto"
                                         :class="{ 'border-2 !border-purple-500 dark:!border-purple-400 shadow-lg shadow-purple-500/20': day && isTodayDate(new Date(currentDate.getFullYear(), currentDate.getMonth(), day)) }">
                                        <span class="font-medium text-sm group-hover:bg-clip-text group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-pink-600" x-text="day"></span>
                                        <div x-html="day ? renderDayItems(new Date(currentDate.getFullYear(), currentDate.getMonth(), day)) : ''"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calendar() {
            return {
                currentView: 'day',
                currentDate: new Date(),
                weekDays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                tasks: [],
                
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

                getItemsForDate(date) {
                    const dateStr = new Date(date).toISOString().split('T')[0];
                    const tasksForDate = this.tasks.filter(task => task.date === dateStr);
                    
                    return {
                        tasks: tasksForDate
                    };
                },

                renderDayItems(date) {
                    const items = this.getItemsForDate(date);
                    let html = '';

                    items.tasks.forEach(task => {
                        html += `
                            <div class="p-2 mb-2 ${task.completed ? 'bg-green-200 dark:bg-green-900/30' : 'bg-purple-200 dark:bg-purple-900/30'} rounded-lg shadow-sm group hover:shadow-md transition-all duration-200 border border-black/5 dark:border-white/5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full ${task.completed ? 'bg-green-500' : 'bg-purple-500'}"></div>
                                        <span class="${task.completed ? 'line-through text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-100'} ${this.currentView === 'month' ? 'text-xs' : 'text-sm'}">${task.text}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    return html;
                },

                get monthDays() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const days = [];
                    
                    // Add empty days for padding
                    for (let i = 0; i < firstDay.getDay(); i++) {
                        days.push('');
                    }
                    
                    // Add month days
                    for (let i = 1; i <= lastDay.getDate(); i++) {
                        days.push(i);
                    }
                    
                    return days;
                },

                get currentPeriodLabel() {
                    const options = { 
                        month: 'long',
                        year: 'numeric'
                    };
                    
                    if (this.currentView === 'day') {
                        options.day = 'numeric';
                        options.weekday = 'long';
                    } else if (this.currentView === 'week') {
                        // Add week range
                        const startOfWeek = new Date(this.currentDate);
                        startOfWeek.setDate(this.currentDate.getDate() - this.currentDate.getDay());
                        const endOfWeek = new Date(startOfWeek);
                        endOfWeek.setDate(startOfWeek.getDate() + 6);
                        
                        return `${startOfWeek.toLocaleDateString('en-US', { month: 'long', day: 'numeric' })} - ${endOfWeek.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`;
                    }
                    
                    return this.currentDate.toLocaleDateString('en-US', options);
                },

                formatHour(hour) {
                    return `${hour % 12 || 12}:00 ${hour < 12 ? 'AM' : 'PM'}`;
                },

                previousPeriod() {
                    const date = new Date(this.currentDate);
                    if (this.currentView === 'day') {
                        date.setDate(date.getDate() - 1);
                    } else if (this.currentView === 'week') {
                        date.setDate(date.getDate() - 7);
                    } else {
                        date.setMonth(date.getMonth() - 1);
                    }
                    this.currentDate = date;
                },

                nextPeriod() {
                    const date = new Date(this.currentDate);
                    if (this.currentView === 'day') {
                        date.setDate(date.getDate() + 1);
                    } else if (this.currentView === 'week') {
                        date.setDate(date.getDate() + 7);
                    } else {
                        date.setMonth(date.getMonth() + 1);
                    }
                    this.currentDate = date;
                },

                today() {
                    this.currentDate = new Date();
                },

                get isToday() {
                    const today = new Date();
                    return this.currentDate.toDateString() === today.toDateString();
                },

                formatButtonDate(date) {
                    return date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                },

                formatWeekRange(date) {
                    const start = new Date(date);
                    start.setDate(date.getDate() - date.getDay());
                    const end = new Date(start);
                    end.setDate(start.getDate() + 6);
                    
                    return `${this.formatButtonDate(start)} - ${this.formatButtonDate(end)}`;
                },

                formatMonthText(date) {
                    return date.toLocaleDateString('en-US', {
                        month: 'long'
                    });
                },

                getWeekDate(dayIndex) {
                    const date = new Date(this.currentDate);
                    const diff = date.getDay() - dayIndex;
                    date.setDate(date.getDate() - diff);
                    return date;
                },

                isTodayDate(date) {
                    const today = new Date();
                    return date.toDateString() === today.toDateString();
                },

                get isThisWeek() {
                    const today = new Date();
                    const start = new Date(this.currentDate);
                    start.setDate(this.currentDate.getDate() - this.currentDate.getDay());
                    const end = new Date(start);
                    end.setDate(start.getDate() + 6);
                    
                    return today >= start && today <= end;
                },

                get isThisMonth() {
                    const today = new Date();
                    return this.currentDate.getMonth() === today.getMonth() && 
                           this.currentDate.getFullYear() === today.getFullYear();
                }
            }
        }
    </script>
</x-app-layout> 