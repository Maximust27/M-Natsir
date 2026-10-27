<?php

return [
    'site_name' => 'M. Natsir Kongah',
    // Only identity claims already visible on the public About page belong here.
    'person' => [
        'description' => 'Pembelajar Anti Pencucian Uang & Pengamat Kejahatan Keuangan',
        'image' => 'images/about-hero.jpeg',
        'same_as' => [
            'https://id.linkedin.com/in/m-natsir-kongah-221615130',
            'https://kumparan.com/natsir-kongah',
        ],
        'knows_about' => [
            'Financial Intelligence',
            'Anti Pencucian Uang',
            'Professional Money Laundering',
            'Asset Recovery',
            'Digital Financial Crime',
        ],
    ],
    // Keep local, staging, and preview deployments out of search results.
    'allow_indexing' => (bool) env('SEO_ALLOW_INDEXING', false),
    'sitemap_routes' => ['home', 'about', 'articles', 'library', 'media', 'contact'],
    'default_title' => 'M. Natsir Kongah | Financial Intelligence Hub',
    'default_description' => 'Jelajahi profil, publikasi, dan referensi M. Natsir Kongah mengenai intelijen keuangan, anti pencucian uang, serta kejahatan finansial.',
    'default_image' => 'images/home-hero.jpeg',
    'default_image_alt' => 'Potret M. Natsir Kongah di ruang kerja',

    // Judul halaman tetap mengikuti atribut #[Title] pada komponen Livewire.
    // Kunci di bawah menggunakan nama route publik pada routes/web.php.
    'pages' => [
        'home' => [
            'description' => 'Jelajahi profil dan analisis M. Natsir Kongah tentang intelijen keuangan, anti pencucian uang, pemulihan aset, dan kejahatan finansial digital.',
        ],
        'about' => [
            'description' => 'Kenali profil M. Natsir Kongah, pengalaman profesional, kontribusi edukasi publik, dan fokus kajian dalam intelijen keuangan serta anti pencucian uang.',
            'image' => 'images/about-hero.jpeg',
            'image_alt' => 'Potret M. Natsir Kongah pada halaman profil',
        ],
        'articles' => [
            'description' => 'Kumpulan opini dan analisis M. Natsir Kongah tentang professional money laundering, asset recovery, intelijen keuangan, dan kejahatan finansial.',
        ],
        'library' => [
            'description' => 'Telusuri makalah, regulasi, standar, dan referensi pilihan mengenai intelijen keuangan, anti pencucian uang, dan pemulihan aset.',
        ],
        'media' => [
            'description' => 'Jelajahi wawancara, dialog publik, dan liputan media tentang kontribusi M. Natsir Kongah dalam isu pencucian uang dan kejahatan keuangan.',
        ],
        'contact' => [
            'description' => 'Hubungi M. Natsir Kongah untuk wawancara media, diskusi akademik, dan konsultasi terkait intelijen keuangan serta kebijakan anti pencucian uang.',
        ],
    ],
];
