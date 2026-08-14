/* iSense — interakcje front-endu. Vanilla JS, bez zależności. */
(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    /* ── Pasek ogłoszeniowy ── */
    var announcement = document.querySelector("[data-announcement]");
    var announcementClose = document.querySelector("[data-announcement-close]");
    if (announcement && announcementClose) {
      announcementClose.addEventListener("click", function () {
        announcement.remove();
      });
    }

    /* ── Dropdowny nawigacji (desktop, hover z opóźnieniem) ── */
    document.querySelectorAll("[data-dropdown]").forEach(function (dropdown) {
      var menu = dropdown.querySelector("[data-dropdown-menu]");
      var caret = dropdown.querySelector("[data-dropdown-caret]");
      if (!menu) return;
      var leaveTimer = null;
      var open = function () {
        if (leaveTimer) clearTimeout(leaveTimer);
        menu.classList.remove("hidden");
        if (caret) caret.classList.add("rotate-180");
      };
      var close = function () {
        leaveTimer = setTimeout(function () {
          menu.classList.add("hidden");
          if (caret) caret.classList.remove("rotate-180");
        }, 120);
      };
      dropdown.addEventListener("mouseenter", open);
      dropdown.addEventListener("mouseleave", close);
      var toggle = dropdown.querySelector("[data-dropdown-toggle]");
      if (toggle) {
        toggle.addEventListener("click", function () {
          menu.classList.contains("hidden") ? open() : close();
        });
      }
    });

    /* ── Menu mobilne ── */
    var mobileToggle = document.querySelector("[data-mobile-toggle]");
    var mobileMenu = document.querySelector("[data-mobile-menu]");
    var iconOpen = document.querySelector("[data-mobile-icon-open]");
    var iconClose = document.querySelector("[data-mobile-icon-close]");
    if (mobileToggle && mobileMenu) {
      mobileToggle.addEventListener("click", function () {
        var isHidden = mobileMenu.classList.toggle("hidden");
        if (iconOpen) iconOpen.classList.toggle("hidden", !isHidden);
        if (iconClose) iconClose.classList.toggle("hidden", isHidden);
      });
    }

    /* ── Akordeon „Serwis Apple" (mobile) ── */
    var appleToggle = document.querySelector("[data-mobile-apple-toggle]");
    var appleMenu = document.querySelector("[data-mobile-apple-menu]");
    var appleCaret = document.querySelector("[data-mobile-apple-caret]");
    if (appleToggle && appleMenu) {
      appleToggle.addEventListener("click", function () {
        var isHidden = appleMenu.classList.toggle("hidden");
        if (appleCaret) appleCaret.classList.toggle("rotate-180", !isHidden);
      });
    }

    /* ── Karuzela opinii ── */
    var carousel = document.querySelector("[data-carousel]");
    if (carousel) {
      var total = parseInt(carousel.getAttribute("data-total"), 10) || 0;
      var slides = carousel.querySelectorAll("[data-slide]");
      var status = carousel.querySelector("[data-carousel-status]");
      var current = 0;

      var render = function () {
        var a = current % total;
        var b = (current + 1) % total;
        slides.forEach(function (slide, i) {
          slide.classList.toggle("hidden", i !== a && i !== b);
        });
        if (status) status.textContent = (a + 1) + "–" + (b + 1) + " / " + total;
      };

      var prevBtn = carousel.querySelector("[data-carousel-prev]");
      var nextBtn = carousel.querySelector("[data-carousel-next]");
      if (prevBtn) prevBtn.addEventListener("click", function () { current = (current - 1 + total) % total; render(); });
      if (nextBtn) nextBtn.addEventListener("click", function () { current = (current + 1) % total; render(); });
      render();
    }

    /* ── Animowane liczniki (count-up) ── */
    var counters = document.querySelectorAll("[data-countup]");
    if (counters.length) {
      var animate = function (el) {
        var text = (el.getAttribute("data-countup-raw") || el.textContent).trim();
        el.setAttribute("data-countup-raw", text);
        var m = text.match(/^(\D*?)(\d[\d\s.,]*)(.*)$/);
        if (!m) return;
        var prefix = m[1], suffix = m[3];
        var target = parseInt(m[2].replace(/\D/g, ""), 10);
        if (isNaN(target)) return;
        var duration = 1600, start = null;
        var step = function (ts) {
          if (start === null) start = ts;
          var p = Math.min((ts - start) / duration, 1);
          var eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
          el.textContent = prefix + Math.round(eased * target) + suffix;
          if (p < 1) requestAnimationFrame(step);
          else el.textContent = text;
        };
        requestAnimationFrame(step);
      };

      var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      if (reduce || !("IntersectionObserver" in window)) {
        // bez animacji — zostaw wartości docelowe
      } else {
        var io = new IntersectionObserver(function (entries, obs) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              animate(entry.target);
              obs.unobserve(entry.target);
            }
          });
        }, { threshold: 0.4 });
        counters.forEach(function (el) { io.observe(el); });
      }
    }

    /* ── Formularze AJAX (naprawa z odbiorem / kontakt) ── */
    var showMsg = function (box, ok, text) {
      if (!box) return;
      box.classList.remove("hidden");
      box.textContent = text;
      if (ok) {
        box.style.background = "#ecfdf5"; box.style.color = "#047857"; box.style.border = "1px solid #a7f3d0";
      } else {
        box.style.background = "#fef2f2"; box.style.color = "#b91c1c"; box.style.border = "1px solid #fecaca";
      }
    };

    document.querySelectorAll("[data-ajax-form]").forEach(function (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        var msg = form.querySelector("[data-form-msg]");
        var btn = form.querySelector('button[type="submit"]');
        var btnText = btn ? btn.innerHTML : "";
        if (btn) { btn.disabled = true; btn.style.opacity = "0.6"; btn.innerHTML = "Wysyłanie…"; }

        fetch(form.getAttribute("action"), {
          method: "POST",
          headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" },
          body: new FormData(form)
        })
          .then(function (r) { return r.json().catch(function () { return { ok: false, error: "Błąd serwera." }; }); })
          .then(function (res) {
            if (res && res.ok) {
              showMsg(msg, true, res.message || "Dziękujemy! Zgłoszenie zostało wysłane.");
              form.reset();
            } else {
              showMsg(msg, false, (res && res.error) || "Nie udało się wysłać. Spróbuj ponownie.");
            }
          })
          .catch(function () { showMsg(msg, false, "Problem z połączeniem. Spróbuj ponownie."); })
          .finally(function () {
            if (btn) { btn.disabled = false; btn.style.opacity = ""; btn.innerHTML = btnText; }
          });
      });
    });

    /* ── Kreator wyceny Trade-In (cena z wybranych opcji) ── */
    document.querySelectorAll("[data-tradein]").forEach(function (root) {
      var dataEl = root.querySelector("[data-tradein-data]");
      if (!dataEl) return;
      var cfg;
      try { cfg = JSON.parse(dataEl.textContent); } catch (e) { return; }
      var devices = cfg.devices || [], conditions = cfg.conditions || [];
      var round = cfg.round || 10, currency = cfg.currency || "zł";
      var modelSel = root.querySelector("[data-tradein-model]");
      var estEl = root.querySelector("[data-tradein-estimate]");
      var devBtns = root.querySelectorAll("[data-tradein-device]");
      var condBtns = root.querySelectorAll("[data-tradein-cond]");
      var devIdx = 0, condIdx = 0;
      var ACT = ["border-[#3b81f7]", "bg-[#3b81f7]/10"];
      var INACT = ["border-[#D2D2D7]", "hover:border-[#6E6E73]"];

      var setActive = function (btns, activeBtn) {
        btns.forEach(function (b) {
          var on = b === activeBtn;
          ACT.forEach(function (c) { b.classList.toggle(c, on); });
          INACT.forEach(function (c) { b.classList.toggle(c, !on); });
        });
      };
      var money = function (n) {
        n = Math.round(n / round) * round;
        return new Intl.NumberFormat("pl-PL").format(n) + " " + currency;
      };
      var populateModels = function () {
        if (!modelSel) return;
        modelSel.innerHTML = "";
        ((devices[devIdx] && devices[devIdx].models) || []).forEach(function (m, i) {
          var o = document.createElement("option");
          o.value = i; o.textContent = m.name;
          modelSel.appendChild(o);
        });
        modelSel.value = "0";
      };
      var calc = function () {
        var models = (devices[devIdx] && devices[devIdx].models) || [];
        var m = models[modelSel ? (parseInt(modelSel.value, 10) || 0) : 0];
        if (!m) { if (estEl) estEl.textContent = "—"; return; }
        var factor = conditions[condIdx] ? parseFloat(conditions[condIdx].factor) : 100;
        if (isNaN(factor)) factor = 100;
        if (estEl) estEl.textContent = money(m.value * factor / 100);
      };

      devBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
          devIdx = parseInt(btn.getAttribute("data-tradein-device"), 10) || 0;
          setActive(devBtns, btn);
          populateModels();
          calc();
        });
      });
      condBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
          condIdx = parseInt(btn.getAttribute("data-tradein-cond"), 10) || 0;
          setActive(condBtns, btn);
          calc();
        });
      });
      if (modelSel) modelSel.addEventListener("change", calc);
    });

    /* ── Wyszukiwarka statusu zlecenia ── */
    document.querySelectorAll("[data-status-form]").forEach(function (form) {
      var section = document.querySelector("[data-status-section]");
      var result = document.querySelector("[data-status-result]");
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        var input = form.querySelector('input[name="order"]');
        var order = input ? input.value.trim() : "";
        if (!order) return;
        var btn = form.querySelector('button[type="submit"]');
        var btnText = btn ? btn.innerHTML : "";
        if (btn) { btn.disabled = true; btn.style.opacity = "0.6"; }

        fetch(form.getAttribute("action") + "?order=" + encodeURIComponent(order), {
          headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" }
        })
          .then(function (r) { return r.json().catch(function () { return { ok: false, message: "Błąd serwera." }; }); })
          .then(function (res) {
            if (section) section.classList.remove("hidden");
            if (!result) return;
            if (res && res.ok) {
              result.innerHTML = res.html;
            } else {
              result.innerHTML = '<div class="max-w-3xl mx-auto text-center rounded-2xl p-8" '
                + 'style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca">'
                + (res && res.message ? res.message.replace(/</g, "&lt;") : "Nie znaleziono zlecenia.") + "</div>";
            }
            if (section && section.scrollIntoView) section.scrollIntoView({ behavior: "smooth", block: "start" });
          })
          .catch(function () {
            if (section) section.classList.remove("hidden");
            if (result) result.innerHTML = '<div class="max-w-3xl mx-auto text-center rounded-2xl p-8" style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca">Problem z połączeniem. Spróbuj ponownie.</div>';
          })
          .finally(function () {
            if (btn) { btn.disabled = false; btn.style.opacity = ""; btn.innerHTML = btnText; }
          });
      });
    });
  });
})();
