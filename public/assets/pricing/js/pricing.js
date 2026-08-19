/* Cennik (moduł Pricing) — przełączanie kategorii i usług. Vanilla JS, bez zależności. */
(function () {
  "use strict";

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  function setActive(el, active) {
    if (!el) return;
    el.classList.toggle("is-active", active);
    if (el.hasAttribute("aria-selected")) el.setAttribute("aria-selected", active ? "true" : "false");
  }

  function initPricing(root) {
    if (!root || root.getAttribute("data-pricing-ready")) return;
    root.setAttribute("data-pricing-ready", "1");

    var tabs = Array.prototype.slice.call(root.querySelectorAll("[data-pricing-tab]"));

    function panels() {
      return Array.prototype.slice.call(root.querySelectorAll("[data-pricing-panel]"));
    }

    function activePanel() {
      return root.querySelector("[data-pricing-panel].is-active") || panels()[0] || null;
    }

    /* Usługi i modele przełączamy tylko w obrębie jednego panelu — kategorie się nie mieszają. */
    function selectService(panel, id) {
      if (!panel || !id) return;
      panel.querySelectorAll("[data-pricing-service]").forEach(function (btn) {
        setActive(btn, btn.getAttribute("data-pricing-service") === id);
      });
      panel.querySelectorAll("[data-pricing-models]").forEach(function (box) {
        var on = box.getAttribute("data-pricing-models") === id;
        setActive(box, on);
        if (on) box.removeAttribute("hidden");
        else box.setAttribute("hidden", "hidden");
      });
    }

    function firstServiceId(panel) {
      var first = panel ? panel.querySelector("[data-pricing-service]") : null;
      return first ? first.getAttribute("data-pricing-service") : null;
    }

    function selectCategory(id) {
      if (!id) return;
      tabs.forEach(function (tab) {
        var on = tab.getAttribute("data-pricing-tab") === id;
        setActive(tab, on);
        tab.setAttribute("tabindex", on ? "0" : "-1");
      });
      panels().forEach(function (panel) {
        var on = panel.getAttribute("data-pricing-panel") === id;
        setActive(panel, on);
        if (on) panel.removeAttribute("hidden");
        else panel.setAttribute("hidden", "hidden");
      });
      // Kategoria bez usług po prostu nic nie wybiera — brak selektora nie jest błędem.
      var panel = activePanel();
      selectService(panel, firstServiceId(panel));
    }

    root.addEventListener("click", function (e) {
      var tab = e.target.closest ? e.target.closest("[data-pricing-tab]") : null;
      if (tab && root.contains(tab)) {
        e.preventDefault();
        selectCategory(tab.getAttribute("data-pricing-tab"));
        return;
      }
      var service = e.target.closest ? e.target.closest("[data-pricing-service]") : null;
      if (service && root.contains(service)) {
        e.preventDefault();
        selectService(service.closest("[data-pricing-panel]"), service.getAttribute("data-pricing-service"));
      }
    });

    /* Belka zakładek obsługiwana klawiaturą zgodnie ze wzorcem tablist. */
    if (tabs.length > 1) {
      root.addEventListener("keydown", function (e) {
        var tab = e.target.closest ? e.target.closest("[data-pricing-tab]") : null;
        if (!tab) return;
        var i = tabs.indexOf(tab);
        var next = null;
        if (e.key === "ArrowRight") next = tabs[(i + 1) % tabs.length];
        else if (e.key === "ArrowLeft") next = tabs[(i - 1 + tabs.length) % tabs.length];
        else if (e.key === "Home") next = tabs[0];
        else if (e.key === "End") next = tabs[tabs.length - 1];
        if (!next) return;
        e.preventDefault();
        selectCategory(next.getAttribute("data-pricing-tab"));
        next.focus();
      });
    }

    // Stan początkowy ustawia serwer; to tylko domknięcie, gdyby żaden panel nie był aktywny.
    if (!root.querySelector("[data-pricing-panel].is-active")) {
      var first = panels()[0];
      if (first) selectCategory(first.getAttribute("data-pricing-panel"));
    }
  }

  ready(function () {
    document.querySelectorAll("[data-pricing]").forEach(initPricing);
  });
})();
