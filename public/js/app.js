/* TaskKas — interaksi UI */
(function () {
  'use strict';

  /* tab Masuk / Gabung (tanpa reload) */
  var tabs = document.querySelectorAll('[data-tab]');
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var name = tab.dataset.tab;
      tabs.forEach(function (t) { t.classList.toggle('active', t === tab); t.setAttribute('aria-selected', t === tab); });
      document.querySelectorAll('[data-pane]').forEach(function (p) { p.hidden = p.dataset.pane !== name; });
      history.replaceState(null, '', tab.dataset.href);
    });
  });

  /* tampil/sembunyi password */
  document.querySelectorAll('[data-toggle-pass]').forEach(function (b) {
    b.addEventListener('click', function () {
      var input = b.parentElement.querySelector('input');
      input.type = input.type === 'password' ? 'text' : 'password';
      b.classList.toggle('on', input.type === 'text');
    });
  });

  /* dialog tambah / edit (tugas & transaksi) */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-dialog]');
    if (btn) {
      var dlg = document.getElementById(btn.dataset.dialog), form = dlg.querySelector('form');
      form.reset();
      form.action = btn.dataset.action;
      dlg.querySelector('[data-title]').textContent = btn.dataset.title || '';
      var vals = btn.dataset.values ? JSON.parse(btn.dataset.values) : {};
      Object.keys(vals).forEach(function (k) { if (form.elements[k]) form.elements[k].value = vals[k] == null ? '' : vals[k]; });
      form.dispatchEvent(new Event('change'));
      dlg.showModal();
      return;
    }
    if (e.target.matches('[data-close]') || (e.target.tagName === 'DIALOG' && e.target.open)) {
      (e.target.closest('dialog') || e.target).close();
    }
  });

  /* kolom "pembayar iuran" hanya muncul untuk kategori iuran */
  document.querySelectorAll('[data-cashform]').forEach(function (form) {
    var box = form.querySelector('[data-member]');
    form.addEventListener('change', function () { box.hidden = form.elements.category.value !== 'iuran'; });
    box.hidden = form.elements.category.value !== 'iuran';
  });

  /* alert hilang otomatis */
  document.querySelectorAll('.alert[data-auto]').forEach(function (a) {
    setTimeout(function () { a.classList.add('gone'); }, 4500);
  });
})();
