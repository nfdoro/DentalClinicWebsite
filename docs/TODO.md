# SEO Fejlesztés — Hátramaradt Teendők

**Utolsó frissítés:** 2026-07-08  
**Elvégzett munka:** `seo/tech-fixes`, `seo/blog`, `seo/landing-pages` branchek — 2026-07-08-án mergelve main-be és pusholva (`git log` lásd). Migráció lokális fejlesztői környezetben lefuttatva. SEO tartalom (kiemelt leírások, GYIK, 5 blog cikk) megírva: [docs/SEO-TARTALOM-KESZ.md](SEO-TARTALOM-KESZ.md) — bemásolásra vár az admin panelbe, illetve lokálisan már beültetve tesztelés céljából.

---

## 1. Szerveroldali feladatok (te végzed el)

### Migration futtatása — ÉLES SZERVEREN MÉG SZÜKSÉGES
```bash
php artisan migrate
```
Hozzáadja a `kiemelt_leiras` (longText, nullable) mezőt a `kategoriak` táblához, valamint a `cikkek` és `faqs` táblákat.  
Lokális fejlesztői adatbázison (sqlite) már lefutott és ellenőrizve lett (routes, admin, blog, landing oldal mind 200-as választ adnak). Az éles szerveren ugyanezt kell futtatni a `main` branch deploy-a után.

---

## 2. Google eszközök beállítása

### Google Search Console
1. Regisztráció: https://search.google.com/search-console
2. Domain hitelesítés: DNS TXT rekord a tárhelyszolgáltatónál  
   *(vagy HTML meta tag — a layout `config('services.search_console.verify')` alapján automatikusan kiírja, csak az `.env`-be kell: `SEARCH_CONSOLE_VERIFY=...`)*
3. Sitemap beküldése: `https://fogaszat-miskolc.hu/sitemap.xml`

### Google Analytics 4
1. Új GA4 property létrehozása: https://analytics.google.com
2. Tracking ID (G-XXXXXXXX) beírása az `.env`-be: `GA4_TRACKING_ID=G-XXXXXXXX`  
   *(a layout automatikusan betölti, ha ki van töltve)*

### Google Business Profile
1. Regisztráció: https://business.google.com
2. Kategória: „Fogász" + „Fogászat"
3. Cím: 3525 Miskolc, Madarász Viktor utca 13/A
4. Telefon: +36 70 627 6160, nyitvatartás, min. 10 fotó
5. Hitelesítés (postai kártya vagy hívás)
6. Miután megvan a Google Business profil URL:  
   Hozzáadni a `home.blade.php` Dentist schema-jába:
   ```json
   "sameAs": [
     "https://www.facebook.com/[FACEBOOK_URL]",
     "https://g.page/[GOOGLE_BUSINESS_ID]"
   ]
   ```

---

## 3. Admin panelen kitöltendő tartalom — MEGÍRVA, MÁSOLÁSRA VÁR

Az admin panel: `https://fogaszat-miskolc.hu/admin`

A teljes, kész szöveg itt található: [docs/SEO-TARTALOM-KESZ.md](SEO-TARTALOM-KESZ.md).
Lokális dev adatbázisban már be van ültetve (`kategoriak.kiemelt_leiras`, `faqs` tábla) — az éles admin panelbe csak be kell másolni.

### Kiemelt leírások (SEO-optimalizált hosszú szöveg)
A `Kategóriák` menüpontban a következő 3 szolgáltatásnál töltsd ki a **Kiemelt leírás** mezőt (RichEditor, min. 400-600 szó):
- **Invisalign / Fogszabályozás** — kulcsszavak: invisalign ár magyarország, fogszabályozás miskolc, láthatatlan fogszabályozó
- **Fogpótlás / Implantátum** — kulcsszavak: fogimplantátum miskolc, fogpótlás ár, implantátum vagy híd
- **Fogfehérítés** — kulcsszavak: fogfehérítés miskolc, fogfehérítés ár, otthoni fogfehérítés

### GYIK kérdések
A `GYIK kérdések` menüpontban adj hozzá 3-5 kérdést minden kiemelt szolgáltatáshoz.  
Ezek megjelennek az oldalon accordion-ként **és** a Google „People Also Ask" dobozban is (FAQPage schema).
5-5 kérdés-válasz mindhárom kategóriához megírva a SEO-TARTALOM-KESZ.md-ben.

---

## 4. Blog cikkek (tartalom marketing) — MEGÍRVA, MÁSOLÁSRA VÁR

A `Blog cikkek` menüpontban töltsd fel a cikkeket. Mind az 5 cikk teljes szövege (cím, bevezető, tartalom,
meta leírás, javasolt közzétételi dátum) elkészült: [docs/SEO-TARTALOM-KESZ.md](SEO-TARTALOM-KESZ.md).

| Cikk | Célzott kulcsszó | Prioritás | Javasolt dátum |
|------|-----------------|-----------|-----------------|
| Mennyibe kerül az Invisalign Magyarországon 2026-ban? | invisalign ár magyarország | 🔴 Magas | 2026-07-15 |
| Implantátum vagy híd — melyiket válasszam? | fogimplantátum vagy híd | 🔴 Magas | 2026-07-22 |
| Fogfehérítés előtt-után: mit várj a kezeléstől? | fogfehérítés tapasztalatok | 🔴 Magas | 2026-07-29 |
| Fogorvos Miskolcon — miért válasszon minket? | fogorvos miskolc | 🟡 Közepes | 2026-08-05 |
| Miskolctól Egerig — miért érdemes utazni a fogorvoshoz? | fogászat eger miskolc | 🟡 Közepes | 2026-08-12 |

A dátumok csak javaslatok (kb. hetenkénti ütemezés) — nyugodtan igazítsd a saját naptáradhoz.

---

## 5. Branchek merge-elése main-be — KÉSZ (2026-07-08)

A 3 SEO branch konfliktusmentesen mergelve lett main-be (sorrendben: tech-fixes → blog → landing-pages),
és pusholva az origin-re. Lokálisan tesztelve: routes, admin, migráció, blog, landing oldal mind működik.

**Hátravan:** a szerveren (production) a deploy után futtatni kell a `php artisan migrate` parancsot
(lásd 1. pont), mert a `kiemelt_leiras` mező és a `cikkek`/`faqs` táblák még nincsenek ott meg.

---

## 6. Mérési terv (folyamatos)

| Eszköz | Mit nézel | Mikor |
|--------|-----------|-------|
| Google Search Console | Impressziók, kattintások, rangsor | Hetente |
| Google Analytics 4 | Látogatók, forrás, tel. kattintás | Havonta |
| Google Business Profile | Helyikeresések, irányítás kérés, hívások | Havonta |
