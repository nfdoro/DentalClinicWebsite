# SEO Fejlesztés — Hátramaradt Teendők

**Utolsó frissítés:** 2026-06-30  
**Elvégzett munka:** `seo/tech-fixes`, `seo/blog`, `seo/landing-pages` branchek — GitHub-on elérhetők, main-be még nem mergeltek.

---

## 1. Szerveroldali feladatok (te végzed el)

### Migration futtatása
```bash
php artisan migrate
```
Hozzáadja a `kiemelt_leiras` (longText, nullable) mezőt a `kategoriak` táblához.  
Nélküle az Észak-Magyarország landing oldal és a kiemelt leírás szekció nem törik el, de az admin form hibát dob mentéskor.

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

## 3. Admin panelen kitöltendő tartalom

Az admin panel: `https://fogaszat-miskolc.hu/admin`

### Kiemelt leírások (SEO-optimalizált hosszú szöveg)
A `Kategóriák` menüpontban a következő 3 szolgáltatásnál töltsd ki a **Kiemelt leírás** mezőt (RichEditor, min. 400-600 szó):
- **Invisalign / Fogszabályozás** — kulcsszavak: invisalign ár magyarország, fogszabályozás miskolc, láthatatlan fogszabályozó
- **Fogpótlás / Implantátum** — kulcsszavak: fogimplantátum miskolc, fogpótlás ár, implantátum vagy híd
- **Fogfehérítés** — kulcsszavak: fogfehérítés miskolc, fogfehérítés ár, otthoni fogfehérítés

### GYIK kérdések
A `GYIK kérdések` menüpontban adj hozzá 3-5 kérdést minden kiemelt szolgáltatáshoz.  
Ezek megjelennek az oldalon accordion-ként **és** a Google „People Also Ask" dobozban is (FAQPage schema).

---

## 4. Blog cikkek (tartalom marketing)

A `Blog cikkek` menüpontban írd meg az első cikkeket. Javasolt sorrend:

| Cikk | Célzott kulcsszó | Prioritás |
|------|-----------------|-----------|
| Mennyibe kerül az Invisalign Magyarországon 2026-ban? | invisalign ár magyarország | 🔴 Magas |
| Implantátum vagy híd — melyiket válasszam? | fogimplantátum vagy híd | 🔴 Magas |
| Fogfehérítés előtt-után: mit várj a kezeléstől? | fogfehérítés tapasztalatok | 🔴 Magas |
| Fogorvos Miskolcon — miért válasszon minket? | fogorvos miskolc | 🟡 Közepes |
| Miskolctól Egerig — miért érdemes utazni a fogorvoshoz? | fogászat eger miskolc | 🟡 Közepes |

Minden cikknél töltsd ki: cím, bevezető, tartalom (min. 600 szó), meta leírás, közzététel dátuma.

---

## 5. Branchek merge-elése main-be

Ha a szerver tesztelés rendben van, a 3 SEO branch merge-elhető main-be:

```bash
git checkout main
git merge seo/tech-fixes
git merge seo/blog
git merge seo/landing-pages
git push origin main
```

**Sorrend fontos** — `seo/tech-fixes` az alap, arra épül a másik kettő.

---

## 6. Mérési terv (folyamatos)

| Eszköz | Mit nézel | Mikor |
|--------|-----------|-------|
| Google Search Console | Impressziók, kattintások, rangsor | Hetente |
| Google Analytics 4 | Látogatók, forrás, tel. kattintás | Havonta |
| Google Business Profile | Helyikeresések, irányítás kérés, hívások | Havonta |
