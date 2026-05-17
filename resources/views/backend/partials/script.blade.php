<script src="{{assetPath('backend/js/plugin.js')}}{{assetVersion()}}" type="application/javascript"></script>

<script>
    @php
        $pdf_font= Settings('datatable_default_font');
        if(!$pdf_font || !file_exists(base_path('public/fonts/DejaVuSans.ttf'))){
            $pdf_font =assetPath('fonts/DejaVuSans.ttf');
        }else{
            $pdf_font = assetPath(Settings('datatable_default_font'));
        }

    @endphp

        pdfMake.fonts = {
        defaultFont: {
            normal: '{{$pdf_font}}',
            bold: '{{$pdf_font}}',
            italics: '{{$pdf_font}}',
            bolditalics: '{{$pdf_font}}',
        }
    };

</script>
<script src="{{assetPath('backend/js/datatable_extra.js')}}{{assetVersion()}}" type="application/javascript"></script>

{{--<script src="{{assetPath('backend/js/jquery-ui.js')}}"></script>--}}
@if(isModuleActive('Org'))
    <script src="{{assetPath('js/loadingoverlay.min.js')}}{{assetVersion()}}" type="application/javascript"></script>
@endif

<script>
    if ($('#main-nav-for-chat').length) {
    } else {
        $('#main-content').append('<div id="main-nav-for-chat" style="visibility: hidden;"></div>');
    }

    if ($('#admin-visitor-area').length) {
    } else {
        $('#main-content').append('<div id="admin-visitor-area" style="visibility: hidden;"></div>');
    }
</script>

@if(isModuleActive('Chat'))
    <script src="{{ assetPath('js/app.js') }}{{assetVersion()}}" type="application/javascript"></script>
@endif

