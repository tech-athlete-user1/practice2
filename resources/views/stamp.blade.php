@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
<div class="container pb-5">
    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
    <section id="page-punch" class="page-section active">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="mb-4">{{ Auth::user()->name }} さん、お疲れ様です！</h2>
                
                <div class="card shadow-lg mb-5 border-0 border-top border-5 border-primary">
                    <div class="card-body py-5">
                        <div class="text-muted mb-2" id="current-date-str"></div>
                        <div id="realtime-clock" class="display-1 fw-bold text-primary">00:00:00</div>
                        
                        <div class="mt-5 d-flex justify-content-center gap-4">
                            <button class="btn btn-primary btn-lg px-5 py-3 shadow" id="btn-in">
                                <i class="bi bi-box-arrow-in-right fs-4 d-block mb-1"></i>出勤
                            </button>
                            <button class="btn btn-outline-danger btn-lg px-5 py-3 shadow" id="btn-out" disabled>
                                <i class="bi bi-box-arrow-left fs-4 d-block mb-1"></i>退勤
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white text-start">
                                <i class="bi bi-list-check me-2"></i>本日のログ
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush text-start">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>状態</span><span class="badge bg-secondary" id="current-status-badge">未出勤</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>出勤時刻</span><span id="stat-in-time">--:--</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>退勤時刻</span><span id="stat-out-time">--:--</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
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