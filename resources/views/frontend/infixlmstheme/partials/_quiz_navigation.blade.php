<style>
.quiz-progress-bar {
    height: 6px;
    background: #e5e5e5;
    border-radius: 10px;
    margin-top: 8px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #ff3c3c, #3c7cff);
    transition: 0.3s ease;
}

/* Popover */
.question-popover {
    position: absolute;
    top: 45px;
    left: 0;
    width: 420px;
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    z-index: 1050;
}

.floating-title {
    z-index: 1000;
}

.question-popover::before {
    content: '';
    position: absolute;
    top: -8px;
    left: 20px;
    border-width: 8px;
    border-style: solid;
    border-color: transparent transparent #fff transparent;
}

.question-grid {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    gap: 8px;
}

.q-box {
    padding: 10px;
    text-align: center;
    border-radius: 6px;
    background: #e5e5e5;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
}

.q-box:hover {
    background: #dbeafe;
}

/* Active (current question) */
.q-box.active {
    background: #3c7cff;
    color: #fff;
}

/* Answered */
.q-box.answered {
    background: #22c55e;
    color: #fff;
}

@media (max-width: 768px) {
    .question-popover {
        left: 50% !important;
        transform: translateX(-20%);
        width: 90vw;
        max-width: 350px;
    }
    .theme_btn.small_btn {
        padding: 10px;
        line-height: 0;
    } 
    .question-popover {
        top: 55px !important;
    }
    #fab-root {
        right: 0;
        top: 70%;
    }
}
</style>

@php
    $totalQuestions = count($questions);
@endphp

<div class="question_list_header">

    <div class="question_list_top">
        <p>
            {{ __('quiz.Question') }}
            <span id="currentNumber">1</span>
            {{ __('common.out of') }} {{ $totalQuestions }}
        </p>
    </div>

    <div class="quiz-progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>

    <div class="position-relative d-inline-block mt-3">
        <button type="button" class="btn btn-light" id="questionToggleBtn">
            Jump to Question
        </button>

        <div id="questionPopover" class="question-popover d-none">
            <div class="question-grid">
                @foreach ($questions as $index => $assign)
                    <div 
                        class="q-box qbox_{{ $assign->id }}"
                        data-id="{{ $assign->id }}"
                        data-index="{{ $index }}"
                    >
                        {{ $index + 1 }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<!-- Hidden Tabs -->
<div class="nav d-none">
    @foreach ($questions as $index => $assign)
        <a class="nav-link questionLink link_{{ $assign->id }} {{ $index == 0 ? 'active' : '' }}"
           data-bs-toggle="tab"
           href="#pills-{{ $assign->id }}"
           data-qus="{{ $assign->id }}">
        </a>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let total = {{ $totalQuestions }};
    let toggleBtn = document.getElementById('questionToggleBtn');
    let popover = document.getElementById('questionPopover');

    // =========================
    // TOGGLE POPOVER
    // =========================
    toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        popover.classList.toggle('d-none');
    });

    document.addEventListener('click', function (e) {
        if (!popover.contains(e.target) && !toggleBtn.contains(e.target)) {
            popover.classList.add('d-none');
        }
    });

    // =========================
    // SWITCH QUESTION (FIXED)
    // =========================
    function switchQuestion(id) {
        let tabEl = document.querySelector('.link_' + id);
        if (!tabEl) return;

        let tab = new bootstrap.Tab(tabEl);
        tab.show(); // ✅ proper Bootstrap trigger
    }

    // Click grid
    document.querySelectorAll('.q-box').forEach(box => {
        box.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            switchQuestion(id);
            popover.classList.add('d-none');
        });
    });

    // =========================
    // UPDATE UI
    // =========================
    function updateUI(id) {

        let links = Array.from(document.querySelectorAll('.questionLink'));
        let index = links.findIndex(l => l.getAttribute('data-qus') == id);

        // Fix count
        document.getElementById('currentNumber').innerText = index + 1;

        // Progress
        let percent = ((index + 1) / total) * 100;
        document.getElementById('progressFill').style.width = percent + '%';

        // Active
        document.querySelectorAll('.q-box').forEach(b => b.classList.remove('active'));
        let activeBox = document.querySelector('.qbox_' + id);
        if (activeBox) activeBox.classList.add('active');
    }

    // =========================
    // TAB CHANGE SYNC (FIXED)
    // =========================
    document.querySelectorAll('.questionLink').forEach(link => {
        link.addEventListener('shown.bs.tab', function (e) {
            let id = this.getAttribute('data-qus');
            updateUI(id);
        });
    });

    // =========================
    // MARK ANSWERED (FIXED FOR ALL TYPES)
    // =========================
    function markAnswered(qid) {
        let box = document.querySelector('.qbox_' + qid);
        if (box) box.classList.add('answered');
    }

    // Detect ALL inputs
    document.addEventListener('change', function (e) {

        let el = e.target;

        if (
            el.matches('input[type=radio]') ||
            el.matches('input[type=checkbox]') ||
            el.matches('textarea') ||
            el.matches('select')
        ) {
            let pane = el.closest('.tab-pane');
            if (!pane) return;

            let qid = pane.getAttribute('data-qus-id');
            markAnswered(qid);
        }

    });

    // =========================
    // FIX CONTINUE BUTTON
    // =========================
    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('next')) {

            let pane = e.target.closest('.tab-pane');
            if (!pane) return;

            let currentId = pane.getAttribute('data-qus-id');

            let links = Array.from(document.querySelectorAll('.questionLink'));
            let index = links.findIndex(l => l.getAttribute('data-qus') == currentId);

            let next = links[index + 1];
            if (next) {
                let tab = new bootstrap.Tab(next);
                tab.show();
            }

        }

        if (e.target.classList.contains('skip')) {

            let pane = e.target.closest('.tab-pane');
            if (!pane) return;

            let currentId = pane.getAttribute('data-qus-id');

            let links = Array.from(document.querySelectorAll('.questionLink'));
            let index = links.findIndex(l => l.getAttribute('data-qus') == currentId);

            let next = links[index + 1];
            if (next) {
                let tab = new bootstrap.Tab(next);
                tab.show();
            }

        }

    });

    // =========================
    // INITIAL LOAD FIX
    // =========================
    setTimeout(() => {

        let activePane = document.querySelector('.tab-pane.active');

        if (activePane) {
            let id = activePane.getAttribute('data-qus-id');
            updateUI(id);

            // Restore answered
            activePane.closest('#pills-tabContent')
                .querySelectorAll('.tab-pane')
                .forEach(pane => {

                    let inputs = pane.querySelectorAll('input:checked, textarea:not(:empty)');
                    if (inputs.length > 0) {
                        let qid = pane.getAttribute('data-qus-id');
                        markAnswered(qid);
                    }

                });
        }

    }, 200);

});
</script>