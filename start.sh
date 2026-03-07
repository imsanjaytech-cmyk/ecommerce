#!/bin/bash

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start PHP-FPM in background
php-fpm -D

# Start Nginx
nginx -g "daemon off;"
```

## .dockerignore
```
node_modules
vendor
.env
.git
storage/logs/*