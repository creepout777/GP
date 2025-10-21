#!/bin/bash

# Define the root directory for the search (the directory where this script is run)
PROJECT_ROOT=$(pwd)

echo "Project File Map (Relative Paths) for $PROJECT_ROOT"
echo "----------------------------------------------------------------------"

# Use find to search for all .php files and list them, 
# then use sed to remove the leading './' to make the paths cleaner.
find . -type f -name "*.php" | sort | sed 's/^\.\///'

echo "----------------------------------------------------------------------"
echo "✅ Done. Use these paths to fix your 'require_once' statements."
