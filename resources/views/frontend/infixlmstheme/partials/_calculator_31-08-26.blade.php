<style>
    /* CSS for calculator */
    #floatingCalculator {
        position: fixed;
        bottom: 150px;
        left: 20px;
        width: 320px;
        min-width: 250px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        z-index: 9999;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
    }
    #calcHeader {
        background: #FB1159;
        color: #fff;
        padding: 12px 15px;
        cursor: move;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 14px;
        border-radius: 11px 11px 0 0;
    }
    .calc-controls span {
        cursor: pointer;
        margin-left: 15px;
        font-size: 14px;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    .calc-controls span:hover {
        opacity: 1;
    }
    .calc-body {
        padding: 15px;
        background: #fff;
        border-radius: 0 0 11px 11px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .calc-tabs {
        display: flex;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 15px;
    }
    .calc-tab {
        flex: 1;
        text-align: center;
        padding: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s;
    }
    .calc-tab.active {
        color: #FB1159;
        border-bottom: 2px solid #FB1159;
        margin-bottom: -2px;
    }
    
    .calc-mode-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    /* Scientific Calc Styles */
    .calc-display {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 10px;
        text-align: right;
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 15px;
        min-height: 56px;
        word-wrap: break-word;
        overflow-x: auto;
    }
    .calc-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        flex: 1;
    }
    .basic-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }
    .basic-grid .calc-btn {
        font-size: 16px; /* Base size for standard buttons */
    }
    .basic-grid .num-btn, .basic-grid .eval-btn {
        font-size: 24px; /* Larger size for numbers and equals */
    }
    .btn-tall {
        grid-row: span 2;
    }
    .calc-btn.num-btn {
        background: #207682;
        color: white;
        border-color: #1a626c;
    }
    .calc-btn.num-btn:hover {
        background: #1a626c;
    }
    .calc-btn.org-btn {
        background: #f59e0b;
        color: white;
        border-color: #d97706;
    }
    .calc-btn.org-btn:hover {
        background: #d97706;
    }
    .calc-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 6px 5px;
        text-align: center;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        color: #334155;
        transition: all 0.1s;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .calc-btn:hover {
        background: #e2e8f0;
    }
    .calc-btn:active {
        transform: scale(0.95);
    }
    .calc-btn.op-btn {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .calc-btn.eval-btn {
        background: #FB1159;
        color: #fff;
        border-color: #FB1159;
    }
    .calc-btn.eval-btn:hover {
        background: #e00f4f;
    }
    
    /* Finance Calc Styles */
    .finance-row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .finance-label {
        width: 55px;
        font-weight: 600;
        font-size: 13px;
        color: #475569;
    }
    .finance-input {
        flex: 1;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 8px 10px;
        font-size: 13px;
        outline: none;
        width: 100px;
    }
    .finance-input:focus {
        border-color: #FB1159;
    }
    .finance-calc-btn {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 12px;
        margin-left: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }
    .finance-calc-btn:hover {
        background: #bfdbfe;
    }
    
    #floatingCalculator.minimized .calc-body {
        display: none;
    }
    
    .tvm-help {
        font-size: 11px;
        color: #64748b;
        margin-top: 15px;
        line-height: 1.4;
        background: #f8fafc;
        padding: 10px;
        border-radius: 6px;
    }
    
    #calcFloatingToggle {
        position: fixed;
        bottom: 150px;
        left: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #FB1159;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 15px rgba(251, 17, 89, 0.3);
        cursor: pointer;
        z-index: 9998;
        transition: transform 0.2s;
    }
    #calcFloatingToggle:hover {
        transform: scale(1.1);
    }
</style>

<div id="calcFloatingToggle" style="display: none;" title="Open Calculator">
    <i class="fas fa-calculator"></i>
</div>

