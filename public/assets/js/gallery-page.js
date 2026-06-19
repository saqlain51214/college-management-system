(function () {
  var grid = document.getElementById('galleryGrid');
  var dlg = document.getElementById('galleryLightbox');
  var imgEl = document.getElementById('galleryLightboxImg');
  var capEl = document.getElementById('lightbox-caption');
  var closeBtn = document.getElementById('galleryLightboxClose');
  if (!grid || !dlg) return;

  function setFilter(cat) {
    grid.classList.add('is-filtering');
    var cards = grid.querySelectorAll('.gallery-card');
    cards.forEach(function (card) {
      var cats = (card.getAttribute('data-cats') || '').split(/\s+/);
      var show = cat === 'all' || cats.indexOf(cat) !== -1;
      card.classList.toggle('hidden', !show);
      card.classList.toggle('is-active', show);
    });
    setTimeout(function () {
      grid.classList.remove('is-filtering');
    }, 50);
    document.querySelectorAll('.gallery-filter').forEach(function (btn) {
      var on = btn.getAttribute('data-gallery-filter') === cat;
      btn.classList.toggle('bg-brand', on);
      btn.classList.toggle('text-white', on);
      btn.classList.toggle('border-brand', on);
      btn.classList.toggle('bg-white', !on);
      btn.classList.toggle('text-stone-700', !on);
      btn.classList.toggle('border-stone-200', !on);
    });
  }

  document.querySelectorAll('.gallery-filter').forEach(function (btn) {
    btn.addEventListener('click', function () {
      setFilter(btn.getAttribute('data-gallery-filter') || 'all');
    });
  });

  grid.querySelectorAll('.gallery-card').forEach(function (card) {
    card.addEventListener('click', function () {
      var im = card.querySelector('img');
      if (!im || !imgEl || !capEl) return;
      imgEl.src = im.getAttribute('src') || '';
      imgEl.alt = im.getAttribute('alt') || '';
      capEl.textContent = im.getAttribute('data-caption') || im.getAttribute('alt') || '';
      if (typeof dlg.showModal === 'function') dlg.showModal();
    });
  });

  function closeLb() {
    if (dlg.open) dlg.close();
  }
  if (closeBtn) closeBtn.addEventListener('click', closeLb);
  dlg.addEventListener('click', function (e) {
    if (e.target === dlg) closeLb();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && dlg.open) closeLb();
  });
})();
