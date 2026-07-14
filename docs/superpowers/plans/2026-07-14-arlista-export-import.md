# Árlista CSV/XLSX export + import Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add CSV/XLSX export and CSV import to the admin Árlista tétel (price list item) list in the Filament panel, using Filament's built-in Import/Export system (no new composer dependencies).

**Architecture:** Two new Filament framework classes (`ArlistaTetelExporter`, `ArlistaTetelImporter`) plug into Filament's existing `filament/actions` import/export engine. They are wired onto the `ListArlistaTetels` page as header actions (`ExportAction`, `ImportAction`), next to the existing `CreateAction`. Because there is no queue worker running on the production server, the queue connection is switched to `sync` so these (queued-by-design) jobs run inline during the request.

**Tech Stack:** Laravel 12, Filament 3.3 (`filament/actions` — already vendored, brings `league/csv` + `openspout/openspout`), PHPUnit + Livewire testing helpers, SQLite (dev/test).

**Spec:** `docs/superpowers/specs/2026-07-14-arlista-export-import-design.md`

## Global Constraints

- No new composer packages — `filament/actions` (already installed, v3.3.0) provides everything needed.
- Export columns: `id`, `kategoria.nev` ("Kategória"), `muveletnev` ("Beavatkozás"), `ar` ("Ár (Ft)"), `kiegeszites` ("Kiegészítés").
- Import columns: `id` (optional, matches existing row to update; blank creates a new row), `kategoria` (required, matched by exact `kategoriak.nev` text; unmatched name = failed row, not a crash), `muveletnev` (required), `ar` (required), `kiegeszites` (optional).
- `QUEUE_CONNECTION` must be `sync` (both local `.env` and the committed `.env.example`) — confirmed no other code in `app/` uses `ShouldQueue`, so this is safe app-wide.
- All new Feature tests use `Illuminate\Foundation\Testing\RefreshDatabase` explicitly — the project's base `Tests\TestCase` does not enable it globally.
- Run PHP via the project's Herd binary: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat"` (plain `php` is not on PATH in the shell used for this work).

---

### Task 1: Enable sync queue + migrate Filament's import/export tables

**Files:**
- Modify: `.env` (not committed — gitignored)
- Modify: `.env.example`

**Interfaces:**
- Produces: working `imports`, `exports`, `failed_import_rows` tables in the local SQLite DB, and a `sync` queue connection that later tasks' tests rely on to make queued import/export jobs run inline.

- [ ] **Step 1: Switch the queue connection to sync in both env files**

In `.env`, change:
```
QUEUE_CONNECTION=database
```
to:
```
QUEUE_CONNECTION=sync
```

In `.env.example`, make the same change (this file **is** committed, so future setups default to `sync` too):
```
QUEUE_CONNECTION=sync
```

- [ ] **Step 2: Run migrations to create Filament's import/export tables**

`filament/actions` registers its migrations (`create_imports_table`, `create_exports_table`, `create_failed_import_rows_table`) automatically via `Spatie\LaravelPackageTools` — no `vendor:publish` needed, just migrate:

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan migrate`
Expected: output includes `imports`, `exports`, and `failed_import_rows` among the newly run migrations, with no errors.

- [ ] **Step 3: Verify the tables exist**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan migrate:status`
Expected: rows for `..._create_imports_table`, `..._create_exports_table`, `..._create_failed_import_rows_table` all show `Ran`.

- [ ] **Step 4: Commit**

```bash
git add .env.example
git commit -m "chore: switch queue connection to sync for filament import/export"
```

> **Note for whoever deploys this:** the production `.env` is not in git and must be updated by hand — set `QUEUE_CONNECTION=sync` there too, and run `php artisan migrate` on the server, or export/import will not work live.

---

### Task 2: Add model factories for Kategoria and ArlistaTetel

**Files:**
- Modify: `app/Models/Kategoria.php`
- Modify: `app/Models/ArlistaTetel.php`
- Create: `database/factories/KategoriaFactory.php`
- Create: `database/factories/ArlistaTetelFactory.php`
- Test: `tests/Feature/ArlistaTetelFactoryTest.php`

