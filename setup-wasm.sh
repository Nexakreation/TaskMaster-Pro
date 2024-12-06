#!/bin/bash

# Check if emsdk is already installed
if [ ! -d "emsdk" ]; then
    echo "Installing Emscripten SDK..."
    git clone https://github.com/emscripten-core/emsdk.git
    cd emsdk
    ./emsdk install latest
    ./emsdk activate latest
    source ./emsdk_env.sh
    cd ..
else
    echo "Emscripten SDK already installed"
    cd emsdk
    source ./emsdk_env.sh
    cd ..
fi

# Create necessary directories
echo "Creating directories..."
mkdir -p public/wasm
mkdir -p resources/wasm

# Make sure the build script is executable
chmod +x resources/wasm/build.sh

# Compile the C code
echo "Compiling C code to WebAssembly..."
cd resources/wasm
./build.sh

# Copy the compiled files to public directory
echo "Copying compiled files to public directory..."
cp task_operations.* ../../public/wasm/

echo "Setup complete!" 