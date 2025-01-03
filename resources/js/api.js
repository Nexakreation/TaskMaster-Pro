const baseUrl = window.baseUrl || '';

export const api = {
    notes: {
        fetch: async () => {
            const response = await fetch(`${baseUrl}/api/notes`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            if (!response.ok) throw new Error('Failed to fetch notes');
            return response.json();
        },
        create: async (note) => {
            const response = await fetch(`${baseUrl}/api/notes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(note)
            });
            if (!response.ok) throw new Error('Failed to create note');
            return response.json();
        },
        update: async (note) => {
            const response = await fetch(`${baseUrl}/api/notes/${note.id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(note)
            });
            if (!response.ok) throw new Error('Failed to update note');
            return response.json();
        },
        delete: async (noteId) => {
            const response = await fetch(`${baseUrl}/api/notes/${noteId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            if (!response.ok) throw new Error('Failed to delete note');
            return response.json();
        }
    },
    tasks: {
        fetch: async () => {
            const response = await fetch(`${baseUrl}/api/tasks`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            if (!response.ok) throw new Error('Failed to fetch tasks');
            return response.json();
        },
        create: async (task) => {
            const response = await fetch(`${baseUrl}/api/tasks`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(task)
            });
            if (!response.ok) throw new Error('Failed to create task');
            return response.json();
        },
        update: async (task) => {
            const response = await fetch(`${baseUrl}/api/tasks/${task.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(task)
            });
            if (!response.ok) throw new Error('Failed to update task');
            return response.json();
        },
        delete: async (taskId) => {
            const response = await fetch(`${baseUrl}/api/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            if (!response.ok) throw new Error('Failed to delete task');
            return response.json();
        }
    }
};