@extends('backend.master')
@section('mainContent')
    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header">
                        <div class="main-title d-flex justify-content-between w-100">
                            <h3 class="mb-0">Bulk Import Questions</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="white_box_50px box_shadow_white">
                        <div class="row mb-30">
                            <div class="col-lg-12">
                                <a href="{{ route('question-pool.download-sample') }}" class="primary-btn fix-gr-bg">Download Sample CSV</a>
                            </div>
                        </div>
                        <form action="{{ route('question-pool.bulk-import-submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">CSV / Excel File *</label>
                                        <div class="primary_file_uploader">
                                            <input class="primary-input" type="text" id="placeholderFileOneName" placeholder="Browse file" readonly="">
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="document_file_1">Browse</label>
                                                <input type="file" class="d-none" name="file" id="document_file_1" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg" type="submit"><i class="ti-upload"></i> Import Questions</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
