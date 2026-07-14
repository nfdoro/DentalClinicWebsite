# Árlista CSV/XLSX export + import (admin)

## Cél
Az admin Filament felületen az Árlista tételek (`ArlistaTetelResource`) listájánál legyen lehetőség a tételek exportálására (CSV/XLSX) és CSV-ből történő importálására (új tételek létrehozása, meglévők frissítése).

## Alapok
- Az admin panel Filament 3.3-mal épül. A `filament/actions` csomag (már telepítve, `composer.lock` szerint) natívan tartalmazza az Import/Export rendszert, a hozzá tartozó `league/csv` és `openspout/openspout` függőségekkel együtt — **nincs szükség új composer csomagra**.
- Az `ArlistaTetel` modell mezői (`adatok` tábla): `id`, `kategoria_id` (FK → `kategoriak.id`), `muveletnev`, `ar` (stringként tárolva), `kiegeszites` (nullable).
- A `Kategoria` modell (`kategoriak` tábla) `nev` mezője adja a kategórianevet.

## Queue viselkedés
A Filament export/import jobjai (`ExportCsv`, `CreateXlsxFile`, `ImportCsv` stb.) mind `ShouldQueue`-t implementálnak, azaz alapból háttérben, queue workeren futnának le. Az éles szerveren nincs futó queue worker (nincs supervisor/cron erre a repóban, és a felhasználó megerősítette, hogy valószínűleg nincs ilyen beállítva). Az appban jelenleg semmilyen más kód nem használ queue-t (nincs `ShouldQueue` implementáció az `app/` mappában).

→ **Döntés:** a `.env` fájlban `QUEUE_CONNECTION=sync`-re állítjuk. Ez a Laravel beépített szinkron queue drivere: a jobok azonnal, a HTTP kérés során lefutnak, külön worker folyamat nélkül. Az árlista mérete (néhány tucat sor) miatt ez gyors és problémamentes.

## Új fájlok

### `app/Filament/Exports/ArlistaTetelExporter.php`
`Filament\Actions\Exports\Exporter` leszármazott, modell: `ArlistaTetel`. Oszlopok:
- `id`
- `kategoria.nev` — label: "Kategória"
- `muveletnev` — label: "Beavatkozás"
- `ar` — label: "Ár (Ft)"
- `kiegeszites` — label: "Kiegészítés"

Az `id` oszlop szerepeltetése azért fontos, hogy egy exportált CSV változtatás után (pl. árat módosítva) visszaimportálva a meglévő sort frissítse, ne duplikálja.

### `app/Filament/Imports/ArlistaTetelImporter.php`
`Filament\Actions\Imports\Importer` leszármazott, modell: `ArlistaTetel`. Oszlopok:
- `id` — opcionális, integer. Ha kitöltött és létező rekordra mutat, azt a Filament alap `resolveRecord()` logikája automatikusan megtalálja és frissíti; ha üres, új rekord jön létre.
- `kategoria` — kötelező szöveg oszlop, `relationship()` feloldással: a `kategoriak.nev` mező alapján keresi meg a `Kategoria` rekordot, és állítja be a `kategoria_id`-t. Ha nincs egyező nevű kategória, az adott sor hibaként jelenik meg az import összegzésében (nem hoz létre hibás/üres kategóriájú tételt).
- `muveletnev` — kötelező szöveg.
- `ar` — kötelező (szám vagy szöveg, a jelenlegi modellhez hasonlóan stringként tárolva).
- `kiegeszites` — opcionális szöveg.

## Bekötés a Resource-ba
Az `ArlistaTetelResource::table()` metódusban két header action a táblázat tetejére:
```php
->headerActions([
    Tables\Actions\ExportAction::make()->exporter(ArlistaTetelExporter::class),
    Tables\Actions\ImportAction::make()->importer(ArlistaTetelImporter::class),
])
```
(A pontos elhelyezést és metódusnevet — `headerActions` a táblán, vagy a `ListArlistaTetels` oldal `getHeaderActions()`-e — az implementáció során a Filament 3.3 tényleges API-ja szerint kell véglegesíteni; mindkettő a lista tetején jelenik meg a felhasználó számára.)

## Hibakezelés / validáció
Mindez a Filament beépített mechanizmusa, egyedi kódolás nélkül:
- Import után Filament összegző értesítést ad (hány sor sikeres/sikertelen), és a hibás sorokról letölthető jelentést készít.
- A `kategoria` oszlop validációja (kötelező, létező kategórianév) és a `muveletnev`/`ar` kötelező mezők validációja az `ImportColumn`-okon definiált szabályokkal történik.

## Tesztelés (kézi ellenőrzés)
1. Export gomb → CSV letöltés, tartalom (kategória, beavatkozás, ár, kiegészítés) egyezik az admin táblázat adataival.
2. A letöltött CSV egy sorában az ár módosítva, majd újra importálva (az `id` megmarad) → a meglévő tétel ára frissül, nem jön létre duplikátum.
3. Új sor hozzáadva a CSV-hez (üres `id`, érvényes, létező kategórianév) → import után új árlista tétel jön létre a megfelelő kategóriában.
4. Egy sorban érvénytelen/elgépelt kategórianév → az import összegzésben hibaként jelenik meg az adott sor, a többi sor változatlanul feldolgozódik, nincs alkalmazáshiba.

## Scope
Kis, jól körülhatárolt feladat: natív Filament funkció bekötése egy meglévő Resource-hoz, 2 új osztály + 1 `.env` változó módosítás. Nincs szükség további bontásra.
