<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    /* ── Page content (your existing course page) ── */
    .page-content {
        padding: 24px 20px;
        max-width: 100%;
    }

    .page-content h2 {
        font-size: 17px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 16px;
    }

    .page-content ul {
        list-style: none;
    }

    .page-content ul li {
        font-size: 13px;
        color: #333;
        line-height: 2;
    }

    .page-content ul li a {
        color: #2563eb;
        font-size: 12px;
        text-decoration: none;
    }

    /* ══════════════════════════════════════════
     FLOATING NAV SYSTEM
  ══════════════════════════════════════════ */

    /* Main FAB wrapper — anchored left center */
    #fab-root {
        position: fixed;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        z-index: 500;
    }

    /* Sub-icon buttons container */
    #sub-icons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
        opacity: 0;
        transform: translateX(-10px);
        pointer-events: none;
        transition: opacity 0.22s ease, transform 0.22s ease;
    }

    #sub-icons.visible {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }

    /* Individual sub-button */
    .sub-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.22);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        position: relative;
        text-decoration: none;
    }

    .sub-btn:hover {
        transform: scale(1.12);
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.3);
    }

    .sub-btn:active {
        transform: scale(0.96);
    }

    #btn-dashboard {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    #btn-chapters {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
    }

    /* Tooltip labels */
    .sub-btn .tooltip {
        position: absolute;
        left: 50px;
        white-space: nowrap;
        background: #1a1a2e;
        color: #fff;
        font-size: 11px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 5px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.15s ease;
    }

    .sub-btn .tooltip::before {
        content: '';
        position: absolute;
        left: -5px;
        top: 50%;
        transform: translateY(-50%);
        border: 5px solid transparent;
        border-right-color: #1a1a2e;
        border-left: none;
    }

    .sub-btn:hover .tooltip {
        opacity: 1;
    }

    /* Main FAB button */
    #fab-main {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2.5px solid rgba(255, 255, 255, 0.25);
        background: linear-gradient(135deg, #1e3a8a, #1e40af);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(30, 58, 138, 0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #fab-main:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 22px rgba(30, 58, 138, 0.5);
    }

    #fab-main:active {
        transform: scale(0.96);
    }

    #fab-main svg {
        transition: transform 0.3s ease;
    }

    #fab-main.open svg {
        transform: rotate(45deg);
    }

    /* ── Overlay ── */
    #menu-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0);
        pointer-events: none;
        z-index: 590;
        transition: background 0.28s ease;
    }

    #menu-overlay.active {
        background: rgba(0, 0, 0, 0.4);
        pointer-events: auto;
    }

    /* ── Chapters slide-in panel ── */
    #chapters-menu {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        width: 260px;
        background: #fff;
        z-index: 600;
        transform: translateX(-100%);
        transition: transform 0.3s cubic-bezier(.4, 0, .2, 1);
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.18);
    }

    #chapters-menu.open {
        transform: translateX(0);
    }

    /* Panel header */
    .panel-header {
        background: #1a1a2e;
        padding: 18px 16px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .panel-header h3 {
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .panel-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: rgba(255, 255, 255, 0.8);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 14px;
        line-height: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
    }

    .panel-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Panel scroll area */
    .panel-body {
        overflow-y: auto;
        flex: 1;
        padding: 8px 0;
    }

    .panel-body::-webkit-scrollbar {
        width: 4px;
    }

    .panel-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .panel-body::-webkit-scrollbar-thumb {
        background: #c5c5d0;
        border-radius: 2px;
    }

    /* Chapter accordion item */
    .chapter-item {
        border-bottom: 1px solid #f0f0f5;
    }

    .chapter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 16px;
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: background 0.15s, border-left-color 0.15s;
        user-select: none;
    }

    .chapter-header:hover {
        background: #f5f5fb;
    }

    .chapter-header.active {
        border-left-color: #1e40af;
        background: #eef2ff;
    }

    .chapter-header .ch-title {
        font-size: 12px;
        font-weight: 600;
        color: #1a1a2e;
    }

    .chapter-header .ch-arrow {
        font-size: 9px;
        color: #888;
        transition: transform 0.22s ease;
    }

    .chapter-header.active .ch-arrow {
        transform: rotate(90deg);
    }

    /* Sub-links */
    .chapter-links {
        display: none;
        background: #f8f8fc;
        padding: 4px 0;
    }

    .chapter-links.open {
        display: block;
    }

    .chapter-links a {
        display: block;
        padding: 8px 16px 8px 28px;
        font-size: 11.5px;
        color: #374151;
        text-decoration: none;
        border-left: 2px solid transparent;
        transition: background 0.12s, color 0.12s, border-left-color 0.12s;
    }

    .chapter-links a:hover {
        background: #e8ecff;
        color: #1e40af;
        border-left-color: #7c3aed;
    }

    .chapter-links a.current {
        color: #1e40af;
        font-weight: 600;
        border-left-color: #1e40af;
        background: #eef2ff;
    }

    /* Progress badge */
    .ch-badge {
        font-size: 9px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
        margin-left: 6px;
    }

    .ch-badge.done {
        background: #d1fae5;
        color: #065f46;
    }

    .ch-badge.active {
        background: #e0e7ff;
        color: #3730a3;
    }

    @media only screen and (min-width: 767px) {
        #fab-root {
            display: none;
        }
    }
