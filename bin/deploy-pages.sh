#!/usr/bin/env bash
# Δημόσια προεπισκόπηση στο GitHub Pages: build, στατική εξαγωγή, push στο branch gh-pages.
# Χρήση: bash bin/deploy-pages.sh [URL]   (προεπιλογή: https://elpet.github.io/xdcinema.gr)
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
URL="${1:-https://elpet.github.io/xdcinema.gr}"
OUT="$(mktemp -d)/static"
WT="$(mktemp -d)/ghp"

cd "$ROOT"
npm run build >/dev/null
php bin/export-static.php "$OUT" "$URL"

git worktree prune
git branch -D gh-pages -q 2>/dev/null || true
git worktree add --detach "$WT" HEAD -q
(
    cd "$WT"
    git checkout -q --orphan gh-pages
    git rm -rfq .
    cp -a "$OUT/." .
    git add -A
    git -c core.safecrlf=false commit -q -m "Στατική εξαγωγή για GitHub Pages ($(date +%Y-%m-%d\ %H:%M))"
    git push -f origin gh-pages
)
git worktree remove --force "$WT"
git branch -D gh-pages -q 2>/dev/null || true
rm -rf "$OUT"
echo "OK → $URL/ (το Pages χρειάζεται ~1 λεπτό για να ανανεωθεί)"
