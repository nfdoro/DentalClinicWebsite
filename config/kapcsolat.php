<?php

/*
|--------------------------------------------------------------------------
| Kapcsolattartási adatok (egyetlen forrás)
|--------------------------------------------------------------------------
|
| A rendelő elérhetőségei egy helyen, hogy a telefonszám, e-mail és cím ne
| legyen több komponensbe bemásolva. A fő kapcsolattartási szám a rendelő
| mobilszáma (+36 70 627 6160) - ez szerepel a fejlécben, láblécben, a
| kapcsolat szekcióban és a schema.org adatokban is, ezért ezt használjuk a
| globális sticky hívógombhoz.
|
| - "telefon"      : ember által olvasható, formázott szám (szóközökkel)
| - "telefon_hivas": tel: linkhez, csak +, ország- és körzetszám, szóköz nélkül
|
*/

return [

    'telefon' => '+36 70 627 6160',
    'telefon_hivas' => '+36706276160',

    'email' => 'info@fogaszat-miskolc.hu',

    'cim' => [
        'iranyitoszam' => '3525',
        'varos' => 'Miskolc',
        'utca' => 'Madarász Viktor utca 13/A',
        'egyeb' => '2. emelet, 03 kapucsengő',
        'orszag' => 'Magyarország',
        // Egysoros, teljes cím a rövid megjelenítésekhez.
        'egysoros' => '3525 Miskolc, Madarász Viktor utca 13/A, 2. emelet',
    ],

];
