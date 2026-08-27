#!/bin/bash

echo "==================================="
echo "  SETUP GITHUB REPOSITORY"
echo "==================================="
echo ""

# Check if gh is installed
if ! command -v gh &> /dev/null; then
    echo "❌ GitHub CLI tidak terinstall"
    echo "Install dengan: sudo apt install gh"
    exit 1
fi

# Check if already authenticated
if gh auth status &> /dev/null; then
    echo "✅ Sudah terautentikasi dengan GitHub"
else
    echo "📋 Login ke GitHub..."
    echo "Silakan login menggunakan browser yang muncul"
    gh auth login --hostname github.com --git-protocol ssh --web
fi

# Get GitHub username
GITHUB_USER=$(gh api user --jq '.login' 2>/dev/null)
if [ -z "$GITHUB_USER" ]; then
    echo "❌ Gagal mendapatkan username GitHub"
    exit 1
fi

echo ""
echo "✅ Username: $GITHUB_USER"
echo ""

# Create repository
echo "📦 Membuat repository pos-app..."
gh repo create pos-app --public --source=. --remote=origin --push 2>/dev/null || {
    echo "Repository mungkin sudah ada, mencoba push..."
    git remote add origin "git@github.com:$GITHUB_USER/pos-app.git" 2>/dev/null || true
    git push -u origin master
}

echo ""
echo "==================================="
echo "  SELESAI!"
echo "==================================="
echo "Repository: https://github.com/$GITHUB_USER/pos-app"
echo ""

