<?php

return [

    /* =====================================================
       SITE SETTINGS
    ===================================================== */
    'site' => [
        'brand'         => 'Shanas',
        'currency'      => '₹',
        'support_email' => 'support@shanas.com',
    ],

    /* =====================================================
       HERO SECTION
       top      → tall landscape, fills top-right cell
       bottom_1 → square-ish, left of bottom row
       bottom_2 → square-ish, right of bottom row
    ===================================================== */
    'hero' => [
        'title'       => 'Luxury Gifts for Every Occasion',
        'subtitle'    => 'Curated premium hampers crafted with elegance & love.',
        'button_text' => 'Shop Now',
        'button_link' => '/products',

        'images' => [
            // Warm flat-lay of ribbon-tied gift boxes — fills tall slot cleanly
            'top'      => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=800&h=500&auto=format&fit=crop&q=80&crop=center',

            // Close-up pink gift with gold ribbon — compact square
            'bottom_1' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=400&h=300&auto=format&fit=crop&q=80&crop=center',

            // Luxury candle & gift flatlay — warm tones
            'bottom_2' => 'https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=400&h=300&auto=format&fit=crop&q=80&crop=center',
        ],
    ],

    /* =====================================================
       CATEGORIES
    ===================================================== */
    'categories' => [
        'all'       => '🎁 All',
        'birthday'  => '🎂 Birthday',
        'wedding'   => '💍 Wedding',
        'corporate' => '🏢 Corporate',
        'festive'   => '✨ Festive',
        'hampers'   => '🧺 Hampers',
        'candles'   => '🕯️ Candles',
        'jewellery' => '💎 Jewellery',
    ],

    /* =====================================================
       BENTO SECTION
       large  → portrait / tall (spans 2 grid rows)
       lg-2   → wide landscape
       sm     → compact square
       sm-2   → compact square
    ===================================================== */
    'bento' => [
        [
            // LARGE — dark luxury hamper flatlay, portrait crop
            'title' => 'Luxury Hampers',
            'image' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=700&h=900&auto=format&fit=crop&q=80&crop=center',
            'link'  => '/products?category=hampers',
        ],
        [
            // LG-2 — warm candle arrangement, wide crop
            'title' => 'Premium Candles',
            'image' => 'https://images.unsplash.com/photo-1603905856869-71f0f17f6dc5?w=700&h=320&auto=format&fit=crop&q=80&crop=center',
            'link'  => '/products?category=candles',
        ],
        [
            // SM — jewellery close-up, square
            'title' => 'Elegant Jewellery',
            'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=350&h=300&auto=format&fit=crop&q=80&crop=center',
            'link'  => '/products?category=jewellery',
        ],
        [
            // SM-2 — pink gift box closeup, square
            'title' => 'Exclusive Gifts',
            'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=350&h=300&auto=format&fit=crop&q=80&crop=center',
            'link'  => '/products',
        ],
    ],

    /* =====================================================
       PROMO SECTION
       card 0 → large left card  (1.3fr)
       card 1 → smaller right card (1fr)
       card 2 → second right card — render only if layout supports 3
    ===================================================== */
    'promo' => [
        [
            // Large left — rich festive red/gold tones
            'title' => 'Festive Collection',
            'desc'  => 'Celebrate every festival with premium gift boxes.',
            'image' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=800&h=500&auto=format&fit=crop&q=80&crop=entropy',
        ],
        [
            // Right — personalised kraft hamper, cosy tones
            'title' => 'Custom Hampers',
            'desc'  => 'Personalised gifting made luxurious.',
            'image' => 'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=500&h=500&auto=format&fit=crop&q=80&crop=center',
        ],
        [
            // Optional third — corporate desk gift
            'title' => 'Corporate Gifting',
            'desc'  => 'Premium bulk gifting solutions.',
            'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=500&h=500&auto=format&fit=crop&q=80&crop=center',
        ],
    ],

    /* =====================================================
       FEATURES / SERVICES
    ===================================================== */
    'features' => [
        [
            'icon'  => 'bi-truck',
            'title' => 'Free Shipping',
            'desc'  => 'On orders above ₹999',
        ],
        [
            'icon'  => 'bi-shield-check',
            'title' => 'Secure Payments',
            'desc'  => '100% safe & encrypted',
        ],
        [
            'icon'  => 'bi-gift',
            'title' => 'Premium Packaging',
            'desc'  => 'Luxury unboxing experience',
        ],
        [
            'icon'  => 'bi-arrow-repeat',
            'title' => 'Easy Returns',
            'desc'  => 'Hassle-free 7-day returns',
        ],
    ],

    /* =====================================================
       TESTIMONIALS
    ===================================================== */
    'testimonials' => [
        [
            'name'    => 'Anita Sharma',
            'comment' => 'Absolutely loved the luxury hamper! Packaging was stunning and delivery was super fast.',
            'rating'  => 5,
            'avatar'  => 'https://i.pravatar.cc/80?img=47',
        ],
        [
            'name'    => 'Rahul Mehta',
            'comment' => 'Best corporate gifting service we have ever used. The team was super helpful.',
            'rating'  => 4,
            'avatar'  => 'https://i.pravatar.cc/80?img=12',
        ],
        [
            'name'    => 'Priya Nair',
            'comment' => 'Gifted this for a wedding — everyone was blown away by the presentation!',
            'rating'  => 5,
            'avatar'  => 'https://i.pravatar.cc/80?img=32',
        ],
        [
            'name'    => 'Vikram Iyer',
            'comment' => 'Ordered a festive hamper last minute and it arrived beautifully packed on time.',
            'rating'  => 5,
            'avatar'  => 'https://i.pravatar.cc/80?img=8',
        ],
    ],

    /* =====================================================
       PRODUCTS
       Images: tight product shots, 600×600, crop=center
    ===================================================== */
    'items' => [

        [
            'id'           => 1,
            'slug'         => 'luxury-birthday-hamper',
            'name'         => 'Luxury Birthday Hamper',
            'category'     => 'birthday',
            'price'        => 2499,
            'old_price'    => 2999,
            'rating'       => 5,
            'review_count' => 24,
            'badge'        => 'sale',
            'filter'       => 'birthday',
            // Pastel balloons & gift box flatlay — birthday vibe
            'image'        => 'https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
        ],

        [
            'id'           => 2,
            'slug'         => 'premium-wedding-gift-box',
            'name'         => 'Premium Wedding Gift Box',
            'category'     => 'wedding',
            'price'        => 4599,
            'old_price'    => 5200,
            'rating'       => 5,
            'review_count' => 41,
            'badge'        => 'new',
            'filter'       => 'wedding',
            // White satin ribbon box — elegant wedding aesthetic
            'image'        => 'https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
        ],

        [
            'id'           => 3,
            'slug'         => 'corporate-deluxe-hamper',
            'name'         => 'Corporate Deluxe Hamper',
            'category'     => 'corporate',
            'price'        => 3299,
            'old_price'    => null,
            'rating'       => 4,
            'review_count' => 16,
            'badge'        => null,
            'filter'       => 'corporate',
            // Clean corporate gift set on white
            'image'        => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
        ],

        [
            'id'           => 4,
            'slug'         => 'festive-gourmet-box',
            'name'         => 'Festive Gourmet Box',
            'category'     => 'festive',
            'price'        => 1599,
            'old_price'    => null,
            'rating'       => 5,
            'review_count' => 32,
            'badge'        => null,
            'filter'       => 'festive',
            // Rich gold & dark festive hamper
            'image'        => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
        ],

        [
            'id'           => 5,
            'slug'         => 'luxury-candle-gift-set',
            'name'         => 'Luxury Candle Gift Set',
            'category'     => 'candles',
            'price'        => 1299,
            'old_price'    => 1599,
            'rating'       => 5,
            'review_count' => 19,
            'badge'        => 'sale',
            'filter'       => 'candles',
            'image'        => 'https://images.unsplash.com/photo-1737091862932-35b489b8fe8d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8bHV4dXJ5JTIwY2FuZGxlJTIwc2V0fGVufDB8fDB8fHww',
        ],

        [
            'id'           => 6,
            'slug'         => 'personalised-jewellery-box',
            'name'         => 'Personalised Jewellery Box',
            'category'     => 'jewellery',
            'price'        => 3799,
            'old_price'    => null,
            'rating'       => 4,
            'review_count' => 11,
            'badge'        => 'new',
            'filter'       => 'jewellery',
            // Gold jewellery on white velvet — product close-up
            'image'        => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
        ],

        [
            'id'           => 7,
            'slug'         => 'wedding-trousseau-hamper',
            'name'         => 'Wedding Trousseau Hamper',
            'category'     => 'wedding',
            'price'        => 6499,
            'old_price'    => 7500,
            'rating'       => 5,
            'review_count' => 28,
            'badge'        => 'sale',
            'filter'       => 'wedding',
            // Pink ribbon boxes stacked — bridal hamper feel
            'image'        => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
        ],

        [
            'id'           => 8,
            'slug'         => 'premium-chocolate-hamper',
            'name'         => 'Premium Chocolate Hamper',
            'category'     => 'birthday',
            'price'        => 1899,
            'old_price'    => 2200,
            'rating'       => 5,
            'review_count' => 47,
            'badge'        => null,
            'filter'       => 'birthday',
            // Dark chocolate gift box — indulgent close-up
            'image'        => 'https://images.unsplash.com/photo-1548907040-4baa42d10919?w=600&h=600&auto=format&fit=crop&q=80&crop=center',
        ],

    ],

];