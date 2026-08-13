#!/usr/bin/env bash
#
# dev-share.sh
#
# Starts the local Laravel server + a Cloudflare tunnel with one command,
# so a public preview URL is available for the client.
#
# Usage:   ./dev-share.sh
# Stop:    Ctrl+C  (both processes shut down together)
#
# Prereqs:
#   - PHP 8.4 (comes with Laravel Herd as `php84`)
#   - cloudflared (install with:  brew install cloudflared)
#
# The tunnel prints a URL like:  https://xxxxx.trycloudflare.com
# Copy that and send it to the client. New URL every run (free-tier limitation).
#
# NOTE: If form submits (login, add to cart, etc.) fail on the tunnel URL,
# update APP_URL in .env to the tunnel URL and run:
#   php84 artisan config:clear
# then restart the script. Not needed for browsing/design review.

cd "$(dirname "$0")" || exit 1

PORT=8000
PHP_BIN="/Users/skurtovic/Library/Application Support/Herd/bin/php84"

# Fall back to system php if Herd's php84 isn't there
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
echo "🌐  Tunnel:  starting… (URL appears below)"
echo "────────────────────────────────────────────────"
echo ""

# Clean up both children on Ctrl+C / exit
cleanup() {
    echo ""
    echo "🛑  Stopping both processes…"
    # Kill everything in this process group
    kill 0 2>/dev/null
    exit 0
}
trap cleanup EXIT INT TERM

# Start Laravel in background, prefix output with [laravel]
(
    "$PHP_BIN" artisan serve --port="$PORT" 2>&1 | sed -u 's/^/[laravel] /'
) &

# Give Laravel a moment to bind the port
sleep 2

# Start Cloudflare tunnel, prefix output with [tunnel] — URL appears here
(
    cloudflared tunnel --url "http://localhost:$PORT" 2>&1 | sed -u 's/^/[tunnel]  /'
) &

# Wait for either process to exit; cleanup trap will handle the rest
wait
