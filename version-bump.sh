#!/bin/bash

# Trading Strategy Tracker Version Bump Script
# Increments version by 0.0.1 and updates all relevant files

set -e

# Read current version
CURRENT_VERSION=$(cat VERSION)
echo "Current version: $CURRENT_VERSION"

# Parse version components
IFS='.' read -ra VERSION_PARTS <<< "$CURRENT_VERSION"
MAJOR=${VERSION_PARTS[0]}
MINOR=${VERSION_PARTS[1]}
PATCH=${VERSION_PARTS[2]}

# Increment patch version
NEW_PATCH=$((PATCH + 1))
NEW_VERSION="$MAJOR.$MINOR.$NEW_PATCH"

echo "New version: $NEW_VERSION"

# Update VERSION file
echo "$NEW_VERSION" > VERSION

# Update composer.json
sed -i.bak "s/\"version\": \"$CURRENT_VERSION\"/\"version\": \"$NEW_VERSION\"/" composer.json
rm composer.json.bak

# Update package.json
sed -i.bak "s/\"version\": \"$CURRENT_VERSION\"/\"version\": \"$NEW_VERSION\"/" package.json
rm package.json.bak

echo "✅ Version updated to $NEW_VERSION"
echo ""
echo "Next steps:"
echo "1. Update CHANGELOG.md with release notes for $NEW_VERSION"
echo "2. Commit changes: git add . && git commit -m \"chore: bump version to $NEW_VERSION\""
echo "3. Tag release: git tag -a v$NEW_VERSION -m \"Release version $NEW_VERSION\""
echo ""
echo "Files updated:"
echo "- VERSION"
echo "- composer.json"
echo "- package.json" 