<script>

    $(document).on('click', '.unread_notification', function (e) {
        e.preventDefault();
        $('.preloader').fadeIn('slow');
        let notification_id = $(this).attr('data-notification_id');
        let count_txt = $('.notificationCount');
        let count = count_txt.text();
        if (count > 0) {
            count--;
            count_txt.text(count);
        }
        $(this).closest('.single_notify').remove();


        let url = $('#url').val();

        let formData = {
            id: notification_id
        };
        $.ajax({
            type: "GET",
            data: formData,
            dataType: 'json',
            url: url + '/' + 'ajaxNotificationMakeRead',
            success: function (data) {
                if (data != '') {
                    window.location.href = data;

                } else {
                    $('.preloader').fadeOut('slow');
                }

            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    });

</script>


<script>
    if ($('.Crm_table_active3').length) {
        let datatable = $('.Crm_table_active3').DataTable(dataTableOptions);

        $(".selectAllQuiz").on("click", function () {
            let totalQuestions = $('#totalQuestions');
            let totalMarks = $('#totalMarks');

            if ($(this).is(':checked')) {
                datatable.$("input[type='checkbox']").prop('checked', true);
            } else {
                datatable.$("input[type='checkbox']").prop('checked', false);
            }
            let online_exam_id = $('#online_exam_id').val();
            let ques_assign = $('.ques_assign').val();
            let token = $('.csrf_token').val();
            var myCheckboxes = [];
            datatable.$("input[type='checkbox']").each(function () {
                if ($(this).is(':checked')) {
                    myCheckboxes.push($(this).val());
                }
            });
            $.ajax({
                type: 'POST',
                url: ques_assign,
                data: {
                    '_token': token,
                    online_exam_id: online_exam_id,
                    questions: myCheckboxes,
                },
                success: function (data) {
                    totalQuestions.html(data.totalQus);
                    totalMarks.html(data.totalMarks);
                    toastr.success('{{__('common.Successfully Assign')}}', '{{__('common.Success')}}');
                },
                error: function (data) {
                    toastr.error('{{__('common.Something Went Wrong')}}', '{{__('common.Error')}}')
                }
            });
        });

    }
</script>
<script>
    setTimeout(function () {
        $('.preloader').fadeOut('slow', function () {
            // $(this).remove();
        });
    }, 0);
</script>

<script>
    if ($('#main-nav-for-chat').length) {
    } else {
        $('#main-content').append('<div id="main-nav-for-chat" style="display: none;"></div>');
    }

    if ($('#admin-visitor-area').length) {
    } else {
        $('#main-content').append('<div id="admin-visitor-area" style="visibility: hidden;"></div>');
    }
</script>

<script>
    //datatable caching
    $.fn.dataTable.pipeline = function (opts) {
        // Configuration options
        var conf = $.extend({
            pages: 5,     // number of pages to cache
            url: '',      // script url
            data: null,   // function or object with parameters to send to the server
                          // matching how `ajax.data` works in DataTables
            method: 'GET' // Ajax HTTP method
        }, opts);
        // Private variables for storing the cache
        var cacheLower = -1;
        var cacheUpper = null;
        var cacheLastRequest = null;
        var cacheLastJson = null;
        return function (request, drawCallback, settings) {
            var ajax = false;
            var requestStart = request.start;
            var drawStart = request.start;
            var requestLength = request.length;
            var requestEnd = requestStart + requestLength;

            if (settings.clearCache) {
                // API requested that the cache be cleared
                ajax = true;
                settings.clearCache = false;
            } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                // outside cached data - need to make a request
                ajax = true;
            } else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
            ) {
                // properties changed (ordering, columns, searching)
                ajax = true;
            }

            // Store the request for checking next time around
            cacheLastRequest = $.extend(true, {}, request);

            if (ajax) {
                // Need data from the server
                if (requestStart < cacheLower) {
                    requestStart = requestStart - (requestLength * (conf.pages - 1));

                    if (requestStart < 0) {
                        requestStart = 0;
                    }
                }
                cacheLower = requestStart;
                cacheUpper = requestStart + (requestLength * conf.pages);

                request.start = requestStart;
                request.length = requestLength * conf.pages;

                // Provide the same `data` options as DataTables.
                if (typeof conf.data === 'function') {
                    // As a function it is executed with the data object as an arg
                    // for manipulation. If an object is returned, it is used as the
                    // data object to submit
                    var d = conf.data(request);
                    if (d) {
                        $.extend(request, d);
                    }
                } else if ($.isPlainObject(conf.data)) {
                    // As an object, the data given extends the default
                    $.extend(request, conf.data);
                }

                return $.ajax(
                    {
                        "type": conf.method,
                        "url": conf.url,
                        "data": request,
                        "dataType": "json",
                        "cache": false,
                        "success": function (json) {
                            cacheLastJson = $.extend(true, {}, json);

                            if (cacheLower != drawStart) {
                                json.data.splice(0, drawStart - cacheLower);
                            }
                            if (requestLength >= -1) {
                                json.data.splice(requestLength, json.data.length);
                            }

                            drawCallback(json);
                        }
                    });
            } else {
                var json = $.extend(true, {}, cacheLastJson);
                json.draw = request.draw; // Update the echo for each response
                json.data.splice(0, requestStart - cacheLower);
                json.data.splice(requestLength, json.data.length);

                drawCallback(json);
            }
        }
    };

    // Register an API method that will empty the pipelined data, forcing an Ajax
    // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
    $.fn.dataTable.Api.register('clearPipeline()', function () {
        return this.iterator('table', function (settings) {
            settings.clearCache = true;
        });
    });
</script>

<script>
    $(function () {
        initChunkedUpload();
    });

    /**
     * Custom Chunked File Uploader — replaces FilePond.
     *
     * Finds all <input type="file" class="filepond"> and wraps each in a
     * drag-drop zone with progress bar. Uploads via 5 MB chunks to
     * /chunked-upload/init + /chunked-upload/chunk, then sets the encrypted
     * server ID into a hidden input so the form submission contract is unchanged.
     */
    function initChunkedUpload() {
        const CHUNK_SIZE = 1.5 * 1024 * 1024; // 1.5 MB
        const INIT_URL   = '{{ url("/chunked-upload/init") }}';
        const CHUNK_URL  = '{{ url("/chunked-upload/chunk") }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        document.querySelectorAll('input[type="file"].filepond').forEach(function (input) {
            // Read original attributes
            const fieldName      = input.getAttribute('name') || 'file';
            const existingFile   = input.getAttribute('data-file') || '';
            const dataAccepts    = input.getAttribute('data-accepts') || '';
            const parentEl       = input.parentElement;

            // Hide original input
            input.style.display = 'none';
            input.removeAttribute('name'); // prevent double-submit

            // Create hidden input that holds the server ID (same name as original)
            const hiddenInput = document.createElement('input');
            hiddenInput.type  = 'hidden';
            hiddenInput.name  = fieldName;
            hiddenInput.value = existingFile || '';
            parentEl.appendChild(hiddenInput);

            // Build the uploader UI
            const wrapper = document.createElement('div');
            wrapper.className = 'chunked-upload-zone';
            wrapper.innerHTML = `
                <div class="cu-dropzone" tabindex="0">
                    <div class="cu-idle">
                        <svg class="cu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 16V4m0 0L8 8m4-4l4 4"/>
                            <path d="M20 16.7V19a2 2 0 01-2 2H6a2 2 0 01-2-2v-2.3"/>
                        </svg>
                        <span class="cu-label">{{ __('common.Drag & Drop or') }} <em>{{ __('common.Browse') }}</em></span>
                    </div>
                    <div class="cu-progress-wrap" style="display:none">
                        <div class="cu-file-info">
                            <span class="cu-filename"></span>
                            <button type="button" class="cu-cancel" title="{{ __('common.Cancel') }}">&times;</button>
                        </div>
                        <div class="cu-bar-bg"><div class="cu-bar-fill"></div></div>
                        <span class="cu-percent">0%</span>
                    </div>
                    <div class="cu-done" style="display:none">
                        <svg class="cu-done-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span class="cu-done-name"></span>
                        <button type="button" class="cu-remove" title="{{ __('common.Remove') }}">&times;</button>
                    </div>
                </div>`;
            parentEl.insertBefore(wrapper, hiddenInput);

            // References
            const dropzone    = wrapper.querySelector('.cu-dropzone');
            const idleUI      = wrapper.querySelector('.cu-idle');
            const progressUI  = wrapper.querySelector('.cu-progress-wrap');
            const doneUI      = wrapper.querySelector('.cu-done');
            const filenameTxt = wrapper.querySelector('.cu-filename');
            const barFill     = wrapper.querySelector('.cu-bar-fill');
            const percentTxt  = wrapper.querySelector('.cu-percent');
            const cancelBtn   = wrapper.querySelector('.cu-cancel');
            const removeBtn   = wrapper.querySelector('.cu-remove');
            const doneName    = wrapper.querySelector('.cu-done-name');

            let abortController = null;

            // If an existing file is already set, show "done" state
            if (existingFile) {
                showDone(existingFile.split('/').pop() || '{{ __("common.Existing File") }}');
            }

            // Click to browse
            dropzone.addEventListener('click', function (e) {
                if (e.target === cancelBtn || e.target === removeBtn) return;
                const fi = document.createElement('input');
                fi.type = 'file';
                if (dataAccepts) fi.accept = dataAccepts;
                fi.addEventListener('change', function () {
                    if (fi.files.length) handleFile(fi.files[0]);
                });
                fi.click();
            });

            // Keyboard accessibility
            dropzone.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); dropzone.click(); }
            });

            // Drag & drop
            ['dragenter', 'dragover'].forEach(function (evt) {
                dropzone.addEventListener(evt, function (e) { e.preventDefault(); dropzone.classList.add('cu-dragover'); });
            });
            ['dragleave', 'drop'].forEach(function (evt) {
                dropzone.addEventListener(evt, function (e) { e.preventDefault(); dropzone.classList.remove('cu-dragover'); });
            });
            dropzone.addEventListener('drop', function (e) {
                if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
            });

            // Cancel
            cancelBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (abortController) abortController.abort();
                resetUI();
            });

            // Remove
            removeBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                hiddenInput.value = '';
                resetUI();
            });

            function resetUI() {
                idleUI.style.display = '';
                progressUI.style.display = 'none';
                doneUI.style.display = 'none';
                barFill.style.width = '0%';
                percentTxt.textContent = '0%';
            }

            function showDone(name) {
                idleUI.style.display = 'none';
                progressUI.style.display = 'none';
                doneUI.style.display = '';
                doneName.textContent = name;
            }

            async function handleFile(file) {
                abortController = new AbortController();
                const signal = abortController.signal;

                // Show progress UI
                idleUI.style.display = 'none';
                doneUI.style.display = 'none';
                progressUI.style.display = '';
                filenameTxt.textContent = file.name;
                barFill.style.width = '0%';
                percentTxt.textContent = '0%';

                try {
                    // Step 1: Init upload
                    const initRes = await fetch(INIT_URL, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            filename: file.name,
                            filesize: file.size,
                            chunk_size: CHUNK_SIZE,
                        }),
                        signal: signal,
                    });

                    if (!initRes.ok) throw new Error('Init failed: ' + initRes.status);
                    const initData = await initRes.json();
                    const uploadId    = initData.upload_id;
                    const totalChunks = initData.total_chunks;

                    // Helper to upload a single chunk
                    const uploadChunk = async (chunkIndex) => {
                        const start = chunkIndex * CHUNK_SIZE;
                        const end   = Math.min(start + CHUNK_SIZE, file.size);
                        const blob  = file.slice(start, end);

                        const fd = new FormData();
                        fd.append('upload_id', uploadId);
                        fd.append('chunk_index', chunkIndex);
                        fd.append('total_chunks', totalChunks);
                        fd.append('chunk', blob, file.name + '.part');

                        let retries = 0;
                        const maxRetries = 3;
                        const retryDelays = [500, 1000, 3000];

                        while (retries <= maxRetries) {
                            try {
                                const chunkRes = await fetch(CHUNK_URL, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': CSRF_TOKEN,
                                        'Accept': 'application/json',
                                    },
                                    body: fd,
                                    signal: signal,
                                });

                                if (!chunkRes.ok) throw new Error('Chunk ' + chunkIndex + ' failed: ' + chunkRes.status);

                                return await chunkRes.json();
                            } catch (err) {
                                if (signal.aborted) throw err;
                                retries++;
                                if (retries > maxRetries) throw err;
                                await new Promise(r => setTimeout(r, retryDelays[retries - 1] || 3000));
                            }
                        }
                    };

                    // Send chunks in parallel with a max concurrency of 3
                    const queue = Array.from({ length: totalChunks }, (_, i) => i);
                    let completedCount = 0;
                    const CONCURRENCY = 3;

                    const worker = async () => {
                        while (queue.length > 0 && !signal.aborted) {
                            const chunkIndex = queue.shift();
                            if (chunkIndex === undefined) break;

                            const chunkData = await uploadChunk(chunkIndex);
                            completedCount++;

                            // Update progress
                            const pct = Math.round((completedCount / totalChunks) * 100);
                            barFill.style.width = pct + '%';
                            percentTxt.textContent = pct + '%';

                            // Final chunk — set the server ID
                            if (chunkData.completed && chunkData.server_id) {
                                hiddenInput.value = chunkData.server_id;
                                showDone(file.name);
                            }
                        }
                    };

                    const workers = Array.from({ length: Math.min(CONCURRENCY, totalChunks) }, worker);
                    await Promise.all(workers);

                } catch (err) {
                    if (err.name === 'AbortError') return;
                    console.error('Chunked upload error:', err);
                    toastr.error(err.message || '{{ __("common.Something Went Wrong") }}', '{{ __("common.Error") }}');
                    resetUI();
                }
            }
        });
    }
