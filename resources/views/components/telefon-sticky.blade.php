{{-- Globális, mindig elérhető telefonos kapcsolatfelvételi gomb.
     A szám a config/kapcsolat.php-ból jön (egyetlen forrás). --}}
<div class="tel-fab" id="telFab">
  <div class="tel-fab-panel" id="telFabPanel" role="dialog" aria-label="Telefonos elérhetőség">
    <span class="tel-fab-panel-label">Hívjon minket</span>
    <a href="tel:{{ config('kapcsolat.telefon_hivas') }}" class="tel-fab-number">
      <i class="bi bi-telephone-fill" aria-hidden="true"></i>
      {{ config('kapcsolat.telefon') }}
    </a>
  </div>
  <button type="button" class="tel-fab-btn" id="telFabBtn"
          aria-label="Telefonszám megjelenítése"
          aria-expanded="false" aria-controls="telFabPanel">
    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
  </button>
</div>

<script>
  (function () {
    var fab = document.getElementById('telFab');
    if (!fab) return;
    var btn = document.getElementById('telFabBtn');

    function open() {
      fab.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');
      btn.setAttribute('aria-label', 'Telefonszám elrejtése');
    }
    function close() {
      fab.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
      btn.setAttribute('aria-label', 'Telefonszám megjelenítése');
    }
    function toggle() {
      fab.classList.contains('is-open') ? close() : open();
    }

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      toggle();
    });

    // Külső területre kattintva összecsukjuk.
    document.addEventListener('click', function (e) {
      if (fab.classList.contains('is-open') && !fab.contains(e.target)) close();
    });

    // Escape billentyűre bezárjuk, és visszaadjuk a fókuszt a gombra.
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && fab.classList.contains('is-open')) {
        close();
        btn.focus();
      }
    });
  })();
</script>