<div id="floatingCalculator" style="display: none;">
    <div id="calcHeader">
        <div><i class="fas fa-calculator mr-2"></i> Calculator</div>
        <div class="calc-controls">
            <span id="calcMinMax" title="Minimize/Maximize"><i class="fas fa-minus"></i></span>
            <span id="calcClose" title="Close"><i class="fas fa-times"></i></span>
        </div>
    </div>
    
    <div class="calc-body">

        
        <div id="scientificMode" class="calc-mode-content">
            <div class="calc-display" id="calcDisplay">0</div>
            
            <div id="toggleScientific" style="text-align: right; margin-bottom: 10px; cursor: pointer; color: #FB1159; font-size: 13px; font-weight: 600; user-select: none;">
                <i class="fas fa-flask"></i> <span id="scientificToggleText">Show Scientific</span>
            </div>
            
            <div class="calc-grid" id="scientificGrid" style="display: none; margin-bottom: 8px;">
                <!-- Scientific Rows -->
                <div class="calc-btn op-btn" data-val="sin(">sin</div>
                <div class="calc-btn op-btn" data-val="cos(">cos</div>
                <div class="calc-btn op-btn" data-val="tan(">tan</div>
                <div class="calc-btn op-btn" data-val="pi">π</div>
                
                <div class="calc-btn op-btn" data-val="log(">log</div>
                <div class="calc-btn op-btn" data-val="ln(">ln</div>
                <div class="calc-btn op-btn" data-val="^">x^y</div>
                <div class="calc-btn op-btn" data-val="sqrt(">√</div>
            </div>

            <div class="basic-grid">
                <!-- Row 1 -->
                <div class="calc-btn" style="color: #207682;" data-val="DEL" title="Backspace">►</div>
                <div class="calc-btn" style="color: #207682;" data-val="+/-">+/-</div>
                <div class="calc-btn" style="color: #207682;" data-val="sqrt(">√</div>
                <div class="calc-btn" style="color: #207682;" data-val="(">(</div>
                <div class="calc-btn" style="color: #207682;" data-val=")">)</div>
                
                <!-- Row 2 -->
                <div class="calc-btn" style="color: #207682;" data-val="MC">MC</div>
                <div class="calc-btn" style="color: #207682;" data-val="MR">MR</div>
                <div class="calc-btn" style="color: #207682;" data-val="M-">M-</div>
                <div class="calc-btn" style="color: #207682;" data-val="M+">M+</div>
                <div class="calc-btn" style="color: #207682; font-size: 32px;" data-val="/">÷</div>
                
                <!-- Row 3 -->
                <div class="calc-btn" style="color: #207682; font-size: 32px;" data-val="%">%</div>
                <div class="calc-btn num-btn" data-val="7">7</div>
                <div class="calc-btn num-btn" data-val="8">8</div>
                <div class="calc-btn num-btn" data-val="9">9</div>
                <div class="calc-btn" style="color: #207682; font-size: 32px;" data-val="*">×</div>
                
                <!-- Row 4 -->
                <div class="calc-btn org-btn" data-val="AC">AC</div>
                <div class="calc-btn num-btn" data-val="4">4</div>
                <div class="calc-btn num-btn" data-val="5">5</div>
                <div class="calc-btn num-btn" data-val="6">6</div>
                <div class="calc-btn" style="color: #207682; font-size: 36px;" data-val="-">-</div>
                
                <!-- Row 5 -->
                <div class="calc-btn org-btn" data-val="C" style="font-size: 14px;">ON/C</div>
                <div class="calc-btn num-btn" data-val="1">1</div>
                <div class="calc-btn num-btn" data-val="2">2</div>
                <div class="calc-btn num-btn" data-val="3">3</div>
                <div class="calc-btn btn-tall" style="color: #207682; height: 100%; font-size: 32px;" data-val="+">+</div>
                
                <!-- Row 6 -->
                <div class="calc-btn num-btn" data-val="0">0</div>
                <div class="calc-btn num-btn" data-val="00">00</div>
                <div class="calc-btn num-btn" data-val=".">.</div>
                <div class="calc-btn eval-btn" data-val="=" style="background: #207682; color: #fff; border: none;">=</div>
            </div>
        </div>
        

    </div>
</div>