</style>


<!-- ══════════════════════════════
     FLOATING NAV SYSTEM
══════════════════════════════ -->

<!-- Backdrop overlay -->
<div id="menu-overlay"></div>

<!-- Chapters slide-in panel -->
<div id="chapters-menu" role="dialog" aria-label="Chapters">
    <div class="panel-header">
        <h3>📚 Chapters</h3>
        <button class="panel-close" id="close-menu" aria-label="Close">✕</button>
    </div>
    <div class="panel-body">

        @php $i = 1; @endphp

        @foreach ($chapters as $k => $chapter)

        <div class="chapter-item">

            <!-- Chapter Header -->
            <div class="chapter-header {{ $k == 0 ? 'active' : '' }}" data-ch="{{ $k }}">
                <span class="ch-title">
                    {{ $k+1 }}. {{ $chapter->name }}

                    @php
                    $completedLessons = $chapter->lessons->where('completed.status', 1)->count();
                    $totalLessons = $chapter->lessons->count();
                    @endphp

                    @if($completedLessons == $totalLessons)
                    <span class="ch-badge done">Done</span>
                    @elseif($completedLessons > 0)
                    <span class="ch-badge active">Active</span>
                    @endif

                </span>
                <span class="ch-arrow">▶</span>
            </div>

            <!-- Lessons -->
            <div class="chapter-links {{ $k == 0 ? 'open' : '' }}" data-ch="{{ $k }}">

                @foreach ($lessons as $lesson)

                @if ($lesson->chapter_id == $chapter->id)

                <a href="javascript:void(0)"
                    onclick="goFullScreen({{ $course->id }}, {{ $lesson->id }})"
                    class="{{ request()->route('lesson_id') == $lesson->id ? 'current' : '' }}">

                    {{ $i }}. {{ $lesson->name }}

                </a>

                @php $i++; @endphp

                @endif

                @endforeach

            </div>

        </div>

        @endforeach

    </div>
</div>

<!-- FAB system -->
<div id="fab-root">

    <!-- Sub-icons (revealed on FAB click) -->
    <div id="sub-icons">

        <!-- Dashboard -->
        <a href="{{ route('studentDashboard') }}" class="sub-btn" id="btn-dashboard" title="Dashboard" aria-label="Go to Dashboard">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
            </svg>
            <span class="tooltip">Dashboard</span>
        </a>

        <!-- Chapters -->
        <button class="sub-btn" id="btn-chapters" aria-label="Open Chapters">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                <line x1="9" y1="9" x2="15" y2="9" />
                <line x1="9" y1="13" x2="13" y2="13" />
            </svg>
            <span class="tooltip">Chapters</span>
        </button>

    </div>

    <!-- Main FAB trigger -->
    <button id="fab-main" aria-label="Toggle navigation" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <!-- plus / menu icon -->
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
    </button>

</div>

<script>
    (function() {
        const fab = document.getElementById('fab-main');
        const subIcons = document.getElementById('sub-icons');
        const chapMenu = document.getElementById('chapters-menu');
        const overlay = document.getElementById('menu-overlay');
        const closeBtn = document.getElementById('close-menu');
        const btnCh = document.getElementById('btn-chapters');

        let fabOpen = false;
        let menuOpen = false;

        /* ── FAB toggle ── */
        fab.addEventListener('click', () => {
            fabOpen = !fabOpen;
            subIcons.classList.toggle('visible', fabOpen);
            fab.classList.toggle('open', fabOpen);
            fab.setAttribute('aria-expanded', fabOpen);
            if (!fabOpen && menuOpen) closeChapters();
        });

        /* ── Open chapters ── */
        btnCh.addEventListener('click', () => {
            openChapters();
        });

        function openChapters() {
            menuOpen = true;
            chapMenu.classList.add('open');
            overlay.classList.add('active');
        }

        function closeChapters() {
            menuOpen = false;
            chapMenu.classList.remove('open');
            overlay.classList.remove('active');
        }

        closeBtn.addEventListener('click', closeChapters);
        overlay.addEventListener('click', () => {
            closeChapters();
            fabOpen = false;
            subIcons.classList.remove('visible');
            fab.classList.remove('open');
            fab.setAttribute('aria-expanded', 'false');
        });

        /* Escape key */
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeChapters();
                fabOpen = false;
                subIcons.classList.remove('visible');
                fab.classList.remove('open');
                fab.setAttribute('aria-expanded', 'false');
            }
        });

        /* ── Chapter accordion ── */
        document.querySelectorAll('.chapter-header').forEach(header => {
            header.addEventListener('click', () => {
                const ch = header.dataset.ch;
                const links = document.querySelector(`.chapter-links[data-ch="${ch}"]`);
                const isOpen = links.classList.contains('open');

                /* Close all */
                document.querySelectorAll('.chapter-header').forEach(h => h.classList.remove('active'));
                document.querySelectorAll('.chapter-links').forEach(l => l.classList.remove('open'));

                /* Open clicked (toggle) */
                if (!isOpen) {
                    header.classList.add('active');
                    links.classList.add('open');
                }
            });
        });
    })();
</script>