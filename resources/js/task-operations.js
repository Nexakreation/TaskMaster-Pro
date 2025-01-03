// Only try to load WASM if it's enabled
const WASM_ENABLED = false;  // Set this to true when you want to use WASM
let wasmModule = null;

// Load the WebAssembly module
async function initWasm() {
    if (!WASM_ENABLED) return;  // Skip if WASM is not enabled
    
    try {
        const response = await fetch('/wasm/task_operations.wasm');
        if (!response.ok) {
            console.warn('WebAssembly module not found, falling back to JavaScript implementation');
            return;
        }
        const wasmBuffer = await response.arrayBuffer();
        const wasmInstance = await WebAssembly.instantiate(wasmBuffer, {
            env: {
                memory: new WebAssembly.Memory({ initial: 256 })
            }
        });
        wasmModule = wasmInstance.instance;
    } catch (error) {
        console.warn('Failed to load WebAssembly module, falling back to JavaScript implementation:', error);
    }
}

// Only initialize WASM if enabled
if (WASM_ENABLED) {
    initWasm();
}

// JavaScript implementation
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