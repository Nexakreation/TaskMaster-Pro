// Keep WASM code commented for future implementation
/*
let wasmModule = null;
const taskSize = 272; // size of Task struct (4 + 256 + 11 + 1 bytes)

export async function initWasm() {
    try {
        const response = await fetch('/wasm/task_operations.wasm');
        const wasmBuffer = await response.arrayBuffer();
        const wasmInstance = await WebAssembly.instantiate(wasmBuffer, {
            env: {
                memory: new WebAssembly.Memory({ initial: 256 })
            }
        });
        wasmModule = wasmInstance.instance;
        return true;
    } catch (error) {
        console.error('Failed to load WebAssembly module:', error);
        return false;
    }
}

export function processTasks(tasks, filterStatus, sortCriteria) {
    // WASM implementation
}
*/

// JavaScript fallback implementation
export function processTasks(tasks, filterStatus, sortCriteria) {
    let filtered = [...tasks];
    
    // Filter tasks
    if (filterStatus !== 'all') {
        filtered = filtered.filter(task => {
            const isOverdue = isTaskPastDue(task);
            
            switch (filterStatus) {
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
    
    // Sort tasks
    filtered.sort((a, b) => {
        switch (sortCriteria) {
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
}

function isTaskPastDue(task) {
    const taskDate = new Date(task.date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    taskDate.setHours(0, 0, 0, 0);
    return taskDate < today;
} 