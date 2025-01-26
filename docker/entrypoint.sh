#!/bin/bash

# Exit on error
set -e

echo "Starting tmux session manager"

# Main execution flow
if [ $# -gt 0 ]; then
    echo "Executing command: $@"
    exec "$@"
else
    # Default behavior: try to attach, create if doesn't exist
    if ! tmux attach-session -t webapp 2>/dev/null; then
        echo "No existing session, creating new one"
        exec tmux new-session -s webapp
    fi
fi
