<?php

/**
 * Labels for the shared e-mail frame (app/Views/emails/header.php + footer.php).
 */
return [
    // Language code for the <html lang="..."> attribute. Kept in the language
    // file because mail views also render from CLI (cron), with no request.
    'HtmlLang'     => 'en',

    'Phone'        => 'Phone',
    'Email'        => 'E-mail',
    'Address'      => 'Address',
    'Nip'          => 'VAT ID',
    'OpeningHours' => 'Opening hours',
    'FollowUs'     => 'Find us on',
];
