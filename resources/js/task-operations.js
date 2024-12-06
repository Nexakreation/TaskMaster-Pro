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