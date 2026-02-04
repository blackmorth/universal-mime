#!/bin/bash
# Simplifie l’usage de gosu comme su-exec
set -e

USER_ID=${LOCAL_UID:-1000}
GROUP_ID=${LOCAL_GID:-1000}

# Create user/group dynamically
groupmod -o -g "$GROUP_ID" dev
usermod  -o -u "$USER_ID" dev

# Switch
exec gosu dev "$@"
