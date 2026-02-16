@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
<div class="container pb-5">
    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
    <!-- 4. 申請確認画面 -->
    <section id="page-requests-list" class="page-section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-check me-2"></i>申請状況の確認</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">申請日</th>
                                <th>対象日</th>
                                <th>内容</th>
                                <th>ステータス</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="requests-table-body">
                            <!-- JSで動的に生成 -->
                        </tbody>
                    </table>
                </div>
                <div id="no-requests-msg" class="text-center py-5 d-none">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">現在、提出済みの申請はありません。</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- 取り下げ確認用モーダル -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle text-warning fs-1 mb-3"></i>
                <h5>申請を取り下げますか？</h5>
                <p class="text-muted small">この操作は元に戻せません。</p>
                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-danger" id="confirm-withdraw-btn">取り下げる</button>
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">キャンセル</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 通知トースト -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 2000;">
    <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toast-msg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/common.js') }}"></script>
@endsection