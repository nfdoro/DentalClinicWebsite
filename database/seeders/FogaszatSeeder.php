<?php

namespace Database\Seeders;

use App\Models\ArlistaTetel;
use App\Models\Galeria;
use App\Models\Kategoria;
use Illuminate\Database\Seeder;

class FogaszatSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriak = [
            [
                'id' => 1,
                'nev' => 'Gyökérkezelés',
                'slug' => 'gyokerkezeles',
                'icon' => 'images/root-canal.png',
                'szolgaltatas' => true,
                'leiras' => '<p>A gyökérkezelés egy igen fontos eljárás, amit azon fogak megmentésére használunk, amelyeknek a gyökere csúnyán befertőződött. A fogbél a fog élő része, ami a fog belsejéből nyúlik le a gyökerek felé, ebben találhatóak idegek és szövetek. Ennek befertőződése fájdalommal, amennyiben nem kezelődik, akár a teljes fog elvesztésével jár.<br><br>Abban az esetben hogyha a szuvasodás miatt a baktériumok bekerültek a fog belsejében rejlő fogbélbe, vagy a fogbél kezd begyulladni, az egyetlen lehetőség a fog megmentésére a gyökérkezelés.<br><br>A gyökérkezelés első lépése a helyi érzéstelenítés. Amint elzsibbadt a fog gyökere, a szuvas része eltávolítódik, ezt követi a gyökércsatornák kitisztítása. Ezután elzárják és letömik a gyökércsatornát. A gyökértömött fogat gyökércsappal erősítik meg, és a fog hátramaradó organikus részét később tanácsos lefedni egy koronával vagy héjjal, így visszaáll a természetes kinézet és funkció.</p>',
            ],
            [
                'id' => 2,
                'nev' => 'Fogszabályozás',
                'slug' => 'fogszabalyozas',
                'icon' => 'images/tooth.png',
                'szolgaltatas' => true,
                'leiras' => '<p>A fogszabályozást sokan tekintik esztétikai kérdésnek, és kétségtelen tény, hogy legtöbben azért fordulnak fogszabályozó szakorvoshoz, mert szebb mosolyt szeretnének. A fogszabályozás azonban az esztétikai hatáson túl jelentősen hozzájárul fogai egészségének a megőrzéséhez is.<br><br>A fogszabályozás lehetővé teszi a saját foganyag megőrzését, és végleges megoldást nyújt a szabálytalanul álló fogak problémájának megoldásában. Fogszabályozóra akkor van szüksége, ha fogai nem állnak szabályosan, és ezen változtatni kíván, mert szeretne fesztelenül mosolyogni, vagy szeretné megkímélni magát a jövőbeni egészségügyi problémáktól.</p>',
            ],
            [
                'id' => 3,
                'nev' => 'Fogtömés',
                'slug' => 'fogtomes',
                'icon' => 'images/tooth-filling.png',
                'szolgaltatas' => true,
                'leiras' => '<p>A tömés a fogszuvasodás esetén alkalmazott kezelés, amely már rutinszerű a fogászati beavatkozások között. A fogat először kitisztítjuk, majd a kialakult hiányt töméssel pótoljuk, hogy helyreállítsuk a fog szerkezetét.</p>',
            ],
            [
                'id' => 4,
                'nev' => 'Fogpótlás',
                'slug' => 'fogpotlas',
                'icon' => 'images/reconstruction.png',
                'szolgaltatas' => true,
                'leiras' => '<p>A fogpótlás magába foglalja mindazokat a kezeléseket, amelyek a letört, tönkrement fogak megmentésére szolgálnak, vagy teljes foghiányt pótolnak.<br><br>A fogpótlást mindig az íny irányából kezdjük vizsgálni: ha netán hézag lenne a pótlás és az íny között, netán kilátszana alatta már a saját fog, olyankor mindenképp szükséges mérlegelni új fogpótlás elkészítését, hiszen ellenkező esetben sajnos a pillér fogak idővel sérülnek, fájnak, begyulladnak!</p>',
            ],
            [
                'id' => 5,
                'nev' => 'Prevenció',
                'slug' => 'prevencio',
                'icon' => 'images/bridge.png',
                'szolgaltatas' => true,
                'leiras' => '<p>Az általános fogászat éppen annyira szól a gyógyításról, mint a megelőzésről. Mindenki számára fontos évente kétszer rutinszerű vizsgálat és professzionális tisztítás. Az általános fogászati vizsgálat során professzionális egészségügyi ellátásban, felvilágosításban valamint jó tanácsokban részesül a páciens.<br><br>Amennyiben több mint fél év telt el az utolsó vizsgálat óta, akkor önnek minden bizonnyal szüksége van egy általános fogászati vizsgálatra. A mindennapi fogmosás, fogselyem, szájvíz használata mellett is, a fogakon felhalmozódik a fogkő, ez pedig egy baktériumforrás, ami fogínygyulladáshoz, csontpusztuláshoz illetve fogszuvasodáshoz vezethet.</p>',
            ],
            [
                'id' => 6,
                'nev' => 'Foghúzás',
                'slug' => 'foghuzas',
                'icon' => 'images/dental-surgery.png',
                'szolgaltatas' => true,
                'leiras' => '<p>A fogászati kezelések fejlődésével a foghúzások száma lecsökkent, hiszen ha egy mód van rá, igyekszünk megmenteni a fogat. Vannak esetek, amikor különböző okok miatt mi is a foghúzást kell végrehajtsunk. Ilyenkor célunk a gyors, fájdalommentes beavatkozás, atraumatikus technikák előnybe részesítése által.<br><br>A foghúzás egy alapvető fogászati művelet, amely a menthetetlen fog eltávolítását foglalja magában. Attól függően, hogy melyik fogat húzták ki, helyettesíteni lehet implantátummal vagy egy más fogpótlási technikával.</p>',
            ],
            [
                'id' => 7,
                'nev' => 'Fogfehérítés',
                'slug' => 'fogfeherites',
                'icon' => null,
                'szolgaltatas' => false,
                'leiras' => '<p>Az esztétikai fogászatban, manapság egyre gyakrabban alkalmazott eljárás a fogfehérítés. Mivel a sokak által fogyasztott nikotin, kávé és vörös bor elszínezi a fogakat, a fogfehérítés napjainkra igen népszerű lett.<br><br>A fogfehérítés két alaptípusa a fogászati rendelőben végzett gyorsfehérítés és az otthoni fogfehérítés. A gyorsfehérítés a fogorvos rendelőjében történik, ahol speciális koncentrált fehérítő gélt alkalmaznak. Az otthoni fogfehérítést az orvos utasításainak megfelelően, otthonában kivitelezhető.</p>',
            ],
        ];

        foreach ($kategoriak as $kat) {
            Kategoria::create($kat);
        }

        $adatok = [
            // Foghúzás (id=6)
            [6, 'Tejfog gyökerek függvényében', '10.000-15.000 Ft'],
            [6, 'Mozgó és lógó fog gyökerek függvényében', '15.000-17.000 Ft'],
            [6, '1 gyökerű fog', '17.000 Ft'],
            [6, 'Több gyökerű fog, betört vagy letört fog esetén', '22.000-25.000 Ft'],
            [6, 'Bölcsesség fog', '30.000 Ft'],
            [6, 'Gyökerek szétválasztása - műtéti fogeltávolítás', '30.000 Ft'],
            [6, 'Gerincformázó műtét - foganként vagy foghelyenként', '20.000 Ft'],
            [6, 'Sinus zárás - Tartalmazza a suturát (varratot)', '20.000 Ft'],
            [6, 'Incízió', '15.000 Ft'],
            [6, 'Ultrahangos depurálás (fogkőeltávolítás) - alsó állcsont', '15.000 Ft'],
            [6, 'Ultrahangos depurálás (fogkőeltávolítás) - alsó és felső állcsont', '25.000 Ft'],
            [6, 'Sutura (varrat) - magában foglalja a varratszedést is', '6.000 Ft'],
            [6, 'Sürgősségi illetve hétvégi felár', '3.000 Ft'],
            // Fogtömés (id=3)
            [3, 'Esztétikus tömés - érintett felszín függvényében', '28.000 – 32.000-35.000 Ft'],
            [3, 'Gyógyszeres, alábélelő tömés - kalciumos', '2.000 Ft'],
            [3, 'Gyógyszeres, alábélelő tömés - fluoros', '4.000 Ft'],
            [3, 'Fognyaki tömés', '22.000 Ft'],
            [3, 'Cement tömés', '20.000 Ft'],
            [3, 'Tejfog tömés', '12.000-15.000 Ft'],
            [3, 'Barázdazárás', '12.000 Ft'],
            [3, 'Ideiglenes tömés - Magában foglalja az alábélelőt', '10.000 Ft'],
            // Gyökérkezelés (id=1)
            [1, '1 csatornás fog (2-4 alkalom)', '40.000 Ft'],
            [1, '2 csatornás fog (2-4 alkalom)', '55.000 Ft'],
            [1, '3-4 csatornás fog (2-4 alkalom)', '75.000-80.000 Ft'],
            [1, 'Üvegszálas megerősítés - fogcsatorna függvényében', '20.000-25.000-30.000 Ft'],
            [1, 'Belső fogfehérítés - kb. 2-3 alkalom szükséges', '10.000 Ft / alkalom'],
            [1, 'Fémcsap - 1 csatorna / 2 csatorna', '35.000-40.000 Ft'],
            [1, 'Sürgősségi illetve hétvégi felár', '3.000 Ft'],
            // Fogpótlás (id=4)
            [4, 'Shofu porcelán korona', '52.000 Ft'],
            [4, 'VM13 porcelán korona (nikkelmentes fém) (prémium)', '59.000 Ft'],
            [4, 'Ideiglenes korona - PMMA', '18.000 Ft'],
            [4, 'Visszaragasztás – nem itt készült korona esetén', '8.000 Ft / db + 2.000/db tisztítás'],
            [4, 'Híd visszaragasztása - 4 fog fölött + 2000 Ft tisztítás/tag', '32.000 Ft'],
            [4, 'Cirkon korona', '82.000 Ft'],
            [4, 'Full cirkon korona', '82.000 Ft'],
            [4, 'Koronahíd levágás', '8.000 Ft / pillérfog'],
            [4, 'Fogsor – akrilát', '190.000 Ft'],
            [4, 'Fogsor – kompozit', '220.000 Ft'],
            [4, 'Fémlemez', '80.000 Ft'],
            [4, 'Fogbetét – kompozit - kemény, fogszínű ragasztó', '60.000 Ft'],
            [4, 'Csúsztató Preci', '70.000 Ft'],
            [4, 'Frézelt váll', '10.000 Ft'],
            [4, 'Interlock', '10.000 Ft'],
            [4, 'OT CAP + fémcsap ára', '65.000 + 35.000 csap Ft'],
            [4, 'Klipsz', '35.000 Ft'],
            [4, 'Fogsor törés javítás', '30.000 Ft'],
            [4, 'Fogbepótlás (+5000 ha nem nálunk készült)', '25.000 Ft'],
            [4, 'Fogsor alábélelés', '35.000 Ft'],
            [4, 'Sürgősségi illetve hétvégi felár - Állcsontonként', '40.000 Ft'],
            // Fogszabályozás (id=2)
            [2, 'Konzultáció', '10.000 Ft'],
            [2, 'Konzultáció + lenyomat + rtg-ek elemzése, mérések, kezelési terv megbeszélés', '20.000 Ft'],
            [2, 'Kivehető fogszabályzó trainer - Csere fél éven belül - 20.000', '70.000 Ft'],
            [2, 'Kivehető fogszabályzók típustól, fogívektől függ', '70.000-120.000 Ft'],
            [2, 'Rögzített fogszabályzó fém hagyományos állcsontonként', '180.000 Ft'],
            [2, 'Rögzített fogszabályzó esztétikus hagyományos állcsontonként', '280.000-400.000 Ft'],
            [2, 'Rögzített önligírozó fém fogszabályzó állcsontonként - American Ortho/Foresta', '230.000-280.000 Ft'],
            [2, 'Leesett bracket visszaragasztása (ha nem sérült és megvan a bracket)', '10.000 Ft'],
            [2, 'Új bracket (törés, sérülés esetén) ragasztása - Típustól függően', '15.000-40.000 Ft'],
            [2, 'Kivehető fogszabályzó aktiválása', '8.000 Ft'],
            [2, 'Rögzített fogszabályzó aktiválása fogívenként - intermaxilláris gumi ha szükséges (+3000 Ft)', '8.000 Ft'],
            [2, 'Fogszabályzó ív niti', '3.000 Ft'],
            [2, 'Fogszabályzó ív acél', '5.000 Ft'],
            [2, 'Retainer', '50.000 Ft'],
            [2, 'Retenciós sín', '45.000 Ft'],
            [2, 'Fogszabályzó eltávolítása fogívenként - Esztétikus eltáv+5000 ill.5000 harapásemelő eltáv', '12.500 Ft'],
            [2, 'Hyrax készülék', '110.000 Ft'],
            // Prevenció (id=5)
            [5, 'Kontroll', '3.000 Ft / max. 10 perc'],
            // Fogfehérítés (id=7)
            [7, 'Rendelői vagy otthoni, az otthoni tartalmazza a sínt is', '45.000 Ft / állcsont'],
        ];

        foreach ($adatok as [$katId, $muveletnev, $ar]) {
            ArlistaTetel::create([
                'kategoria_id' => $katId,
                'muveletnev' => $muveletnev,
                'ar' => $ar,
            ]);
        }

        $galeria = [
            [1, 'images/gyokerkez1.jpg', 'Röntgenfelvétel egy gyökérkezelt fog előtt'],
            [1, 'images/gyokerkez2.jpg', 'Röntgenfelvétel egy gyökérkezelt fog után'],
            [1, 'images/gyokerkez3.jpg', 'Animációs kép a gyökérkezelt fogról'],
            [1, 'images/gyokerkez4.jpg', 'Animációs kép a gyökérkezelés folyamatáról'],
            [3, 'images/tomes1_elotte.jpg', 'Egy páciensünk tömés előtti képe'],
            [3, 'images/tomes1_utana.jpg', 'Egy páciensünk tömés utáni képe'],
            [4, 'images/fogpotlas1.jpg', 'Egy páciensünk pótlás előtti és utáni képe'],
            [4, 'images/fogpotlas2.jpg', 'Fogpótlások'],
            [4, 'images/hid1_elotte.jpg', 'Egy páciensünk híd felhelyezése előtti képe'],
            [4, 'images/hid1_utana.jpg', 'Egy páciensünk híd felhelyezése utáni képe'],
            [4, 'images/hid2_elotte.jpg', 'Egy páciensünk híd felhelyezése előtti képe'],
            [4, 'images/hid2_utana.jpg', 'Egy páciensünk híd felhelyezése utáni képe'],
            [4, 'images/hid3_elotte.jpg', 'Egy páciensünk híd felhelyezése előtti képe'],
            [4, 'images/hid3_utana.jpg', 'Egy páciensünk híd felhelyezése utáni képe'],
            [4, 'images/csap1.jpg', 'Kép egy páciensünk csapjáról'],
            [7, 'images/fogfeherites1.jpg', 'Egy páciensünk fogfehérítés előtti és utáni képe'],
            [7, 'images/fogfeherites2.jpg', 'Egy páciensünk fogfehérítés előtti és utáni képe'],
            [7, 'images/fogfeherites3.jpg', 'Egy páciensünk fogfehérítés előtti és utáni képe'],
            [2, 'images/fogszabi1.jpg', 'Fogszabályozó kinézete'],
            [2, 'images/fogszabi2.jpg', '"Átlátszó" brekettes fogszabályzó'],
            [2, 'images/fogszabi3.jpg', 'Fogszabályzási konzultáció'],
            [2, 'images/fogszabi4.jpg', 'Retaineres fogszabályzás'],
            [2, 'images/fogszabi5.jpg', 'Fogszabályozó előtti és használata közbeni kép'],
        ];

        foreach ($galeria as [$katId, $fajlnev, $leiras]) {
            Galeria::create([
                'kategoria_id' => $katId,
                'fajlnev' => $fajlnev,
                'rovidleiras' => $leiras,
            ]);
        }
    }
}
