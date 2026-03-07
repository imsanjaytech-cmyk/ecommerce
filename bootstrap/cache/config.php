<?php return array (
  'app' => 
  array (
    'name' => 'Laravel',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => 'base64:RbT8XsjfYVNJltsdxVKmjJ9OoWtD+YfOdY1Nhe0JVow=',
    'cipher' => 'AES-256-CBC',
    'maintenance' => 
    array (
      'driver' => 'file',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      15 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      16 => 'Illuminate\\Queue\\QueueServiceProvider',
      17 => 'Illuminate\\Redis\\RedisServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'App\\Providers\\AppServiceProvider',
      23 => 'App\\Providers\\AuthServiceProvider',
      24 => 'App\\Providers\\EventServiceProvider',
      25 => 'App\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Number' => 'Illuminate\\Support\\Number',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Process' => 'Illuminate\\Support\\Facades\\Process',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'sanctum' => 
      array (
        'driver' => 'sanctum',
        'provider' => NULL,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'cluster' => 'mt1',
          'host' => 'api-mt1.pusher.com',
          'port' => '443',
          'scheme' => 'https',
          'encrypted' => true,
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\framework/cache/data',
        'lock_path' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
    ),
    'prefix' => 'laravel_cache_',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'ecommerce',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'ecommerce',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'ecommerce',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'ecommerce',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'laravel_database_',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\app',
        'throw' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\app/public',
        'url' => 'http://localhost/storage',
        'visibility' => 'public',
        'throw' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      'C:\\Users\\SanjayS\\ecommerce\\public\\storage' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\app/public',
    ),
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 12,
      'verify' => true,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
      'verify' => true,
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => NULL,
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\logs/laravel.log',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
        'replace_placeholders' => true,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
        'facility' => 8,
        'replace_placeholders' => true,
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\logs/laravel.log',
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'url' => NULL,
        'host' => 'mailpit',
        'port' => '1025',
        'encryption' => NULL,
        'username' => NULL,
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => NULL,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Laravel',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'C:\\Users\\SanjayS\\ecommerce\\resources\\views/vendor/mail',
      ),
    ),
  ),
  'products' => 
  array (
    'site' => 
    array (
      'brand' => 'Shanas',
      'currency' => '₹',
      'support_email' => 'support@shanas.com',
    ),
    'hero' => 
    array (
      'title' => 'Luxury Gifts for Every Occasion',
      'subtitle' => 'Curated premium hampers crafted with elegance & love.',
      'button_text' => 'Shop Now',
      'button_link' => '/products',
      'images' => 
      array (
        'top' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=800&h=500&auto=format&fit=crop&q=80&crop=center',
        'bottom_1' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=400&h=300&auto=format&fit=crop&q=80&crop=center',
        'bottom_2' => 'https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=400&h=300&auto=format&fit=crop&q=80&crop=center',
      ),
    ),
    'categories' => 
    array (
      'all' => '🎁 All',
      'birthday' => '🎂 Birthday',
      'wedding' => '💍 Wedding',
      'corporate' => '🏢 Corporate',
      'festive' => '✨ Festive',
      'hampers' => '🧺 Hampers',
      'candles' => '🕯️ Candles',
      'jewellery' => '💎 Jewellery',
    ),
    'bento' => 
    array (
      0 => 
      array (
        'title' => 'Luxury Hampers',
        'image' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=700&h=900&auto=format&fit=crop&q=80&crop=center',
        'link' => '/products?category=hampers',
      ),
      1 => 
      array (
        'title' => 'Premium Candles',
        'image' => 'https://images.unsplash.com/photo-1603905856869-71f0f17f6dc5?w=700&h=320&auto=format&fit=crop&q=80&crop=center',
        'link' => '/products?category=candles',
      ),
      2 => 
      array (
        'title' => 'Elegant Jewellery',
        'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=350&h=300&auto=format&fit=crop&q=80&crop=center',
        'link' => '/products?category=jewellery',
      ),
      3 => 
      array (
        'title' => 'Exclusive Gifts',
        'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=350&h=300&auto=format&fit=crop&q=80&crop=center',
        'link' => '/products',
      ),
    ),
    'promo' => 
    array (
      0 => 
      array (
        'title' => 'Festive Collection',
        'desc' => 'Celebrate every festival with premium gift boxes.',
        'image' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=800&h=500&auto=format&fit=crop&q=80&crop=entropy',
      ),
      1 => 
      array (
        'title' => 'Custom Hampers',
        'desc' => 'Personalised gifting made luxurious.',
        'image' => 'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=500&h=500&auto=format&fit=crop&q=80&crop=center',
      ),
      2 => 
      array (
        'title' => 'Corporate Gifting',
        'desc' => 'Premium bulk gifting solutions.',
        'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=500&h=500&auto=format&fit=crop&q=80&crop=center',
      ),
    ),
    'features' => 
    array (
      0 => 
      array (
        'icon' => 'bi-truck',
        'title' => 'Free Shipping',
        'desc' => 'On orders above ₹999',
      ),
      1 => 
      array (
        'icon' => 'bi-shield-check',
        'title' => 'Secure Payments',
        'desc' => '100% safe & encrypted',
      ),
      2 => 
      array (
        'icon' => 'bi-gift',
        'title' => 'Premium Packaging',
        'desc' => 'Luxury unboxing experience',
      ),
      3 => 
      array (
        'icon' => 'bi-arrow-repeat',
        'title' => 'Easy Returns',
        'desc' => 'Hassle-free 7-day returns',
      ),
    ),
    'testimonials' => 
    array (
      0 => 
      array (
        'name' => 'Anita Sharma',
        'comment' => 'Absolutely loved the luxury hamper! Packaging was stunning and delivery was super fast.',
        'rating' => 5,
        'avatar' => 'https://i.pravatar.cc/80?img=47',
      ),
      1 => 
      array (
        'name' => 'Rahul Mehta',
        'comment' => 'Best corporate gifting service we have ever used. The team was super helpful.',
        'rating' => 4,
        'avatar' => 'https://i.pravatar.cc/80?img=12',
      ),
      2 => 
      array (
        'name' => 'Priya Nair',
        'comment' => 'Gifted this for a wedding — everyone was blown away by the presentation!',
        'rating' => 5,
        'avatar' => 'https://i.pravatar.cc/80?img=32',
      ),
      3 => 
      array (
        'name' => 'Vikram Iyer',
        'comment' => 'Ordered a festive hamper last minute and it arrived beautifully packed on time.',
        'rating' => 5,
        'avatar' => 'https://i.pravatar.cc/80?img=8',
      ),
    ),
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'slug' => 'luxury-birthday-hamper',
        'name' => 'Luxury Birthday Hamper',
        'category' => 'birthday',
        'price' => 2499,
        'old_price' => 2999,
        'rating' => 5,
        'review_count' => 24,
        'badge' => 'sale',
        'filter' => 'birthday',
        'image' => 'https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
      ),
      1 => 
      array (
        'id' => 2,
        'slug' => 'premium-wedding-gift-box',
        'name' => 'Premium Wedding Gift Box',
        'category' => 'wedding',
        'price' => 4599,
        'old_price' => 5200,
        'rating' => 5,
        'review_count' => 41,
        'badge' => 'new',
        'filter' => 'wedding',
        'image' => 'https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
      ),
      2 => 
      array (
        'id' => 3,
        'slug' => 'corporate-deluxe-hamper',
        'name' => 'Corporate Deluxe Hamper',
        'category' => 'corporate',
        'price' => 3299,
        'old_price' => NULL,
        'rating' => 4,
        'review_count' => 16,
        'badge' => NULL,
        'filter' => 'corporate',
        'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
      ),
      3 => 
      array (
        'id' => 4,
        'slug' => 'festive-gourmet-box',
        'name' => 'Festive Gourmet Box',
        'category' => 'festive',
        'price' => 1599,
        'old_price' => NULL,
        'rating' => 5,
        'review_count' => 32,
        'badge' => NULL,
        'filter' => 'festive',
        'image' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
      ),
      4 => 
      array (
        'id' => 5,
        'slug' => 'luxury-candle-gift-set',
        'name' => 'Luxury Candle Gift Set',
        'category' => 'candles',
        'price' => 1299,
        'old_price' => 1599,
        'rating' => 5,
        'review_count' => 19,
        'badge' => 'sale',
        'filter' => 'candles',
        'image' => 'https://images.unsplash.com/photo-1737091862932-35b489b8fe8d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8bHV4dXJ5JTIwY2FuZGxlJTIwc2V0fGVufDB8fDB8fHww',
      ),
      5 => 
      array (
        'id' => 6,
        'slug' => 'personalised-jewellery-box',
        'name' => 'Personalised Jewellery Box',
        'category' => 'jewellery',
        'price' => 3799,
        'old_price' => NULL,
        'rating' => 4,
        'review_count' => 11,
        'badge' => 'new',
        'filter' => 'jewellery',
        'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
      ),
      6 => 
      array (
        'id' => 7,
        'slug' => 'wedding-trousseau-hamper',
        'name' => 'Wedding Trousseau Hamper',
        'category' => 'wedding',
        'price' => 6499,
        'old_price' => 7500,
        'rating' => 5,
        'review_count' => 28,
        'badge' => 'sale',
        'filter' => 'wedding',
        'image' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
      ),
      7 => 
      array (
        'id' => 8,
        'slug' => 'premium-chocolate-hamper',
        'name' => 'Premium Chocolate Hamper',
        'category' => 'birthday',
        'price' => 1899,
        'old_price' => 2200,
        'rating' => 5,
        'review_count' => 47,
        'badge' => NULL,
        'filter' => 'birthday',
        'image' => 'https://images.unsplash.com/photo-1548907040-4baa42d10919?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
    ),
    'batching' => 
    array (
      'database' => 'mysql',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'sanctum' => 
  array (
    'stateful' => 
    array (
      0 => 'localhost',
      1 => 'localhost:3000',
      2 => '127.0.0.1',
      3 => '127.0.0.1:8000',
      4 => '::1',
      5 => 'localhost',
    ),
    'guard' => 
    array (
      0 => 'web',
    ),
    'expiration' => NULL,
    'token_prefix' => '',
    'middleware' => 
    array (
      'authenticate_session' => 'Laravel\\Sanctum\\Http\\Middleware\\AuthenticateSession',
      'encrypt_cookies' => 'App\\Http\\Middleware\\EncryptCookies',
      'verify_csrf_token' => 'App\\Http\\Middleware\\VerifyCsrfToken',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
      'scheme' => 'https',
    ),
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'razorpay' => 
    array (
      'key' => 'rzp_test_SJtospNjnjyjTG',
      'secret' => 'WwObk6hmUrbSbrQ41LlzTt2D',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\Users\\SanjayS\\ecommerce\\resources\\views',
    ),
    'compiled' => 'C:\\Users\\SanjayS\\ecommerce\\storage\\framework\\views',
  ),
  'flare' => 
  array (
    'key' => NULL,
    'flare_middleware' => 
    array (
      0 => 'Spatie\\FlareClient\\FlareMiddleware\\RemoveRequestIp',
      1 => 'Spatie\\FlareClient\\FlareMiddleware\\AddGitInformation',
      2 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddNotifierName',
      3 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddEnvironmentInformation',
      4 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionInformation',
      5 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddDumps',
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddLogs' => 
      array (
        'maximum_number_of_collected_logs' => 200,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddQueries' => 
      array (
        'maximum_number_of_collected_queries' => 200,
        'report_query_bindings' => true,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddJobs' => 
      array (
        'max_chained_job_reporting_depth' => 5,
      ),
      6 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddContext',
      7 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionHandledStatus',
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestBodyFields' => 
      array (
        'censor_fields' => 
        array (
          0 => 'password',
          1 => 'password_confirmation',
        ),
      ),
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestHeaders' => 
      array (
        'headers' => 
        array (
          0 => 'API-KEY',
          1 => 'Authorization',
          2 => 'Cookie',
          3 => 'Set-Cookie',
          4 => 'X-CSRF-TOKEN',
          5 => 'X-XSRF-TOKEN',
        ),
      ),
    ),
    'send_logs_as_events' => true,
  ),
  'ignition' => 
  array (
    'editor' => 'phpstorm',
    'theme' => 'auto',
    'enable_share_button' => true,
    'register_commands' => false,
    'solution_providers' => 
    array (
      0 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\BadMethodCallSolutionProvider',
      1 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\MergeConflictSolutionProvider',
      2 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\UndefinedPropertySolutionProvider',
      3 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\IncorrectValetDbCredentialsSolutionProvider',
      4 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingAppKeySolutionProvider',
      5 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\DefaultDbNameSolutionProvider',
      6 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\TableNotFoundSolutionProvider',
      7 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingImportSolutionProvider',
      8 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\InvalidRouteActionSolutionProvider',
      9 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\ViewNotFoundSolutionProvider',
      10 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\RunningLaravelDuskInProductionProvider',
      11 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingColumnSolutionProvider',
      12 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownValidationSolutionProvider',
      13 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingMixManifestSolutionProvider',
      14 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingViteManifestSolutionProvider',
      15 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingLivewireComponentSolutionProvider',
      16 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UndefinedViewVariableSolutionProvider',
      17 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\GenericLaravelExceptionSolutionProvider',
      18 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\OpenAiSolutionProvider',
      19 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\SailNetworkSolutionProvider',
      20 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMysql8CollationSolutionProvider',
      21 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMariadbCollationSolutionProvider',
    ),
    'ignored_solution_providers' => 
    array (
    ),
    'enable_runnable_solutions' => NULL,
    'remote_sites_path' => 'C:\\Users\\SanjayS\\ecommerce',
    'local_sites_path' => '',
    'housekeeping_endpoint_prefix' => '_ignition',
    'settings_file_path' => '',
    'recorders' => 
    array (
      0 => 'Spatie\\LaravelIgnition\\Recorders\\DumpRecorder\\DumpRecorder',
      1 => 'Spatie\\LaravelIgnition\\Recorders\\JobRecorder\\JobRecorder',
      2 => 'Spatie\\LaravelIgnition\\Recorders\\LogRecorder\\LogRecorder',
      3 => 'Spatie\\LaravelIgnition\\Recorders\\QueryRecorder\\QueryRecorder',
    ),
    'open_ai_key' => NULL,
    'with_stack_frame_arguments' => true,
    'argument_reducers' => 
    array (
      0 => 'Spatie\\Backtrace\\Arguments\\Reducers\\BaseTypeArgumentReducer',
      1 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ArrayArgumentReducer',
      2 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StdClassArgumentReducer',
      3 => 'Spatie\\Backtrace\\Arguments\\Reducers\\EnumArgumentReducer',
      4 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ClosureArgumentReducer',
      5 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeArgumentReducer',
      6 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeZoneArgumentReducer',
      7 => 'Spatie\\Backtrace\\Arguments\\Reducers\\SymphonyRequestArgumentReducer',
      8 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\ModelArgumentReducer',
      9 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\CollectionArgumentReducer',
      10 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StringableArgumentReducer',
    ),
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
    'trust_project' => 'always',
  ),
);
