# Admin felületen elvégzendő tartalmi javítások

Ez a lista azokat a tartalmi javításokat gyűjti össze, amelyek **az adatbázisban tárolt,
a Filament adminban szerkesztett szövegeket** érintik. Ezeket a kód nem módosította,
mert a tartalmat éles admin felületen szerkesztitek, és a `database.sqlite` felülírása
törölné az ott végzett szerkesztéseket.

A javítás helye mindenhol: **`/admin`** → az adott erőforrás (Kategóriák / Cikkek) szerkesztő űrlapja.

---

## 1. Félrevezető „ingyenes konzultáció" állítások

A weboldal nem állíthatja, hogy a konzultáció ingyenes (az árlistában a fogszabályozási
konzultáció 10.000 Ft-tól szerepel). A kód oldali előfordulásokat már javítottuk; a
következők az adatbázisban maradtak:

### Kategóriák → Kiemelt leírás mező

- **Fogszabályozás**
  - „...ingyenes első konzultáció keretében." → „...személyes konzultáció keretében."
  - „Ingyenes konzultáció és szájvizsgálat..." → „Konzultáció és szájvizsgálat..."
  - „...foglaljon időpontot ingyenes konzultációra..." → „...foglaljon időpontot konzultációra..."
- **Fogpótlás**
  - „Kérjen ingyenes konzultációt és árajánlatot." → „Kérjen konzultációt és árajánlatot."

### Cikkek → Tartalom mező

- **„Miskolctól Egerig..."**
  - „...érdemes egy ingyenes találkozóval kezdeni" → „...érdemes egy személyes konzultációval kezdeni"
  - „...foglaljon egy ingyenes konzultációt." → „...foglaljon egy konzultációt."
- **„Önligírozó (kapcsos) fogszabályozó..."**
  - „Az első lépés mindig egy ingyenes konzultáció..." → „Az első lépés mindig egy konzultáció..."
  - „...foglaljon ingyenes konzultációt..." → „...foglaljon konzultációt..."

---

## 2. Em dash (—) karakterek

A kívánság szerint a szövegek ne tartalmazzanak em dash („—") karaktert. Ajánlott csere:
vessző, kettőspont vagy sima kötőjel („-"). Az adatbázisban a következők érintettek:

### Cikkek → Cím

- „Implantátum vagy híd **—** melyiket válasszam?"
- „Fogorvos Miskolcon **—** miért válasszon minket?"
- „Miskolctól Egerig **—** miért érdemes utazni a fogorvoshoz?"
- „Önligírozó (kapcsos) fogszabályozó **—** mit érdemes tudni róla?"

### Cikkek → Tartalom

- Em dash található a következő cikkek szövegtörzsében: #2, #3, #4, #6 (a szerkesztőben
  a „—" karakterekre keresve cserélhetők).

### Kategóriák → Kiemelt leírás

- Em dash található a **Fogszabályozás**, **Fogpótlás** és **Fogfehérítés** kiemelt leírásában.

---

## Megjegyzés

Ha bármelyik szöveget inkább kódból (fix tartalomként) szeretnétek kezelni a jövőben,
az megoldható, de akkor az adott mező nem lesz többé adminból szerkeszthető. Jelenleg a
tartalom teljesen a ti kezetekben marad.
