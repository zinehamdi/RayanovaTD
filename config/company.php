<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Official Corporate Legal Information (RNE & Fiscal Identification)
    |--------------------------------------------------------------------------
    | Extracted from official Tunisian National Business Registry (RNE)
    | and General Directorate of Taxes (Direction Générale des Impôts).
    */

    'legal_name' => [
        'en' => 'Rayanova Trading & Distribution',
        'fr' => 'Rayanova Trading & Distribution',
        'ar' => 'شركة ريانوفا للتجارة والتوزيع',
    ],

    'brand_name' => 'RAYANOVA T.D',
    'legal_form' => 'SARL',
    'legal_form_ar' => 'شركة ذات المسؤولية المحدودة',

    // Fiscal & Registration Numbers
    'tax_number' => '1975095/B/A/M/000',           // Matricule Fiscal Complet (MF: 1975095 B A M 000)
    'tax_id' => '1975095B',                        // Identifiant Unique
    'code_tva' => 'A',                             // Assujetti à la TVA
    'code_category' => 'M',                        // Personne Morale
    'secondary_code' => '000',                     // Siège Principal
    'tax_office' => 'Guichet Unique Commerce (Tunis)',
    'tax_regime' => 'Régime réel - Assujetti obligatoire pour toute l\'activité',

    // RNE (Registre National des Entreprises)
    'rne_id' => '1975095B',                        // Identifiant Unique RNE
    'rne_extract_number' => 'ER20260645525',       // عدد المضمون
    'rne_verification_code' => 'Y7XYX27QV3UUE0',   // رقم التثبت من السجل
    'rne_management_number' => 'B50148372026',     // عدد التصرف الداخلي
    'rne_publication_date' => '2026-03-31',
    'rne_verification_url' => 'https://registre-entreprises.tn/rne-public/#/qr-code/validation',

    // Activity & Capital
    'capital' => '20 000 TND',
    'activity_code' => '51700',
    'activity_label_fr' => 'Autres commerces de gros (Vente en gros de produits divers / Commerce non spécialisé)',
    'activity_label_ar' => 'أصناف أخرى من تجارة الجملة - تجارة بالجملة لمواد مختلفة',
    'activity_start_date' => '2026-03-23',

    // Official Registered Address
    'address' => [
        'street' => 'Rue Omar Kaddah, Imm. Montplaisir, 2ème étage B25, Av. Khaireddine Pacha',
        'district' => 'Montplaisir, Bab El Bhar',
        'postal_code' => '1073',
        'city' => 'Tunis',
        'country' => 'Tunisia',
        'country_code' => 'TN',
        'full_fr' => 'Rue Omar Kaddah, Imm. Montplaisir, 2ème étage B25, Av. Khaireddine Pacha, Montplaisir, 1073 Bab El Bhar, Tunis, Tunisie',
        'full_ar' => 'نهج عمر قداح، عمارة مونبليزير، الطابق الثاني، ب25، شارع خيرالدين باشا، مونبليزير، 1073 باب بحر، تونس',
    ],

    // Contact
    'email' => 'export@rayanovatd.com',
    'phone' => '+216 71 890 120',
    'website' => 'https://rayanovatd.com',

    // Official Seal & QR Assets
    'qr_code_path' => 'images/brand/rne-verification-qr.png',
    '2ddoc_path' => 'images/brand/rne-2ddoc-clean.png',
];