**Interfaces:**
- Produces: `Kategoria::factory()` and `ArlistaTetel::factory()`, used by Task 3 and Task 4's tests.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/ArlistaTetelFactoryTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\ArlistaTetel;
use App\Models\Kategoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArlistaTetelFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_arlista_tetel_factory_creates_a_valid_record_with_a_kategoria(): void
    {
        $arlistaTetel = ArlistaTetel::factory()->create();

        $this->assertDatabaseCount('adatok', 1);
        $this->assertInstanceOf(Kategoria::class, $arlistaTetel->kategoria);
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan test --filter=ArlistaTetelFactoryTest`
Expected: FAIL — `Call to undefined method App\Models\ArlistaTetel::factory()`.

- [ ] **Step 3: Add `HasFactory` to both models**

In `app/Models/Kategoria.php`, add the trait:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategoria extends Model
{
    use HasFactory;

    protected $table = 'kategoriak';

    protected $fillable = ['nev', 'leiras', 'kiemelt_leiras', 'icon', 'szolgaltatas', 'slug'];

    protected $casts = [
        'szolgaltatas' => 'boolean',
    ];

    public function arlistaTetelei(): HasMany
    {
        return $this->hasMany(ArlistaTetel::class, 'kategoria_id');
    }

    public function galeria(): HasMany
    {
        return $this->hasMany(Galeria::class, 'kategoria_id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy('sorrend');
    }
}
```

In `app/Models/ArlistaTetel.php`, add the trait:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArlistaTetel extends Model
{
    use HasFactory;

    protected $table = 'adatok';

    protected $fillable = ['kategoria_id', 'muveletnev', 'ar', 'kiegeszites'];

    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria_id');
    }
}
```

- [ ] **Step 4: Create the factories**

Create `database/factories/KategoriaFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategoria>
 */
class KategoriaFactory extends Factory
{
    public function definition(): array
    {
        $nev = fake()->unique()->words(2, true);

        return [
            'nev' => $nev,
            'leiras' => fake()->sentence(),
            'icon' => 'heroicon-o-star',
            'szolgaltatas' => true,
            'slug' => (string) str($nev)->slug(),
        ];
    }
}
```

Create `database/factories/ArlistaTetelFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArlistaTetel>
 */
class ArlistaTetelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategoria_id' => KategoriaFactory::new(),
            'muveletnev' => fake()->words(3, true),
            'ar' => (string) fake()->numberBetween(5000, 100000),
            'kiegeszites' => null,
        ];
    }
}
```

- [ ] **Step 5: Run the test to verify it passes**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan test --filter=ArlistaTetelFactoryTest`
Expected: PASS (1 test, 2 assertions).

- [ ] **Step 6: Commit**

```bash
git add app/Models/Kategoria.php app/Models/ArlistaTetel.php database/factories/KategoriaFactory.php database/factories/ArlistaTetelFactory.php tests/Feature/ArlistaTetelFactoryTest.php
git commit -m "test: add Kategoria and ArlistaTetel model factories"
```

---

### Task 3: Export action (Exporter class + wiring + test)

**Files:**
- Create: `app/Filament/Exports/ArlistaTetelExporter.php`
- Modify: `app/Filament/Resources/ArlistaTetelResource/Pages/ListArlistaTetels.php`
- Test: `tests/Feature/Filament/ArlistaTetelExportTest.php`

**Interfaces:**
- Consumes: `ArlistaTetel::factory()` and `Kategoria::factory()` from Task 2.
- Produces: `App\Filament\Exports\ArlistaTetelExporter` (extends `Filament\Actions\Exports\Exporter`), registered as the `export` header action on `ListArlistaTetels`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Filament/ArlistaTetelExportTest.php`:

```php
<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ArlistaTetelResource\Pages\ListArlistaTetels;
use App\Models\ArlistaTetel;
use App\Models\User;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArlistaTetelExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_exports_all_arlista_tetel_records(): void
    {
        $this->actingAs(User::factory()->create());

        ArlistaTetel::factory()->count(3)->create();

        Livewire::test(ListArlistaTetels::class)
            ->callAction('export')
            ->assertHasNoActionErrors();

        $export = Export::query()->latest('id')->first();

        $this->assertNotNull($export);
        $this->assertSame(3, $export->successful_rows);
        $this->assertSame(0, $export->getFailedRowsCount());
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan test --filter=ArlistaTetelExportTest`
Expected: FAIL — the `export` action does not exist on `ListArlistaTetels` yet (`assertActionExists`-style failure from `callAction`, e.g. "Action [export] does not exist on ...").

- [ ] **Step 3: Create the Exporter class**

Create `app/Filament/Exports/ArlistaTetelExporter.php`:

```php
<?php

namespace App\Filament\Exports;

use App\Models\ArlistaTetel;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ArlistaTetelExporter extends Exporter
{
    protected static ?string $model = ArlistaTetel::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('kategoria.nev')
                ->label('Kategória'),
            ExportColumn::make('muveletnev')
                ->label('Beavatkozás'),
            ExportColumn::make('ar')
                ->label('Ár (Ft)'),
            ExportColumn::make('kiegeszites')
                ->label('Kiegészítés'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Az árlista exportálása befejeződött, ' . number_format($export->successful_rows) . ' ' . str('sor')->plural($export->successful_rows) . ' exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('sor')->plural($failedRowsCount) . ' exportálása sikertelen.';
        }

        return $body;
    }
}
```

- [ ] **Step 4: Wire the ExportAction into the list page**

Replace the contents of `app/Filament/Resources/ArlistaTetelResource/Pages/ListArlistaTetels.php`:

```php
<?php

