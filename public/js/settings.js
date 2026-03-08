/**
 * settings.js — NexAdmin
 * Place at: public/js/settings.js
 *
 * Profile    → POST /admin/settings/profile   (users table: name, email)
 * Store      → POST /admin/settings/store
 * Notifs     → POST /admin/settings/notifications
 * Security   → POST /admin/settings/security  (password change)
 * Shipping   → POST /admin/settings/shipping
 * Appearance → POST /admin/settings/appearance
 */

(function () {
    'use strict';

    const $ = id => document.getElementById(id);

    /* ══════════════════════════════════════════
     |  TOAST
    ══════════════════════════════════════════ */
    function toast(msg, type = 'success') {
        const wrap = $('toastContainer');
        const map  = {
            success: { bg:'#e8f8ee', border:'#1f9c4a', text:'#1f9c4a', icon:'bi-check-circle-fill' },
            error:   { bg:'#fee8eb', border:'#dc3545', text:'#dc3545', icon:'bi-x-circle-fill' },
            info:    { bg:'#e8f4ff', border:'#1a7cd4', text:'#1a7cd4', icon:'bi-info-circle-fill' },
        };
        const c = map[type] || map.info;
        const el = document.createElement('div');
        el.style.cssText = `background:${c.bg};border:1.5px solid ${c.border};color:${c.text};
            padding:11px 16px;border-radius:11px;font-size:13px;font-weight:600;
            display:flex;align-items:center;gap:9px;
            box-shadow:0 4px 20px rgba(0,0,0,.1);pointer-events:all;
            animation:_fadeUp .25s ease;min-width:240px;max-width:340px;`;
        el.innerHTML = `<i class="bi ${c.icon}" style="font-size:16px;flex-shrink:0;"></i><span>${msg}</span>`;
        wrap.appendChild(el);
        setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .3s'; setTimeout(()=>el.remove(),300); }, 3500);
    }

    const _kf = document.createElement('style');
    _kf.textContent = `@keyframes _fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}`;
    document.head.appendChild(_kf);

    /* ══════════════════════════════════════════
     |  AJAX
    ══════════════════════════════════════════ */
    function send(url, body, isFormData = false) {
        const headers = { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.SETTINGS.csrf };
        if (!isFormData) headers['Content-Type'] = 'application/json';
        return fetch(url, {
            method: 'POST',
            headers,
            body: isFormData ? body : JSON.stringify(body),
        }).then(async r => {
            const data = await r.json();
            if (!r.ok) throw data;
            return data;
        });
    }

    /* ══════════════════════════════════════════
     |  SPINNER
    ══════════════════════════════════════════ */
    function spin(btnId, textId, spinnerId, on) {
        const btn = $(btnId), txt = $(textId), sp = $(spinnerId);
        if (btn) btn.disabled = on;
        if (txt) txt.style.display = on ? 'none'         : 'inline';
        if (sp)  sp.style.display  = on ? 'inline-block' : 'none';
    }

    /* ══════════════════════════════════════════
     |  FIELD ERRORS
    ══════════════════════════════════════════ */
    function showErrors(errors = {}) {
        Object.entries(errors).forEach(([field, msgs]) => {
            const el  = $(`err_${field}`);
            const inp = document.querySelector(`[name="${field}"]`);
            if (el)  el.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
            if (inp) inp.classList.add('is-invalid');
        });
    }

    function clearErrors() {
        document.querySelectorAll('.field-err').forEach(el => el.textContent = '');
        document.querySelectorAll('.inp.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    /* ══════════════════════════════════════════
     |  TABS
    ══════════════════════════════════════════ */
    function initTabs() {
        document.querySelectorAll('.settings-tab-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                document.querySelectorAll('.settings-tab-link').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                document.querySelectorAll('.settings-panel').forEach(p => p.style.display = 'none');
                const panel = $(`panel-${link.dataset.tab}`);
                if (panel) panel.style.display = 'block';
                clearErrors();
            });
        });
    }

    /* ══════════════════════════════════════════
     |  PROFILE  (users table: name + email only)
    ══════════════════════════════════════════ */
    function initProfile() {
        const btn  = $('saveProfileBtn');
        const form = $('profileForm');
        if (!btn || !form) return;

        btn.addEventListener('click', () => {
            clearErrors();
            spin('saveProfileBtn', 'saveProfileText', 'saveProfileSpinner', true);

            const payload = Object.fromEntries(new FormData(form));

            send(window.SETTINGS.routes.profile, payload)
                .then(res => {
                    toast(res.message, 'success');
                    // Update sidebar display name if present
                    const dn = $('displayName');
                    if (dn && res.name) dn.textContent = res.name;
                    const ai = $('avatarInitials');
                    if (ai && res.initials) ai.textContent = res.initials;
                })
                .catch(err => {
                    if (err.errors) showErrors(err.errors);
                    toast(err.message || 'Failed to save profile.', 'error');
                })
                .finally(() => spin('saveProfileBtn', 'saveProfileText', 'saveProfileSpinner', false));
        });
    }

    /* ══════════════════════════════════════════
     |  STORE
    ══════════════════════════════════════════ */
    function initStore() {
        const btn  = $('saveStoreBtn');
        const form = $('storeForm');
        if (!btn || !form) return;

        btn.addEventListener('click', () => {
            clearErrors();
            spin('saveStoreBtn', 'saveStoreText', 'saveStoreSpinner', true);

            send(window.SETTINGS.routes.store, Object.fromEntries(new FormData(form)))
                .then(res => toast(res.message, 'success'))
                .catch(err => {
                    if (err.errors) showErrors(err.errors);
                    toast(err.message || 'Failed to save store settings.', 'error');
                })
                .finally(() => spin('saveStoreBtn', 'saveStoreText', 'saveStoreSpinner', false));
        });
    }

    /* ══════════════════════════════════════════
     |  NOTIFICATIONS
    ══════════════════════════════════════════ */
    function initNotifications() {
        const btn  = $('saveNotifBtn');
        const form = $('notifForm');
        if (!btn || !form) return;

        btn.addEventListener('click', () => {
            spin('saveNotifBtn', 'saveNotifText', 'saveNotifSpinner', true);

            // Collect checkboxes manually — unchecked won't appear in FormData
            const payload = {};
            form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                payload[cb.name] = cb.checked ? 1 : 0;
            });

            send(window.SETTINGS.routes.notifications, payload)
                .then(res => toast(res.message, 'success'))
                .catch(err => toast(err.message || 'Failed to save.', 'error'))
                .finally(() => spin('saveNotifBtn', 'saveNotifText', 'saveNotifSpinner', false));
        });
    }

    /* ══════════════════════════════════════════
     |  SECURITY
    ══════════════════════════════════════════ */
    function initSecurity() {
        const btn  = $('saveSecurityBtn');
        const form = $('securityForm');
        if (!btn || !form) return;

        btn.addEventListener('click', () => {
            clearErrors();
            spin('saveSecurityBtn', 'saveSecurityText', 'saveSecuritySpinner', true);

            send(window.SETTINGS.routes.security, Object.fromEntries(new FormData(form)))
                .then(res => {
                    toast(res.message, 'success');
                    form.reset();
                    const bar   = $('pwdStrengthBar');
                    const label = $('pwdStrengthLabel');
                    if (bar)   { bar.style.width = '0'; bar.style.background = ''; }
                    if (label) label.textContent = '';
                })
                .catch(err => {
                    if (err.errors) showErrors(err.errors);
                    toast(err.message || 'Failed to update password.', 'error');
                })
                .finally(() => spin('saveSecurityBtn', 'saveSecurityText', 'saveSecuritySpinner', false));
        });
    }

    /* ══════════════════════════════════════════
     |  SHIPPING
    ══════════════════════════════════════════ */
    function initShipping() {
        const btn  = $('saveShippingBtn');
        const form = $('shippingForm');
        if (!btn || !form) return;

        btn.addEventListener('click', () => {
            clearErrors();
            spin('saveShippingBtn', 'saveShippingText', 'saveShippingSpinner', true);

            send(window.SETTINGS.routes.shipping, Object.fromEntries(new FormData(form)))
                .then(res => toast(res.message, 'success'))
                .catch(err => {
                    if (err.errors) showErrors(err.errors);
                    toast(err.message || 'Failed to save shipping.', 'error');
                })
                .finally(() => spin('saveShippingBtn', 'saveShippingText', 'saveShippingSpinner', false));
        });
    }

    /* ══════════════════════════════════════════
     |  APPEARANCE
    ══════════════════════════════════════════ */
    function initAppearance() {
        const btn       = $('saveAppearanceBtn');
        const form      = $('appearanceForm');
        const logoInput = $('logoInput');
        if (!btn || !form) return;

        /* Sync picker ↔ text input ↔ preview bar */
        function syncColor(pickerId, textId, barId) {
            const picker = $(pickerId);
            const text   = $(textId);
            const bar    = $(barId);
            if (!picker || !text) return;

            picker.addEventListener('input', () => {
                text.value = picker.value;
                if (bar) bar.style.background = picker.value;
            });
            text.addEventListener('input', () => {
                if (/^#[0-9a-fA-F]{6}$/.test(text.value)) {
                    picker.value = text.value;
                    if (bar) bar.style.background = text.value;
                }
            });
        }

        syncColor('primaryPicker',   'primaryText',   'primaryBar');
        syncColor('secondaryPicker', 'secondaryText', 'secondaryBar');

        /* Logo preview */
        if (logoInput) {
            logoInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const ph = $('logoPlaceholder');
                    if (ph) ph.style.display = 'none';
                    let img = $('logoPreview');
                    if (!img) {
                        img = document.createElement('img');
                        img.id = 'logoPreview';
                        img.style.cssText = 'max-height:80px;object-fit:contain;';
                        logoInput.closest('.upload-zone')?.prepend(img);
                    }
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        btn.addEventListener('click', () => {
            clearErrors();
            spin('saveAppearanceBtn', 'saveAppearanceText', 'saveAppearanceSpinner', true);

            const fd = new FormData(form);
            if (logoInput?.files[0]) fd.set('logo', logoInput.files[0]);

            send(window.SETTINGS.routes.appearance, fd, true)
                .then(res => {
                    toast(res.message, 'success');
                    // Apply live to CSS variables
                    const p = $('primaryText')?.value;
                    const s = $('secondaryText')?.value;
                    if (p) document.documentElement.style.setProperty('--primary',   p);
                    if (s) document.documentElement.style.setProperty('--secondary', s);
                })
                .catch(err => {
                    if (err.errors) showErrors(err.errors);
                    toast(err.message || 'Failed to save appearance.', 'error');
                })
                .finally(() => spin('saveAppearanceBtn', 'saveAppearanceText', 'saveAppearanceSpinner', false));
        });
    }

    /* ══════════════════════════════════════════
     |  GLOBAL — called from blade onclick attrs
    ══════════════════════════════════════════ */
    window.togglePwd = function (inputId, btn) {
        const inp = $(inputId);
        if (!inp) return;
        const show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        btn.innerHTML = `<i class="bi ${show ? 'bi-eye-slash' : 'bi-eye'}"></i>`;
    };

    window.checkPwdStrength = function (val) {
        const bar   = $('pwdStrengthBar');
        const label = $('pwdStrengthLabel');
        if (!bar || !label) return;

        let score = 0;
        if (val.length >= 8)           score++;
        if (/[A-Z]/.test(val))         score++;
        if (/[0-9]/.test(val))         score++;
        if (/[^A-Za-z0-9]/.test(val))  score++;

        const levels = [
            { w:'0%',   bg:'transparent', txt:'' },
            { w:'25%',  bg:'#dc3545',     txt:'Weak' },
            { w:'50%',  bg:'#d97706',     txt:'Fair' },
            { w:'75%',  bg:'#1a7cd4',     txt:'Good' },
            { w:'100%', bg:'#1f9c4a',     txt:'Strong' },
        ];
        const lv = levels[score];
        bar.style.width      = lv.w;
        bar.style.background = lv.bg;
        label.textContent    = lv.txt;
        label.style.color    = lv.bg;
    };

    /* ══════════════════════════════════════════
     |  INIT
    ══════════════════════════════════════════ */
    function init() {
        initTabs();
        initProfile();
        initStore();
        initNotifications();
        initSecurity();
        initShipping();
        initAppearance();
    }

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', init)
        : init();

})();