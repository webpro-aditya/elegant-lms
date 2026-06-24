/**
 * ============================================================
 *  ELEGANT LMS — Content Protection Shield v3.0
 * ============================================================
 *  Prevents content theft via:
 *   • Print Screen — screen blanks + clipboard wipe (text+image)
 *   • Win+Shift+S — blocked + clipboard wipe
 *   • Win+Shift+T — blocked
 *   • Ctrl+C / Ctrl+X / Ctrl+A / Ctrl+P / Ctrl+S / Ctrl+U
 *   • Right-click context menu
 *   • Text selection & drag-and-drop
 *   • DevTools shortcuts (F12, Ctrl+Shift+I/J/C)
 *   • Browser print dialog
 *   • Clipboard API interception (text + images)
 *   • Screen-recording deterrent (tab blur blanking)
 *
 *  NOTE: OS-level capture cannot be 100% blocked by JS,
 *  but these measures make it extremely difficult.
 * ============================================================
 */
(function () {
    'use strict';

    // ─── CONFIGURATION ──────────────────────────────────────
    var SHOW_WARNING     = true;
    var BLANK_ON_BLUR    = true;           // Blank page when tab/window loses focus
    var CLIPBOARD_WIPE   = true;
    var WARNING_MSG      = 'Content is protected. This action is not allowed.';
    var SCREENSHOT_MSG   = 'Screenshots are disabled on this page.';

    // ─── HELPERS ────────────────────────────────────────────
    function warn(msg) {
        if (!SHOW_WARNING) return;
        if (typeof toastr !== 'undefined' && toastr.warning) {
            toastr.warning(msg, 'Protected', {
                closeButton: true,
                progressBar: true,
                timeOut: 3000,
                positionClass: 'toast-top-right'
            });
        }
    }

    /**
     * Aggressively wipe clipboard — both text AND image data.
     * Uses a tiny 1x1 transparent PNG to overwrite image clipboard.
     */
    function wipeClipboard() {
        if (!CLIPBOARD_WIPE) return;
        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText('').catch(function () {});
            }
        } catch (e) {}

        // Also attempt to overwrite image data in clipboard with a blank 1x1 PNG
        try {
            if (navigator.clipboard && navigator.clipboard.write) {
                // 1x1 transparent PNG as base64
                var b64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQI12NgAAIABQAB' +
                          'Nl7BcQAAAABJRU5ErkJggg==';
                var byteString = atob(b64);
                var ab = new ArrayBuffer(byteString.length);
                var ia = new Uint8Array(ab);
                for (var i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                var blob = new Blob([ab], { type: 'image/png' });
                var item = new ClipboardItem({
                    'image/png': blob,
                    'text/plain': new Blob([''], { type: 'text/plain' })
                });
                navigator.clipboard.write([item]).catch(function () {});
            }
        } catch (e) {}
    }

    /**
     * Burst-wipe: fire multiple clipboard wipes over 2 seconds
     * to defeat the Windows screenshot notification delay.
     */
    function burstWipeClipboard() {
        wipeClipboard();
        setTimeout(wipeClipboard, 50);
        setTimeout(wipeClipboard, 150);
        setTimeout(wipeClipboard, 300);
        setTimeout(wipeClipboard, 500);
        setTimeout(wipeClipboard, 800);
        setTimeout(wipeClipboard, 1200);
        setTimeout(wipeClipboard, 1800);
        setTimeout(wipeClipboard, 2500);
    }


    // ═══════════════════════════════════════════════════════
    //  SCREENSHOT SHIELD — Preemptive Screen Blanking
    // ═══════════════════════════════════════════════════════
    //
    //  Strategy: Keep a transparent overlay always in the DOM.
    //  On PrintScreen detection, instantly make it opaque black.
    //  This happens in the same event loop tick, so the OS
    //  may capture the blanked screen on the next frame.
    //
    var shield = document.createElement('div');
    shield.id = 'lms-screenshot-shield';
    shield.style.cssText = [
        'position:fixed',
        'top:0', 'left:0',
        'width:100vw', 'height:100vh',
        'background:transparent',
        'z-index:2147483647',        // Maximum z-index
        'pointer-events:none',       // Invisible to mouse
        'transition:none',           // No transition delay
        'display:block',
        'opacity:1'
    ].join(';');

    // Insert shield as soon as possible
    function insertShield() {
        if (document.body && !document.getElementById('lms-screenshot-shield')) {
            document.body.appendChild(shield);
        }
    }
    if (document.body) {
        insertShield();
    } else {
        document.addEventListener('DOMContentLoaded', insertShield);
    }

    /**
     * Flash the shield black for a brief moment.
     * The screen goes black → OS captures black → reverts.
     */
    function flashShield() {
        shield.style.background = '#000';
        shield.style.pointerEvents = 'all';

        // Revert after 800ms — enough to poison any screenshot
        setTimeout(function () {
            shield.style.background = 'transparent';
            shield.style.pointerEvents = 'none';
        }, 800);
    }


    // ─── 1. BLOCK KEYBOARD SHORTCUTS ────────────────────────
    document.addEventListener('keydown', function (e) {
        var key = e.key || '';
        var code = e.code || '';
        var keyCode = e.keyCode || e.which || 0;

        // ── Preemptive Shield for Snipping Tool (Win+Shift held) ──
        // The OS freezes the screen before we get the 'S' keydown.
        // But the user has to press Win and Shift first.
        // We blank the screen the instant Win+Shift are held together.
        if (e.metaKey && e.shiftKey) {
            shield.style.background = '#000';
            shield.style.pointerEvents = 'all';
            burstWipeClipboard();
        }

        // ── Print Screen (any variant) ──
        if (code === 'PrintScreen' || keyCode === 44) {
            e.preventDefault();
            e.stopImmediatePropagation();
            flashShield();
            burstWipeClipboard();
            warn(SCREENSHOT_MSG);
            return false;
        }

        // ── Windows + Shift + S  (Snipping Tool / Snip & Sketch) ──
        if (e.metaKey && e.shiftKey && (key === 'S' || key === 's' || keyCode === 83)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            flashShield();
            burstWipeClipboard();
            warn(SCREENSHOT_MSG);
            return false;
        }

        // ── Windows + Shift + T  (specifically requested) ──
        if (e.metaKey && e.shiftKey && (key === 'T' || key === 't' || keyCode === 84)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            flashShield();
            burstWipeClipboard();
            warn(SCREENSHOT_MSG);
            return false;
        }

        // ── Ctrl+Shift+S / Cmd+Shift+S (Save As) ──
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (key === 'S' || key === 's' || keyCode === 83)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            warn(WARNING_MSG);
            return false;
        }

        // ── F12 (DevTools) ──
        if (keyCode === 123) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

        // ── Ctrl/Cmd + Shift + I (DevTools) ──
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && keyCode === 73) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

        // ── Ctrl/Cmd + Shift + J (Console) ──
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && keyCode === 74) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

        // ── Ctrl/Cmd + Shift + C (Inspect Element) ──
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && keyCode === 67) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

        // ── Ctrl/Cmd + U (View Source) ──
        if ((e.ctrlKey || e.metaKey) && !e.shiftKey && keyCode === 85) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

        // ── Ctrl/Cmd + S (Save Page) ──
        if ((e.ctrlKey || e.metaKey) && !e.shiftKey && keyCode === 83) {
            e.preventDefault();
            e.stopImmediatePropagation();
            warn(WARNING_MSG);
            return false;
        }

        // ── Ctrl/Cmd + P (Print) ──
        if ((e.ctrlKey || e.metaKey) && keyCode === 80) {
            e.preventDefault();
            e.stopImmediatePropagation();
            warn(WARNING_MSG);
            return false;
        }

        // ── Ctrl/Cmd + C (Copy) ──
        if ((e.ctrlKey || e.metaKey) && !e.shiftKey && keyCode === 67) {
            if (isInsideFormField(e.target)) {
                return true;
            }
            // Clear any existing selection FIRST
            clearSelection();
            e.preventDefault();
            e.stopImmediatePropagation();
            wipeClipboard();
            warn(WARNING_MSG);
            return false;
        }

        // ── Ctrl/Cmd + X (Cut) ──
        if ((e.ctrlKey || e.metaKey) && keyCode === 88) {
            if (isInsideFormField(e.target)) {
                return true;
            }
            clearSelection();
            e.preventDefault();
            e.stopImmediatePropagation();
            wipeClipboard();
            warn(WARNING_MSG);
            return false;
        }

        // ── Ctrl/Cmd + A (Select All) — block outside form fields ──
        if ((e.ctrlKey || e.metaKey) && keyCode === 65) {
            if (isInsideFormField(e.target)) {
                return true;
            }
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

    }, true);

    // ─── HELPER: Check if element is a real form field or PDF area ───────
    function isInsideFormField(el) {
        if (!el) return false;
        var tag = (el.tagName || '').toLowerCase();
        // Only allow actual input/textarea elements
        if (tag === 'input' || tag === 'textarea') return true;
        // Check for Summernote editor or PDF containers
        var p = el;
        for (var i = 0; i < 10 && p; i++) {
            if (p.classList && p.classList.contains('note-editable')) return true;
            if (p.id === 'pdfOuterContainer') return true;
            if (p.id === 'pdfCommentPopup') return true;
            if (p.classList && p.classList.contains('pdf-text-layer')) return true;
            if (p.classList && p.classList.contains('pdftoolbar')) return true;
            if (p.classList && p.classList.contains('pdf-search-wrap')) return true;
            p = p.parentElement;
        }
        return false;
    }

    // ─── HELPER: Force-clear any text selection ──────────────
    function clearSelection() {
        try {
            var sel = window.getSelection();
            if (sel) {
                sel.removeAllRanges();
                if (sel.empty) sel.empty();
            }
        } catch(e) {}
        try {
            document.selection && document.selection.empty();
        } catch(e) {}
    }

    // ─── 2. BLOCK KEYUP for PrintScreen & Preemptive Shield ─
    document.addEventListener('keyup', function (e) {
        var code = e.code || '';
        var keyCode = e.keyCode || e.which || 0;

        // Un-blank screen if Win or Shift is released
        if (!e.metaKey || !e.shiftKey) {
            // Only un-blank if it wasn't a flashShield (which has its own timeout)
            // But doing it here is fine, it just restores visibility when they let go
            shield.style.background = 'transparent';
            shield.style.pointerEvents = 'none';
        }

        if (code === 'PrintScreen' || keyCode === 44) {
            e.preventDefault();
            e.stopImmediatePropagation();
            flashShield();
            burstWipeClipboard();
            warn(SCREENSHOT_MSG);
            return false;
        }
    }, true);

    // ─── 3. BLOCK COPY / CUT EVENTS ────────────────────────
    // Multiple layers: addEventListener + document.oncopy + execCommand override

    document.addEventListener('copy', function (e) {
        if (isInsideFormField(e.target)) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        clearSelection();
        if (e.clipboardData) {
            e.clipboardData.setData('text/plain', '');
            e.clipboardData.setData('text/html', '');
        }
        wipeClipboard();
        warn(WARNING_MSG);
        return false;
    }, true);

    document.addEventListener('cut', function (e) {
        if (isInsideFormField(e.target)) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        clearSelection();
        wipeClipboard();
        warn(WARNING_MSG);
        return false;
    }, true);

    // ─── 3b. NUCLEAR FALLBACK: document.oncopy ──────────────
    document.oncopy = function (e) {
        if (isInsideFormField(e.target)) return true;
        e.preventDefault();
        clearSelection();
        wipeClipboard();
        return false;
    };
    document.oncut = function (e) {
        if (isInsideFormField(e.target)) return true;
        e.preventDefault();
        clearSelection();
        return false;
    };

    // ─── 3c. Override document.execCommand('copy') ──────────
    var origExecCommand = document.execCommand.bind(document);
    document.execCommand = function (cmd) {
        if (cmd === 'copy' || cmd === 'cut') {
            clearSelection();
            wipeClipboard();
            return false;
        }
        return origExecCommand.apply(document, arguments);
    };

    // ─── 4. BLOCK RIGHT-CLICK CONTEXT MENU ──────────────────
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
        warn(WARNING_MSG);
        return false;
    }, true);

    // ─── 4b. BLOCK TEXT SELECTION at source ──────────────────
    // Prevents any text from being selected (except in form fields and PDF areas)
    document.addEventListener('selectstart', function (e) {
        if (isInsideFormField(e.target)) {
            return true;
        }
        // Also allow selection inside PDF text layer for search highlighting
        if (isInsidePdfArea(e.target)) {
            return true;
        }
        e.preventDefault();
        return false;
    }, true);

    // ─── 4c. BLOCK PASTE (prevent paste into external apps) ─
    document.addEventListener('paste', function (e) {
        if (isInsideFormField(e.target)) {
            return true;
        }
        e.preventDefault();
        return false;
    }, true);

    // ─── 4d. FORCE user-select:none via JavaScript ──────────
    // Applies inline styles that can't be overridden by external CSS
    function enforceNoSelect() {
        var body = document.body;
        if (!body) return;
        body.style.webkitUserSelect = 'none';
        body.style.MozUserSelect = 'none';
        body.style.msUserSelect = 'none';
        body.style.userSelect = 'none';
        body.style.webkitTouchCallout = 'none';
    }
    enforceNoSelect();
    document.addEventListener('DOMContentLoaded', enforceNoSelect);

    // ─── HELPER: Check if element is inside a PDF viewer area ───────
    function isInsidePdfArea(el) {
        if (!el) return false;
        var p = (el.nodeType === 1) ? el : el.parentElement;
        for (var i = 0; i < 12 && p; i++) {
            if (p.id === 'pdfOuterContainer') return true;
            if (p.id === 'pdfCommentPopup') return true;
            if (p.classList && p.classList.contains('pdf-text-layer')) return true;
            if (p.classList && p.classList.contains('pdftoolbar')) return true;
            if (p.classList && p.classList.contains('pdf-search-wrap')) return true;
            if (p.classList && p.classList.contains('pdftoolbar-slide-container')) return true;
            p = p.parentElement;
        }
        return false;
    }

    // ─── 4e. CONTINUOUSLY CLEAR any accidental selection ────
    // If something manages to get selected, nuke it immediately
    document.addEventListener('selectionchange', function () {
        var sel = window.getSelection ? window.getSelection() : null;
        if (!sel || sel.rangeCount === 0) return;

        // Check if selection is inside a form field — allow those
        var anchor = sel.anchorNode;
        if (anchor) {
            var parent = anchor.nodeType === 1 ? anchor : anchor.parentElement;
            if (parent) {
                var tag = (parent.tagName || '').toLowerCase();
                if (tag === 'input' || tag === 'textarea' || parent.isContentEditable) return;
                // Allow selections inside PDF text layer (needed for search)
                if (isInsidePdfArea(parent)) return;
                // Walk up a few levels to check for editable containers
                var p = parent;
                for (var i = 0; i < 5 && p; i++) {
                    if (p.isContentEditable) return;
                    var pTag = (p.tagName || '').toLowerCase();
                    if (pTag === 'input' || pTag === 'textarea') return;
                    if (p.classList && (p.classList.contains('note-editable') || p.classList.contains('note-editor'))) return;
                    p = p.parentElement;
                }
            }
        }

        // Clear the selection
        try {
            sel.removeAllRanges();
        } catch (e) {
            try { sel.empty(); } catch (e2) {}
        }
    });

    // ─── 5. BLOCK DRAG & DROP ───────────────────────────────
    document.addEventListener('dragstart', function (e) {
        e.preventDefault();
        return false;
    }, true);

    document.addEventListener('drop', function (e) {
        e.preventDefault();
        return false;
    }, true);

    // ─── 6. VISIBILITY / FOCUS CHANGE — Instant screen blank ─
    // When the browser loses focus (e.g. Win+Shift+S opens
    // Snipping Tool), the page goes black immediately.
    // The overlay is PRE-RENDERED in the DOM (hidden) so
    // showing it has zero DOM-insertion delay.
    if (BLANK_ON_BLUR) {
        var blurOverlay = document.createElement('div');
        blurOverlay.id = 'content-protection-blur-overlay';
        blurOverlay.style.cssText = [
            'position:fixed',
            'top:0', 'left:0',
            'width:100vw', 'height:100vh',
            'background:#000',
            'z-index:2147483646',
            'display:flex',
            'align-items:center',
            'justify-content:center',
            'color:#fff',
            'font-size:1.2rem',
            'font-family:sans-serif',
            'pointer-events:all',
            'opacity:0',
            'visibility:hidden',
            'transition:none'           // No transition — instant
        ].join(';');
        blurOverlay.innerHTML = '<p>Content hidden — return to this tab to continue.</p>';

        function insertBlurOverlay() {
            if (document.body && !document.getElementById('content-protection-blur-overlay')) {
                document.body.appendChild(blurOverlay);
            }
        }
        if (document.body) insertBlurOverlay();
        else document.addEventListener('DOMContentLoaded', insertBlurOverlay);

        function showBlurOverlay() {
            blurOverlay.style.opacity = '1';
            blurOverlay.style.visibility = 'visible';
            // Also wipe clipboard whenever focus is lost
            burstWipeClipboard();
        }

        function hideBlurOverlay() {
            blurOverlay.style.opacity = '0';
            blurOverlay.style.visibility = 'hidden';
        }

        // visibilitychange — fires when tab is hidden/switched
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                showBlurOverlay();
            } else {
                hideBlurOverlay();
            }
        });

        // blur/focus — fires when another app steals focus
        // (e.g. Snipping Tool, Alt+Tab, clicking another window)
        window.addEventListener('blur', function () {
            showBlurOverlay();
        });

        window.addEventListener('focus', function () {
            hideBlurOverlay();
        });
    }

    // ─── 7. PERIODIC CLIPBOARD WIPE ─────────────────────────
    // Wipes clipboard every 1.5 seconds to prevent delayed paste
    if (CLIPBOARD_WIPE) {
        setInterval(function () {
            try {
                if (document.hasFocus()) {
                    var active = document.activeElement;
                    var tag = (active && active.tagName || '').toLowerCase();
                    if (tag !== 'input' && tag !== 'textarea' && !(active && active.isContentEditable)) {
                        wipeClipboard();
                    }
                }
            } catch (e) {}
        }, 1500);
    }

    // ─── 8. BLOCK PRINT VIA window.print() OVERRIDE ─────────
    window.print = function () {
        warn('Printing is disabled.');
        return false;
    };

    // ─── 9. BEFORE PRINT EVENT — blank the page ─────────────
    window.addEventListener('beforeprint', function () {
        document.body.classList.add('content-protection-printing');
    });

    window.addEventListener('afterprint', function () {
        document.body.classList.remove('content-protection-printing');
    });

    // ─── 10. MUTATION OBSERVER — Resist DevTools removal ────
    var protectedBody = document.body;
    if (protectedBody && typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.type === 'attributes' && m.attributeName === 'style') {
                    protectedBody.style.webkitUserSelect = 'none';
                    protectedBody.style.userSelect = 'none';
                }
            });
        });
        observer.observe(protectedBody, { attributes: true, attributeFilter: ['style'] });
    }

    // ─── 11. INTERCEPT Clipboard API reads ──────────────────
    // Prevent scripts (e.g. browser extensions) from reading clipboard
    if (navigator.clipboard && navigator.clipboard.readText) {
        var originalReadText = navigator.clipboard.readText.bind(navigator.clipboard);
        navigator.clipboard.readText = function () {
            return Promise.resolve('');
        };
    }
    if (navigator.clipboard && navigator.clipboard.read) {
        var originalRead = navigator.clipboard.read.bind(navigator.clipboard);
        navigator.clipboard.read = function () {
            return Promise.resolve([]);
        };
    }

    // ─── 12. CONSOLE WARNING ────────────────────────────────
    if (typeof console !== 'undefined') {
        console.log(
            '%c⚠ CONTENT PROTECTED',
            'color:#ff3333;font-size:24px;font-weight:bold;'
        );
        console.log(
            '%cAttempting to copy, screenshot, or extract content from this page is prohibited and may result in account suspension.',
            'color:#ff6600;font-size:14px;'
        );
    }

})();