namespace App\Filament\Resources\ArlistaTetelResource\Pages;

use App\Filament\Exports\ArlistaTetelExporter;
use App\Filament\Resources\ArlistaTetelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArlistaTetels extends ListRecords
{
    protected static string $resource = ArlistaTetelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(ArlistaTetelExporter::class),
        ];
    }
}
```

- [ ] **Step 5: Run the test to verify it passes**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan test --filter=ArlistaTetelExportTest`
Expected: PASS (1 test, 3 assertions).

- [ ] **Step 6: Commit**

```bash
git add app/Filament/Exports/ArlistaTetelExporter.php app/Filament/Resources/ArlistaTetelResource/Pages/ListArlistaTetels.php tests/Feature/Filament/ArlistaTetelExportTest.php
git commit -m "feat: add árlista CSV/XLSX export action"
```

---

### Task 4: Import action (Importer class + wiring + tests)

**Files:**
- Create: `app/Filament/Imports/ArlistaTetelImporter.php`
- Modify: `app/Filament/Resources/ArlistaTetelResource/Pages/ListArlistaTetels.php`
- Test: `tests/Feature/Filament/ArlistaTetelImportTest.php`

**Interfaces:**
- Consumes: `ArlistaTetel::factory()`, `Kategoria::factory()` from Task 2; the `getHeaderActions()` array shape established in Task 3.
- Produces: `App\Filament\Imports\ArlistaTetelImporter` (extends `Filament\Actions\Imports\Importer`), registered as the `import` header action on `ListArlistaTetels`.

- [ ] **Step 1: Write the failing tests**

Create `tests/Feature/Filament/ArlistaTetelImportTest.php`:

```php
<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ArlistaTetelResource\Pages\ListArlistaTetels;
use App\Models\ArlistaTetel;
use App\Models\Kategoria;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class ArlistaTetelImportTest extends TestCase
{
    use RefreshDatabase;

    private const COLUMN_MAP = [
        'id' => 'id',
        'kategoria' => 'kategoria',
        'muveletnev' => 'muveletnev',
        'ar' => 'ar',
        'kiegeszites' => 'kiegeszites',
    ];

    public function test_it_creates_new_records_for_rows_without_an_id(): void
    {
        $this->actingAs(User::factory()->create());
        $kategoria = Kategoria::factory()->create(['nev' => 'Fogszabályozás']);

        $csv = "id,kategoria,muveletnev,ar,kiegeszites\n"
            . ",Fogszabályozás,Konzultáció,15000,első alkalom\n";

        Livewire::test(ListArlistaTetels::class)
            ->callAction('import', data: [
                'file' => UploadedFile::fake()->createWithContent('arlista.csv', $csv),
                'columnMap' => self::COLUMN_MAP,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('adatok', [
            'kategoria_id' => $kategoria->id,
            'muveletnev' => 'Konzultáció',
            'ar' => '15000',
            'kiegeszites' => 'első alkalom',
        ]);
    }

    public function test_it_updates_existing_records_by_id(): void
    {
        $this->actingAs(User::factory()->create());
        $kategoria = Kategoria::factory()->create(['nev' => 'Fogszabályozás']);
        $tetel = ArlistaTetel::factory()->create([
            'kategoria_id' => $kategoria->id,
            'muveletnev' => 'Régi név',
            'ar' => '10000',
        ]);

        $csv = "id,kategoria,muveletnev,ar,kiegeszites\n"
            . "{$tetel->id},Fogszabályozás,Régi név,20000,\n";

        Livewire::test(ListArlistaTetels::class)
            ->callAction('import', data: [
                'file' => UploadedFile::fake()->createWithContent('arlista.csv', $csv),
                'columnMap' => self::COLUMN_MAP,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseCount('adatok', 1);
        $this->assertDatabaseHas('adatok', [
            'id' => $tetel->id,
            'ar' => '20000',
        ]);
    }

    public function test_it_reports_failed_rows_for_unknown_kategoria_without_crashing(): void
    {
        $this->actingAs(User::factory()->create());
        Kategoria::factory()->create(['nev' => 'Fogszabályozás']);

        $csv = "id,kategoria,muveletnev,ar,kiegeszites\n"
            . ",Nemletezo Kategoria,Ismeretlen,5000,\n"
            . ",Fogszabályozás,Konzultáció,15000,\n";

        Livewire::test(ListArlistaTetels::class)
            ->callAction('import', data: [
                'file' => UploadedFile::fake()->createWithContent('arlista.csv', $csv),
                'columnMap' => self::COLUMN_MAP,
            ])
            ->assertHasNoActionErrors();

        $import = Import::query()->latest('id')->first();

        $this->assertNotNull($import);
        $this->assertSame(1, $import->successful_rows);
        $this->assertSame(1, $import->getFailedRowsCount());
        $this->assertDatabaseHas('adatok', ['muveletnev' => 'Konzultáció']);
        $this->assertDatabaseMissing('adatok', ['muveletnev' => 'Ismeretlen']);
    }
}
```

