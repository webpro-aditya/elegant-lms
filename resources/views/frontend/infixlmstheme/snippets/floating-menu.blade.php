<style>
/* RESET */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* ══════════════════════════════════════════
   FLOATING NAV SYSTEM (UPDATED DESIGN)
══════════════════════════════════════════ */

/* FAB ROOT */
#fab-root {
    position: fixed;
    left: 6px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    z-index: 500;
}

/* SUB BUTTON CONTAINER */
#sub-icons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: flex-start;
    opacity: 0;
    transform: translateX(-10px);
    pointer-events: none;
    transition: all 0.25s ease;
}

#sub-icons.visible {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
}

/* ───────────── CARD STYLE BUTTON ───────────── */
.sub-btn {
    width: 220px;
    padding: 12px;
    border-radius: 18px;

    display: flex;
    align-items: center;
    gap: 12px;

    background: #f3f4f6;
    color: #1f2937;

    box-shadow:
        6px 6px 16px rgba(0,0,0,0.12),
        -4px -4px 10px rgba(255,255,255,0.8);

    transition: all 0.25s ease;
    text-decoration: none;
}

/* HOVER */
.sub-btn:hover {
    transform: translateY(-2px);
    box-shadow:
        8px 8px 18px rgba(0,0,0,0.15),
        -4px -4px 10px rgba(255,255,255,0.9);
}

/* ACTIVE */
.sub-btn:active {
    transform: scale(0.97);
}

/* ICON BOX */
.sub-btn .icon-box {
    width: 42px;
    height: 42px;
    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #fff;
    font-size: 18px;

    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

/* ICON COLORS */
.icon-dashboard {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.icon-chapters {
    background: linear-gradient(135deg, #7c3aed, #5b21b6);
}

/* TEXT */
.sub-btn .text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.sub-btn .label {
    font-size: 10px;
    color: #6b7280;
    letter-spacing: 0.5px;
}

.sub-btn .title {
    font-size: 13.5px;
    font-weight: 700;
    color: #111827;
}

/* FAB BUTTON */
/* PREMIUM FAB BUTTON */
#fab-main {
    width: 60px;
    height: 60px;
    border-radius: 18px; /* rounded square */

    position: relative;
    overflow: hidden;

    background: linear-gradient(135deg, #1e293b, #1e40af);

    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;

    /* soft shadow */
    box-shadow:
        8px 8px 20px rgba(0,0,0,0.35),
        -4px -4px 12px rgba(255,255,255,0.08);

    transition: all 0.25s ease;
}

/* GLOW EFFECT */
#fab-main::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;

    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.25), transparent 60%);
    opacity: 0.6;
}

/* HOVER */
#fab-main:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow:
        10px 10px 24px rgba(0,0,0,0.4),
        -4px -4px 12px rgba(255,255,255,0.1);
}

/* CLICK */
#fab-main:active {
    transform: scale(0.95);
}

#fab-main::after {
    content: '';
    position: absolute;
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(59,130,246,0.35), transparent 70%);
    top: -20%;
    left: -20%;
    z-index: 0;
}

#fab-main svg {
    position: relative;
    z-index: 1;
}

/* ICON STYLE */
#fab-main svg {
    width: 24px;
    height: 24px;
    stroke: #fff;
    transition: transform 0.3s ease;
}

/* ROTATION WHEN OPEN */
#fab-main.open svg {
    transform: rotate(45deg);
}
/* OVERLAY */
#menu-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0);
    pointer-events: none;
    z-index: 590;
    transition: background 0.3s ease;
}

#menu-overlay.active {
    background: rgba(0,0,0,0.4);
    pointer-events: auto;
}

/* ───────────── CHAPTER PANEL (UNCHANGED) ───────────── */
 /* HEADER */
 /* FIX: CHAPTER MENU VISIBILITY */
#chapters-menu {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: 260px;
    background: #fff;
    z-index: 1000;

    transform: translateX(-100%);
    transition: transform 0.3s ease;

    display: flex;
    flex-direction: column;

    box-shadow: 4px 0 24px rgba(0,0,0,0.18);
}

