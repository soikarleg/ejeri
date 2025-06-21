#!/bin/bash

# === CONFIGURATION ===
BASE_DIR="$(dirname "$(dirname "$0")")" # remonte au dossier racine du projet
LOG_FILE="$BASE_DIR/shared/htaccess-lock.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

# === USAGE ===
usage() {
    echo "Usage: $0 [--lock|--unlock] <module>"
    echo "Example: $0 --lock app"
    exit 1
}

# === CHECK PARAMS ===
ACTION=""
MODULE="$2"

[[ "$1" == "--lock" ]] && ACTION="lock"
[[ "$1" == "--unlock" ]] && ACTION="unlock"
[[ -z "$ACTION" || -z "$MODULE" ]] && usage

HTACCESS="$BASE_DIR/$MODULE/.htaccess"
BACKUP="$BASE_DIR/$MODULE/.htaccess.bak"

# === VERIFY TARGET ===
if [ ! -f "$HTACCESS" ]; then
    echo "❌ [$DATE] Fichier introuvable: $HTACCESS" | tee -a "$LOG_FILE"
    exit 1
fi

# === ACTIONS ===

if [ "$ACTION" == "unlock" ]; then
    echo "🔓 [$DATE] Déverrouillage de $HTACCESS" | tee -a "$LOG_FILE"

    sudo chattr -i "$HTACCESS" 2>/dev/null
    sudo chmod 644 "$HTACCESS"

    echo "✅ [$DATE] $HTACCESS est maintenant modifiable." | tee -a "$LOG_FILE"

elif [ "$ACTION" == "lock" ]; then
    echo "🔐 [$DATE] Verrouillage de $HTACCESS" | tee -a "$LOG_FILE"

    # Sauvegarde avant lock
    cp "$HTACCESS" "$BACKUP"

    sudo chmod 444 "$HTACCESS"
    sudo chattr +i "$HTACCESS" 2>/dev/null

    echo "✅ [$DATE] $HTACCESS est maintenant protégé. Backup: $BACKUP" | tee -a "$LOG_FILE"
fi

