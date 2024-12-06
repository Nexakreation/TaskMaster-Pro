<x-app-layout class="bg-gradient-to-br from-yellow-50 to-yellow-200 dark:from-gray-900 dark:to-gray-800 min-h-screen">







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
                            
                            const newNote = await response.json();
                            this.notes.push(newNote);
                            this.showModal = false;
                            this.newNote = { 
                                title: '', 
                                content: '', 
                                color: 'bg-amber-200 dark:bg-amber-300',
                                tags: []
                            };
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
                            tags: Array.isArray(note.tags) ? [...note.tags] : []
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
                                body: JSON.stringify({
                                    title: this.newNote.title,
                                    content: this.newNote.content,
                                    color: this.newNote.color,
                                    tags: this.newNote.tags || []
                                })
                            });

                            if (!response.ok) throw new Error('Failed to update note');
                            
                            await this.loadNotes(); // Reload all notes to get the latest state
                            this.showModal = false;
                            this.editingNote = null;
                            this.newNote = {
                                title: '',
                                content: '',
                                color: 'bg-amber-200 dark:bg-amber-300',
                                tags: []
                            };
                        } catch (error) {
                            console.error('Error updating note:', error);
                            alert('Failed to update note. Please try again.');
                        }
                    }
                }
            }
        </script>




    <div class="flex-1 p-2">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-center lg:text-left text-3xl sm:text-4xl font-black mb-6 sm:mb-8 dark:text-gray-100 tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Sticky Notes
                </span>
            </h1>
            
            <!-- Notes Grid -->
            <div x-data="stickyWall()" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Add Note Button -->
              
                <template x-for="note in notes" :key="note.id">
                    <div class="note w-full h-64 flex flex-col relative group z-10 hover:z-20" 
                        :class="[
                            note.color,
                            'shadow-md rounded-2xl transition-all duration-300'
                        ]"
                        :style="'transform-origin: center; transform: scale(1);'"
                        @mouseenter="$el.style.transform = 'scale(1.05)'"
                        @mouseleave="$el.style.transform = 'scale(1)'"
                        :data-note-id="note.id"
                        draggable="true"
                        @dragstart="dragStart($event, note)"
                        @dragend="dragEnd($event)"
                        @dragover.prevent
                        @drop="drop($event, note)">
                        
                        <!-- Action Buttons -->
                        <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
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
                                class="font-bold text-lg cursor-move"
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

                <button @click="showModal = true" 
                        class="h-64 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-purple-400 dark:hover:border-purple-500 flex items-center justify-center transition-colors duration-300">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="mt-2 block text-sm font-medium text-gray-600 dark:text-gray-400">
                            Add New Note
                        </span>
                    </div>
                </button>
                            <!-- Add Note Button -->
            <button @click="showModal = true" 
                class="fixed bottom-8 right-8 w-14 h-14 flex items-center justify-center bg-purple-600 text-white rounded-full shadow-lg hover:bg-purple-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 z-[100]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>

            <!-- Notes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" 
            
                x-init="initDragAndDrop()"
                x-ref="notesContainer">
               
            </div>

            <!-- Add Note Modal -->
            <div x-show="showModal" 
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[110]"
                @click.self="showModal = false">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
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
        </div>
    </div>

























    <div class="sticky-wall" x-data="stickyWall()">

        </div>
</x-app-layout> 