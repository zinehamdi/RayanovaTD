<?php

return [

    // Langues officielles supportées pour Rayanova T.D
    'supportedLocales' => [
        'ar' => ['name' => 'Arabic',   'script' => 'Arab', 'native' => 'العربية',  'regional' => 'ar_TN'],
        'en' => ['name' => 'English',  'script' => 'Latn', 'native' => 'English',  'regional' => 'en_US'],
        'fr' => ['name' => 'French',   'script' => 'Latn', 'native' => 'Français', 'regional' => 'fr_FR'],
    ],

    // Détection automatique de la langue du navigateur au premier chargement
    'useAcceptLanguageHeader' => true,

    // Préfixe de langue visible dans les URLs (ex: /ar/products, /en/products)
    'hideDefaultLocaleInURL' => false,

    // Ordre d'affichage dans le sélecteur
    'localesOrder' => ['ar', 'en', 'fr'],

    'localesMapping' => [],

    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    'urlsIgnored' => ['/skipped', '/api/*'],

    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],

];
