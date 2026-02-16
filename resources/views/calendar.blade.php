@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
<div class="container pb-5">
    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
    <section id="page-calendar" class="page-section">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 text-center text-md-start">
                <h4 class="mb-0 fw-bold" id="calendar-month-label"></h4>
                <div class="btn-group shadow-sm mt-2 mt-md-0">
                    <button class="btn btn-light border" onclick="changeMonth(-1)"><i class="bi bi-chevron-left"></i> 前月</button>
                    <button class="btn btn-light border" onclick="resetToToday()">今日</button>
                    <button class="btn btn-light border" onclick="changeMonth(1)">次月 <i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i> 日付を<strong>ダブルクリック</strong>すると変更届の画面へ移動します。</p>
            <div class="table-responsive">
                <table class="table calendar-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-danger">日</th>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th class="text-primary">土</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- JSで生成 -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection


@section('js')
    <script src="{{ asset('js/common.js') }}"></script>
@endsection