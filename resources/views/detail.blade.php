@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
<div class="container pb-5">
    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
    <section id="page-request" class="page-section">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="mb-3">
                    <button class="btn btn-link text-decoration-none p-0 text-secondary" onclick="showPage('calendar')">
                        <i class="bi bi-arrow-left"></i> カレンダーに戻る
                    </button>
                </div>
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>打刻修正・変更届</h5>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="text-muted mb-4 fw-bold" id="request-target-date"></h6>
                        
                        <form id="request-form">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">出勤時刻</label>
                                    <input type="time" class="form-control" id="input-in-time" value="09:00">
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label class="form-label small fw-bold">退勤時刻</label>
                                    <input type="time" class="form-control" id="input-out-time" value="18:00">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">変更理由</label>
                                <textarea class="form-control" id="input-reason" rows="3" placeholder="（例）打刻忘れのため修正申請します。"></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" onclick="submitRequest()">申請を送信する</button>
                                <button type="button" class="btn btn-light border" onclick="showPage('calendar')">キャンセル</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection


@section('js')
    <script src="{{ asset('js/common.js') }}"></script>
@endsection