#chapters-menu.open {
    transform: translateX(0);
}
    .panel-header {
        background: #1a1a2e;
        color: #fff;
        padding: 18px 16px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-header h3 {
        color: #fff;
        font-size: 14px;
        font-weight: 600;
    }

    .panel-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
    }

    /* SCROLL AREA */
    .panel-body {
        overflow-y: auto;
        flex: 1;
        padding: 8px 0;
    }

    /* CHAPTER ITEM */
    .chapter-item {
        border-bottom: 1px solid #f0f0f5;
    }

    /* CHAPTER HEADER */
    .chapter-header {
        display: flex;
        justify-content: space-between;
        padding: 11px 16px;
        cursor: pointer;
    }

    .chapter-header.active {
        background: #eef2ff;
        border-left: 3px solid #1e40af;
    }

    /* LESSON LINKS */
    .chapter-links {
        display: none;
    }

    .chapter-links.open {
        display: block;
    }

    .chapter-links a {
        display: block;
        padding: 8px 16px 8px 24px;
        font-size: 12px;
        text-decoration: none;
        color: #374151;
    }

    .chapter-links a.current {
        color: #1e40af;
        font-weight: 600;
    }

    /* BADGES */
    .ch-badge {
        font-size: 9px;
        padding: 2px 6px;
        border-radius: 10px;
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

    /* ANIMATION */
    #sub-icons.visible .sub-btn {
        animation: slideIn 0.25s ease forwards;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* HIDE ON DESKTOP */
    @media only screen and (min-width: 767px) {
        #fab-root {
            display: none;
        }
    }
</style>

<!-- OVERLAY -->
<div id="menu-overlay"></div>

<!-- CHAPTER MENU -->
<div id="chapters-menu">
    <div class="panel-header">
        <span>📚 Chapters</span>
        <button class="panel-close" id="close-menu">✕</button>
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

<!-- FAB SYSTEM -->
<div id="fab-root">

    <div id="sub-icons">

        <!-- HOME -->
        <a href="{{ route('studentDashboard') }}" class="sub-btn">
            <div class="icon-box icon-dashboard">🏠</div>
            <div class="text">
                <span class="label">GO TO</span>
                <span class="title">Home</span>
            </div>
        </a>

        <!-- CHAPTERS -->
        <button class="sub-btn" id="btn-chapters">
            <div class="icon-box icon-chapters">📚</div>
            <div class="text">
                <span class="label">OPEN</span>
                <span class="title">Chapters</span>
            </div>
        </button>

    </div>

    <!-- FAB BUTTON -->
    <button id="fab-main">
        <svg width="22" height="22" viewBox="0 0 24 24" stroke="white" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
    </button>

</div>

<script>
(function () {
    const fab = document.getElementById('fab-main');
    const subIcons = document.getElementById('sub-icons');
    const chapMenu = document.getElementById('chapters-menu');
    const overlay = document.getElementById('menu-overlay');
    const btnCh = document.getElementById('btn-chapters');
    const closeBtn = document.getElementById('close-menu');

    let fabOpen = false;

    fab.addEventListener('click', () => {
        fabOpen = !fabOpen;
        subIcons.classList.toggle('visible', fabOpen);
        fab.classList.toggle('open', fabOpen);
    });

    btnCh.addEventListener('click', () => {
        chapMenu.classList.add('open');
        overlay.classList.add('active');
    });

    function closeMenu() {
        chapMenu.classList.remove('open');
        overlay.classList.remove('active');
    }

    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);
})();
/* ── HOVER BASED CHAPTER ACCORDION ── */
document.querySelectorAll('.chapter-item').forEach(item => {

    const header = item.querySelector('.chapter-header');
    const links  = item.querySelector('.chapter-links');

    item.addEventListener('mouseenter', () => {

        // Close all
        document.querySelectorAll('.chapter-header').forEach(h => h.classList.remove('active'));
        document.querySelectorAll('.chapter-links').forEach(l => l.classList.remove('open'));

        // Open current
        header.classList.add('active');
        links.classList.add('open');
    });

    item.addEventListener('mouseleave', () => {
        header.classList.remove('active');
        links.classList.remove('open');
    });

});
</script>