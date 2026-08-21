<?php

/**
 * Etykiety wspolnej ramki maili (app/Views/emails/header.php + footer.php).
 * Wczesniej naglowek pozyczal klucze z modulu Users (Users.form.PhoneNumber),
 * przez co zmiana w tamtym module po cichu psula maile z formularzy.
 */
return [
    // Kod jezyka do atrybutu <html lang="...">. Trzymamy go w pliku jezykowym,
    // bo widok maila renderuje sie takze z CLI (cron), gdzie nie ma requestu.
    'HtmlLang'     => 'pl',

    'Phone'        => 'Telefon',
    'Email'        => 'E-mail',
    'Address'      => 'Adres',
    'Nip'          => 'NIP',
    'OpeningHours' => 'Godziny otwarcia',
    'FollowUs'     => 'Znajdziesz nas na',
];
