<style>

/* =========================
   PROGRESS BAR
========================= */
.quiz-progress-bar {
    height: 6px;
    background: #e5e5e5;
    border-radius: 10px;
    margin-top: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #ff3c3c, #3c7cff);
    transition: 0.3s ease;
}

/* =========================
   HEADER
========================= */
.question-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.q-title {
    font-weight: 600;
    font-size: 16px;
}

/* =========================
   BUTTON
========================= */
#questionToggleBtn {
    background: linear-gradient(90deg, #ff3c3c, #3c7cff);
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
}

/* =========================
   POPOVER WRAPPER
========================= */
.jump-wrapper {
    position: relative;
}

/* =========================
   POPOVER
========================= */
.question-popover {
    position: absolute;
    top: 110%;
    right: 0;
    width: 300px;
    max-width: 90vw;
    background: #fff;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    z-index: 9999;
}

/* ARROW */
.question-popover::before {
    content: '';
    position: absolute;
    top: -6px;
    right: 20px;
    border-width: 6px;
    border-style: solid;
    border-color: transparent transparent #fff transparent;
}

/* =========================
   GRID
========================= */
.question-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 6px;
}

/* BOX */
.q-box {
    padding: 8px;
    text-align: center;
    border-radius: 6px;
    background: #eee;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

.q-box:hover {
    background: #dbeafe;
}

/* STATES */
.q-box.active {
    background: #3c7cff;
    color: #fff;
}

.q-box.answered {
    background: #22c55e;
    color: #fff;
}

/* =========================
   MOBILE
========================= */
@media (max-width: 768px) {

    .question-header-flex {
        flex-direction: row;
    }

    .q-title {
        font-size: 14px;
    }

    #questionToggleBtn {
        padding: 6px 10px;
        font-size: 12px;
    }

    .question-popover {
        width: 260px;
        right: 0;
    }

    #fab-root {
        right: 0;
        top: 25%;
        z-index: 10000;
    }

    .quiz_questions_wrapper .quiz_test_header {
        padding: 10px;
        font-size: 10px;
    }

    .quiz_questions_wrapper .quiz_test_header .quiz_header_right {
        text-align: center;
    }

    .multypol_qustion p, .multypol_qustion h4 {
        font-size: 24px;
        font-weight: bold;
    }

    .quiz_questions_wrapper .quiz_test_body .question_list_header{
        margin-bottom: 10px;
    }

    .quiz_questions_wrapper .quiz_test_body .quiz_select{
        margin-bottom: 10px;
    }

    .quiz_questions_wrapper .quiz_test_body .quiz_select li{
        margin-bottom: 25px;
    }

    .theme_btn.small_btn{
        padding: 1px 20px 28px;
    }
}

</style>

@php
    $totalQuestions = count($questions);
@endphp

<div class="question_list_header">

    <!-- HEADER -->
    <div class="question-header-flex">

        <div class="q-title">
            {{ __('quiz.Question') }}
            <span id="currentNumber">1</span>
            {{ __('common.out of') }} {{ $totalQuestions }}
        </div>

        <div class="jump-wrapper">
            <button type="button" id="questionToggleBtn">
                Jump ▾
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

    <!-- PROGRESS -->
    <div class="quiz-progress-bar">
        <div class="progress-fill" id="progressFill"></div>
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
    // SWITCH QUESTION
    // =========================
    function switchQuestion(id) {
        let tabEl = document.querySelector('.link_' + id);
        if (!tabEl) return;

        let tab = new bootstrap.Tab(tabEl);
        tab.show();
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

        document.getElementById('currentNumber').innerText = index + 1;

        let percent = ((index + 1) / total) * 100;
        document.getElementById('progressFill').style.width = percent + '%';

        document.querySelectorAll('.q-box').forEach(b => b.classList.remove('active'));

        let activeBox = document.querySelector('.qbox_' + id);
        if (activeBox) activeBox.classList.add('active');
    }

    // =========================
    // TAB SYNC
    // =========================
    document.querySelectorAll('.questionLink').forEach(link => {
        link.addEventListener('shown.bs.tab', function () {
            let id = this.getAttribute('data-qus');
            updateUI(id);
        });
    });

    // =========================
    // MARK ANSWERED
    // =========================
    function markAnswered(qid) {
        let box = document.querySelector('.qbox_' + qid);
        if (box) box.classList.add('answered');
    }

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
    // NEXT / SKIP
    // =========================
    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('next') || e.target.classList.contains('skip')) {

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
    // INITIAL LOAD
    // =========================
    setTimeout(() => {

        let activePane = document.querySelector('.tab-pane.active');

        if (activePane) {
            let id = activePane.getAttribute('data-qus-id');
            updateUI(id);

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