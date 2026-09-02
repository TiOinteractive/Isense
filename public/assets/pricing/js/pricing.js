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
        var on = btn.getAttribute("data-pricing-service") === id;
        setActive(btn, on);
        btn.setAttribute("tabindex", on ? "0" : "-1");
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

    /* Obie belki zakładek obsługiwane klawiaturą zgodnie ze wzorcem tablist:
       kategorie poziomo (strzałki lewo/prawo), usługi pionowo (góra/dół). */
    function step(list, current, key, prevKey, nextKey) {
      var i = list.indexOf(current);
      if (i < 0) return null;
      if (key === nextKey) return list[(i + 1) % list.length];
      if (key === prevKey) return list[(i - 1 + list.length) % list.length];
      if (key === "Home") return list[0];
      if (key === "End") return list[list.length - 1];
      return null;
    }

    root.addEventListener("keydown", function (e) {
      if (!e.target.closest) return;

      var tab = e.target.closest("[data-pricing-tab]");
      if (tab && tabs.length > 1) {
        var nextTab = step(tabs, tab, e.key, "ArrowLeft", "ArrowRight");
        if (!nextTab) return;
        e.preventDefault();
        selectCategory(nextTab.getAttribute("data-pricing-tab"));
        nextTab.focus();
        return;
      }

      var service = e.target.closest("[data-pricing-service]");
      if (!service) return;
      var panel = service.closest("[data-pricing-panel]") || root;
      var services = Array.prototype.slice.call(panel.querySelectorAll("[data-pricing-service]"));
      if (services.length < 2) return;
      var nextService = step(services, service, e.key, "ArrowUp", "ArrowDown");
      if (!nextService) return;
      e.preventDefault();
      selectService(panel, nextService.getAttribute("data-pricing-service"));
      nextService.focus();
    });

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
