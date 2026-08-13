#!/usr/bin/env bash
#
# dev-share.sh
#
# Starts local Laravel + Cloudflare tunnel with one command,
# then highlights the public URL in a big banner and copies it
# to the clipboard so you can paste it straight into a message.
#
# Usage:  ./dev-share.sh
# Stop:   Ctrl+C  (both processes shut down together)
#
# Prereqs:
#   - PHP 8.4 (Laravel Herd ships `php84`)
#   - cloudflared:  brew install cloudflared
#
# The tunnel URL changes every run (free "quick tunnel" limitation).
#
# NOTE: If form submits (login, add to cart) misbehave on the tunnel URL,
# update APP_URL in .env to the tunnel URL and run: php84 artisan config:clear
# then restart the script. Not needed for browsing/design review.

cd "$(dirname "$0")" || exit 1

PORT=8000
PHP_BIN="/Users/skurtovic/Library/Application Support/Herd/bin/php84"

if [ ! -x "$PHP_BIN" ]; then
    if command -v php84 >/dev/null 2>&1; then
        PHP_BIN="$(command -v php84)"
    elif command -v php >/dev/null 2>&1; then
        PHP_BIN="$(command -v php)"
    else
        echo "❌ PHP not found. Install Laravel Herd."
        exit 1
    fi
fi

if ! command -v cloudflared >/dev/null 2>&1; then
    echo "❌ cloudflared not installed."
    echo "   Install with:  brew install cloudflared"
    exit 1
fi

echo ""
echo "🎨  SinonimDesign — dev share"
echo "────────────────────────────────────────────────"
echo "📁  Project: $(pwd)"
echo "🐘  PHP:     $PHP_BIN"
echo "🚀  Local:   http://127.0.0.1:$PORT"
echo "🌐  Tunnel:  starting… (URL banner appears in ~10 seconds)"
echo "────────────────────────────────────────────────"
echo ""

cleanup() {
    echo ""
    echo "🛑  Stopping both processes…"
    kill 0 2>/dev/null
    exit 0
}
trap cleanup EXIT INT TERM

# Start Laravel — quiet output
(
    "$PHP_BIN" artisan serve --port="$PORT" 2>&1 \
        | grep --line-buffered -vE '^\s*$|Press Ctrl' \
        | sed -u 's/^/[laravel] /'
) &

sleep 2

# Start Cloudflare tunnel
# - Filter noise: only show important lines and the URL detection
# - When we spot the trycloudflare.com URL, print a big banner + copy to clipboard
(
    cloudflared tunnel --url "http://localhost:$PORT" 2>&1 | while IFS= read -r line; do
        # Detect the tunnel URL
        if [[ "$line" =~ (https://[a-zA-Z0-9.-]+\.trycloudflare\.com) ]]; then
            URL="${BASH_REMATCH[1]}"
            echo ""
            echo "╔════════════════════════════════════════════════════════════════╗"
            echo "║                                                                ║"
            echo "║   ✅  PUBLIC PREVIEW URL                                       ║"
            echo "║                                                                ║"
            printf   "║   %-60s ║\n" "$URL"
            echo "║                                                                ║"
            echo "║   📋  Copied to clipboard — paste it to your client            ║"
            echo "║                                                                ║"
            echo "╚════════════════════════════════════════════════════════════════╝"
            echo ""

            # Copy to clipboard (macOS)
            if command -v pbcopy >/dev/null 2>&1; then
                echo -n "$URL" | pbcopy
            fi
            continue
        fi

        # Filter out noisy INF lines — keep errors and important events only
        if [[ "$line" =~ (ERR|WRN|failed|error|Error) ]]; then
            echo "[tunnel]  $line"
        fi
    done
) &

wait