<script>
    $(document).ready(function() {
        const authId = "{{ auth()->check() ? auth()->id() : 'guest' }}";
        const stateKey = `lms_calc_state_${authId}`;
        
        // Initialize State
        let calcState = {
            isOpen: false,
            isMinimized: false,
            isScientificOpen: false,
            position: { top: 'auto', left: '20px', bottom: '20px', right: 'auto' },
            size: { width: '320px', height: 'auto' },
            activeTab: '#scientificMode',
            expression: '0',
            tvm: { n: '', r: '', pv: '', pmt: '', fv: '' }
        };
        
        // Load state from local storage
        try {
            const savedState = localStorage.getItem(stateKey);
            if (savedState) {
                const parsed = JSON.parse(savedState);
                calcState = { ...calcState, ...parsed };
            }
        } catch(e) {}
        
        function saveState() {
            localStorage.setItem(stateKey, JSON.stringify(calcState));
        }
        
        const $calc = $('#floatingCalculator');
        const $display = $('#calcDisplay');
        const $toggleBtn = $('#calcFloatingToggle');
        
        // Apply State
        if (calcState.isScientificOpen) {
            $('#scientificGrid').show();
            $('#scientificToggleText').text('Hide Scientific');
        } else {
            $('#scientificGrid').hide();
            $('#scientificToggleText').text('Show Scientific');
        }

        $('#toggleScientific').on('click', function() {
            $('#scientificGrid').slideToggle(200, function() {
                calcState.isScientificOpen = $('#scientificGrid').is(':visible');
                $('#scientificToggleText').text(calcState.isScientificOpen ? 'Hide Scientific' : 'Show Scientific');
                saveState();
            });
        });

        if (calcState.isOpen) {
            $calc.show();
            $toggleBtn.hide();
        } else {
            $toggleBtn.show();
        }
        if (calcState.isMinimized) {
            $calc.addClass('minimized');
            $('#calcMinMax i').removeClass('fa-minus').addClass('fa-window-maximize');
        }
        if (calcState.position.top !== 'auto' || calcState.position.left !== 'auto' || calcState.position.bottom !== 'auto') {
            $calc.css({
                top: calcState.position.top,
                left: calcState.position.left,
                bottom: calcState.position.bottom,
                right: calcState.position.right
            });
        }
        
        if (calcState.size.width !== 'auto') {
            $calc.css({
                width: calcState.size.width,
                height: calcState.size.height
            });
        }
        
        // Setup Dragging
        if ($.fn.draggable) {
            $calc.draggable({
                handle: "#calcHeader",
                containment: "window",
                stop: function(event, ui) {
                    calcState.position = {
                        top: ui.position.top + 'px',
                        left: ui.position.left + 'px',
                        bottom: 'auto',
                        right: 'auto'
                    };
                    saveState();
                }
            });
        }
        
        // Setup Resizing
        if ($.fn.resizable) {
            $calc.resizable({
                minHeight: 350,
                minWidth: 280,
                handles: "n, e, s, w, ne, se, sw, nw",
                stop: function(event, ui) {
                    calcState.size = {
                        width: ui.size.width + 'px',
                        height: ui.size.height + 'px'
                    };
                    saveState();
                }
            });
        }
        
        // Tabs
        $('.calc-tab').on('click', function() {
            $('.calc-tab').removeClass('active');
            $(this).addClass('active');
            $('.calc-mode-content').hide();
            const target = $(this).data('target');
            $(target).show();
            
            calcState.activeTab = target;
            saveState();
        });
        
        // Apply Tab State
        $(`.calc-tab[data-target="${calcState.activeTab}"]`).click();
        
        // Controls
        $('#calcMinMax').on('click', function() {
            $calc.toggleClass('minimized');
            const isMin = $calc.hasClass('minimized');
            calcState.isMinimized = isMin;
            $(this).find('i').toggleClass('fa-minus', !isMin).toggleClass('fa-window-maximize', isMin);
            saveState();
        });
        
        $('#calcClose').on('click', function() {
            $calc.hide();
            $toggleBtn.show();
            calcState.isOpen = false;
            saveState();
        });
        
        $toggleBtn.on('click', function() {
            window.openCalculator();
        });
        
        // Global hook to open calculator
        window.openCalculator = function() {
            $calc.show();
            $toggleBtn.hide();
            calcState.isOpen = true;
            saveState();
        };
        
        // --- Scientific Calculator Logic ---
        $display.text(calcState.expression);
        
        let expression = calcState.expression === '0' ? '' : calcState.expression;
        let shouldResetDisplay = false;
        
        function updateDisplay(val) {
            $display.text(val || '0');
            calcState.expression = val || '0';
            saveState();
        }
        
        function handleCalcAction(val) {
            if (val === 'C' || val === 'AC') {
                expression = '';
                updateDisplay('0');
                return;
            }
            
            if (val === 'DEL') {
                if (shouldResetDisplay) {
                    expression = '';
                    shouldResetDisplay = false;
                } else {
                    expression = expression.slice(0, -1);
                }
                updateDisplay(expression);
                return;
            }
            
            if (val === '+/-') {
                if (expression && expression !== '0' && !shouldResetDisplay) {
                    if (expression.startsWith('-')) {
                        expression = expression.substring(1);
                    } else {
                        expression = '-' + expression;
                    }
                    updateDisplay(expression);
                }
                return;
            }
            
            if (val === '%') {
                try {
                    let result = Function('"use strict";return (' + expression + ')')();
                    expression = String(result / 100);
                    updateDisplay(expression);
                    shouldResetDisplay = true;
                } catch(e) {}
                return;
            }
            
            if (['MC', 'MR', 'M+', 'M-', 'GT', 'MU'].includes(val)) {
                // Advanced functions placeholder
                return;
            }
            
            if (val === '=') {
                try {
                    // Auto-close any unclosed parentheses
                    let openCount = (expression.match(/\(/g) || []).length;
                    let closeCount = (expression.match(/\)/g) || []).length;
                    for (let i = 0; i < openCount - closeCount; i++) {
                        expression += ')';
                    }

                    // Safe basic evaluation replacing scientific terms
                    let evalExpr = expression
                        .replace(/sin\(/g, 'Math.sin(')
                        .replace(/cos\(/g, 'Math.cos(')
                        .replace(/tan\(/g, 'Math.tan(')
                        .replace(/log\(/g, 'Math.log10(')
                        .replace(/ln\(/g, 'Math.log(')
                        .replace(/sqrt\(/g, 'Math.sqrt(')
                        .replace(/pi/g, 'Math.PI')
                        .replace(/\^/g, '**');
                        
                    let result = Function('"use strict";return (' + evalExpr + ')')();
                    
                    // Format result to avoid huge decimals
                    if (typeof result === 'number') {
                        result = Math.round(result * 10000000000) / 10000000000;
                    }
                    
                    expression = String(result);
                    updateDisplay(expression);
                    shouldResetDisplay = true;
                } catch(e) {
                    updateDisplay('Error');
                    expression = '';
                }
                return;
            }
            
            if (shouldResetDisplay && !['+','-','*','/','^'].includes(val)) {
                expression = '';
            }
            shouldResetDisplay = false;
            
            expression += val;
            updateDisplay(expression);
        }

        $('.calc-btn').on('click', function() {
            const val = $(this).data('val');
            handleCalcAction(val);
        });
        
        $(document).on('keydown', function(e) {
            if (!calcState.isOpen || calcState.activeTab !== '#scientificMode' || calcState.isMinimized) return;
            if ($(e.target).is('input, textarea')) return;
            
            const key = e.key;
            let mappedVal = null;
            
            if (key.length === 1 && /[0-9\.\+\-\*\/\(\)\^]/.test(key)) {
                mappedVal = key;
            } else if (key === 'Enter' || key === '=') {
                mappedVal = '=';
                e.preventDefault();
            } else if (key === 'Backspace') {
                mappedVal = 'DEL';
                e.preventDefault();
            } else if (key === 'Escape' || key === 'Delete') {
                mappedVal = 'C';
                e.preventDefault();
            } else if (key.toLowerCase() === 'p') {
                mappedVal = 'pi';
            } else if (key.toLowerCase() === 'e') {
                mappedVal = 'e';
            }
            
            if (mappedVal) {
                handleCalcAction(mappedVal);
            }
        });
        

    });
</script>