</script>

<style>
    .chunked-upload-zone { width: 100%; }
    .cu-dropzone {
        border: 2px dashed rgba(130, 139, 178, 0.35);
        border-radius: 10px;
        padding: 22px 18px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        background: var(--bg_white, #fff);
        outline: none;
    }
    .cu-dropzone:hover, .cu-dropzone.cu-dragover {
        border-color: var(--base_color, #6C27FF);
        background: rgba(108, 39, 255, 0.04);
    }
    .cu-icon { width: 32px; height: 32px; color: var(--base_color, #6C27FF); margin-bottom: 6px; }
    .cu-label { display: block; font-size: 13px; color: var(--text-color, #828bb2); }
    .cu-label em { font-style: normal; color: var(--base_color, #6C27FF); font-weight: 600; }
    .cu-progress-wrap { text-align: left; }
    .cu-file-info { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
    .cu-filename { font-size: 13px; color: var(--dynamic-text-color, #333); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%; }
    .cu-cancel, .cu-remove {
        background: none; border: none; font-size: 20px; line-height: 1;
        color: #e74c3c; cursor: pointer; padding: 0 4px; font-weight: 700;
    }
    .cu-cancel:hover, .cu-remove:hover { color: #c0392b; }
    .cu-bar-bg {
        width: 100%; height: 6px; border-radius: 3px;
        background: rgba(130, 139, 178, 0.18); overflow: hidden;
    }
    .cu-bar-fill {
        height: 100%; width: 0%; border-radius: 3px;
        background: linear-gradient(90deg, var(--base_color, #6C27FF), #a855f7);
        transition: width .25s ease;
    }
    .cu-percent { font-size: 12px; color: var(--text-color, #828bb2); margin-top: 3px; display: inline-block; }
    .cu-done { display: flex; align-items: center; gap: 8px; }
    .cu-done-icon { width: 22px; height: 22px; color: #27ae60; flex-shrink: 0; }
    .cu-done-name { font-size: 13px; color: var(--dynamic-text-color, #333); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>
<script src="{{ assetPath('chat/js/custom.js') }}{{assetVersion()}}"></script>
@if(isModuleActive("WhatsappSupport"))
    <script src="{{ assetPath('whatsapp-support/scripts.js') }}{{assetVersion()}}"></script>
@endif
<!-- Load Uppy JS bundle. -->
<script src="{{ assetPath('vendor/uppy/uppy.min.js') }}"></script>
<script src="{{ assetPath('vendor/uppy/uppy.legacy.min.js') }}"></script>
<script src="{{ assetPath('vendor/uppy/ru_RU.min.js') }}"></script>

@stack('scripts')
<script>
    $('.dataTables_length label select').niceSelect();
    $('.dataTables_length label .nice-select').addClass('dataTable_select');
    $(document).on('click', '.dataTables_length label .nice-select', function () {
        $(this).toggleClass('open_selectlist');
    })
</script>

