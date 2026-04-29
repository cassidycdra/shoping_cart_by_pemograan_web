<?php


$products = [
    1 => [
        'id'          => 1,
        'name'        => 'Elden Ring',
        'price'       => 399000,
        'stock'       => 15,
        'genre'       => 'Action RPG',
        'rating'      => 9.8,
        'description' => 'Open-world action RPG dari FromSoftware & George R.R. Martin.',
        'emoji'       => '⚔️',
        'badge'       => 'BESTSELLER',
    ],
    2 => [
        'id'          => 2,
        'name'        => 'Cyberpunk 2077',
        'price'       => 329000,
        'stock'       => 20,
        'genre'       => 'RPG',
        'rating'      => 9.1,
        'description' => 'RPG dunia terbuka futuristik di Night City yang dystopian.',
        'emoji'       => '🤖',
        'badge'       => 'HOT',
    ],
    3 => [
        'id'          => 3,
        'name'        => 'Stardew Valley',
        'price'       => 99000,
        'stock'       => 50,
        'genre'       => 'Simulation',
        'rating'      => 9.5,
        'description' => 'Bangun farm impianmu, berteman dengan warga kota, dan temukan cinta.',
        'emoji'       => '🌾',
        'badge'       => 'INDIE GEM',
    ],
    4 => [
        'id'          => 4,
        'name'        => 'The Witcher 3',
        'price'       => 249000,
        'stock'       => 12,
        'genre'       => 'Action RPG',
        'rating'      => 9.7,
        'description' => 'Perjalanan Geralt di dunia dark fantasy yang epik.',
        'emoji'       => '🐺',
        'badge'       => 'CLASSIC',
    ],
    5 => [
        'id'          => 5,
        'name'        => 'Hollow Knight',
        'price'       => 79000,
        'stock'       => 30,
        'genre'       => 'Metroidvania',
        'rating'      => 9.3,
        'description' => 'Jelajahi kerajaan serangga bawah tanah yang gelap dan misterius.',
        'emoji'       => '🦋',
        'badge'       => 'INDIE GEM',
    ],
    6 => [
        'id'          => 6,
        'name'        => "Baldur's Gate 3",
        'price'       => 429000,
        'stock'       => 8,
        'genre'       => 'RPG',
        'rating'      => 9.9,
        'description' => 'RPG terbaik dekade ini dengan kebebasan pilihan yang luar biasa.',
        'emoji'       => '🎲',
        'badge'       => 'GOTY',
    ],
    7 => [
        'id'          => 7,
        'name'        => 'Hades',
        'price'       => 149000,
        'stock'       => 25,
        'genre'       => 'Roguelike',
        'rating'      => 9.4,
        'description' => 'Kabur dari dunia bawah dengan senjata dan kekuatan para dewa.',
        'emoji'       => '🔱',
        'badge'       => 'HOT',
    ],
    8 => [
        'id'          => 8,
        'name'        => 'Palworld',
        'price'       => 189000,
        'stock'       => 0,
        'genre'       => 'Adventure',
        'rating'      => 8.5,
        'description' => 'Survival adventure dengan teman-teman Pal yang bisa bertempur.',
        'emoji'       => '🐉',
        'badge'       => 'NEW',
    ],
    9 => [
        'id'          => 9,
        'name'        => 'Portal 2',
        'price'       => 119000,
        'stock'       => 40,
        'genre'       => 'Puzzle',
        'rating'      => 9.6,
        'description' => 'Puzzle FPS berbasis portal yang ikonik dari Valve.',
        'emoji'       => '🌀',
        'badge'       => 'CLASSIC',
    ],
    10 => [
        'id'          => 10,
        'name'        => 'Celeste',
        'price'       => 89000,
        'stock'       => 35,
        'genre'       => 'Platformer',
        'rating'      => 9.4,
        'description' => 'Platformer challenging penuh makna tentang mengatasi diri sendiri.',
        'emoji'       => '🏔️',
        'badge'       => 'INDIE GEM',
    ],
];

function getProduct($id) {
    global $products;
    return $products[(int)$id] ?? null;
}

function getAllProducts() {
    global $products;
    return $products;
}
?>