- [ ] **Step 2: Run the tests to verify they fail**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan test --filter=ArlistaTetelImportTest`
Expected: FAIL — the `import` action does not exist on `ListArlistaTetels` yet.

- [ ] **Step 3: Create the Importer class**

Create `app/Filament/Imports/ArlistaTetelImporter.php`:

```php
<?php

namespace App\Filament\Imports;

use App\Models\ArlistaTetel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;

class ArlistaTetelImporter extends Importer
{
    protected static ?string $model = ArlistaTetel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('ID')
                ->integer(),
            ImportColumn::make('kategoria')
                ->label('Kategória')
                ->relationship(resolveUsing: 'nev')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('muveletnev')
                ->label('Beavatkozás')
                ->requiredMapping()
                ->rules(['required', 'max:1000']),
            ImportColumn::make('ar')
                ->label('Ár')
                ->requiredMapping()
                ->rules(['required', 'max:100']),
            ImportColumn::make('kiegeszites')
                ->label('Kiegészítés')
                ->rules(['max:200']),
        ];
    }

    public function resolveRecord(): ?Model
    {
        if (filled($this->data['id'] ?? null)) {
            return ArlistaTetel::find($this->data['id']) ?? new ArlistaTetel();
        }

        return new ArlistaTetel();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Az árlista importálása befejeződött, ' . number_format($import->successful_rows) . ' ' . str('sor')->plural($import->successful_rows) . ' importálva.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('sor')->plural($failedRowsCount) . ' importálása sikertelen.';
        }

        return $body;
    }
}
```

- [ ] **Step 4: Wire the ImportAction into the list page**

Replace the contents of `app/Filament/Resources/ArlistaTetelResource/Pages/ListArlistaTetels.php`:

```php
<?php

namespace App\Filament\Resources\ArlistaTetelResource\Pages;

use App\Filament\Exports\ArlistaTetelExporter;
use App\Filament\Imports\ArlistaTetelImporter;
use App\Filament\Resources\ArlistaTetelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArlistaTetels extends ListRecords
{
    protected static string $resource = ArlistaTetelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(ArlistaTetelImporter::class),
            Actions\ExportAction::make()
                ->exporter(ArlistaTetelExporter::class),
        ];
    }
}
```

- [ ] **Step 5: Run the tests to verify they pass**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan test --filter=ArlistaTetelImportTest`
Expected: PASS (3 tests).

- [ ] **Step 6: Run the full test suite**

Run: `"C:/Users/NFDorottya/.config/herd/bin/php84.bat" artisan test`
Expected: all tests pass except the pre-existing, unrelated `Tests\Feature\ExampleTest::test_the_application_returns_a_successful_response` failure (missing `kategoriak` table in that test's DB — present before this plan, out of scope here).

- [ ] **Step 7: Commit**

```bash
git add app/Filament/Imports/ArlistaTetelImporter.php app/Filament/Resources/ArlistaTetelResource/Pages/ListArlistaTetels.php tests/Feature/Filament/ArlistaTetelImportTest.php
git commit -m "feat: add árlista CSV import action"
```

---

### Task 5: Manual smoke test in the browser

**Files:** none (verification only).

- [ ] **Step 1: Start the admin panel**

Start the `fogaszat-web` dev server (already configured in `.claude/launch.json`, port 8765) and open `http://localhost:8765/admin/arlista-tetels`, logged in as an admin user.

- [ ] **Step 2: Export**

Click **Export**, choose CSV, download the file, open it, and confirm it lists every current árlista tétel with the right kategória, beavatkozás, ár, and kiegészítés columns.

- [ ] **Step 3: Import (update)**

Edit one row's price in the downloaded CSV (keep its `id` column), then use **Import** to re-upload it. Confirm the table shows the updated price and no duplicate row was created.

- [ ] **Step 4: Import (create)**

Add a new row to the CSV with a blank `id` and a valid, existing kategória name, then import it. Confirm a new árlista tétel appears in the table under the right category.

- [ ] **Step 5: Import (invalid category)**

Add a row with a made-up kategória name and import. Confirm the UI reports a failed row without crashing, and the other rows still import correctly.
