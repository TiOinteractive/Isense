/* iSense — panel: generyczny repeater pozycji listy (dodaj/usuń) dla formularzy sekcji. */
(function () {
  "use strict";

  function initRepeater(rep) {
    var items = rep.querySelector("[data-repeater-items]");
    var tpl = rep.querySelector("[data-repeater-template]");
    var addBtn = rep.querySelector("[data-repeater-add]");
    if (!items || !tpl || !addBtn) return;

    // Licznik początkowy = max istniejący indeks + 1 (żeby nowe pozycje nie nadpisywały istniejących).
    var counter = items.querySelectorAll("[data-repeater-row]").length;

    addBtn.addEventListener("click", function (e) {
      e.preventDefault();
      var html = tpl.innerHTML.replace(/__i__/g, "new" + counter);
      counter++;
      var wrap = document.createElement("div");
      wrap.innerHTML = html.trim();
      var row = wrap.firstElementChild;
      items.appendChild(row);
    });

    items.addEventListener("click", function (e) {
      var rm = e.target.closest("[data-repeater-remove]");
      if (rm) {
        e.preventDefault();
        var row = rm.closest("[data-repeater-row]");
        if (row) row.remove();
      }
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-repeater]").forEach(initRepeater);
  });
})();
