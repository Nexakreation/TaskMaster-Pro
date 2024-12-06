#!/bin/bash

emcc task_operations.c \
    -o task_operations.js \
    -s WASM=1 \
    -s EXPORTED_FUNCTIONS='["_process_tasks"]' \
    -s EXPORTED_RUNTIME_METHODS='["ccall", "cwrap"]' \
    -s ALLOW_MEMORY_GROWTH=1 \
    -O3 