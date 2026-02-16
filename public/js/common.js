/**
 * 勤怠管理システム 共通ロジック
 * Laravelバックエンドとの連携用
 */

// --- 状態管理 ---
let currentViewDate = new Date();
let attendanceData = { inTime: null, outTime: null };
let requests = [];
let pendingWithdrawId = null;

// --- UIインスタンス ---
let withdrawModal;
let toastInstance;

/**
 * アプリの初期化
 */
async function initApp() {
    console.log("Attendance System Initializing...");
    
    // Bootstrap インスタンスの取得
    const modalEl = document.getElementById('withdrawModal');
    const toastEl = document.getElementById('liveToast');
    
    if (typeof bootstrap !== 'undefined') {
        if (modalEl) withdrawModal = new bootstrap.Modal(modalEl);
        if (toastEl) toastInstance = new bootstrap.Toast(toastEl);
    }

    // URLパラメータの解析 (詳細画面から戻った時などの日付保持用)
    const urlParams = new URLSearchParams(window.location.search);
    const dateParam = urlParams.get('date');
    const targetDateEl = document.getElementById('request-target-date');
    if (dateParam && targetDateEl) {
        targetDateEl.innerText = dateParam;
    }

    // 今日の打刻データをLaravelから取得
    await fetchTodayStatus();

    // 時計の開始
    if (document.getElementById('realtime-clock')) {
        updateClock();
        setInterval(updateClock, 1000);
    }
}

/**
 * Laravel APIから今日の打刻状況を取得
 */
async function fetchTodayStatus() {
    try {
        const response = await fetch('/api/attendance/today');
        if (response.ok) {
            attendanceData = await response.json();
            updateUIWithAttendanceData();
        }
        // カレンダー表示が必要なページなら生成
        if (document.getElementById('calendar-body')) generateCalendar();
    } catch (error) {
        console.error("データの取得に失敗しました", error);
    }
}

// ページ読み込み完了時に実行
document.addEventListener('DOMContentLoaded', initApp);

/**
 * ページ遷移（Laravelのルートへ移動）
 */
function showPage(pageId, dateStr = null) {
    const routes = {
        'punch': '/stamp',
        'calendar': '/calendar',
        'requests-list': '/application',
        'request': '/detail'
    };
    
    if (routes[pageId]) {
        let url = routes[pageId];
        if (dateStr) url += `?date=${encodeURIComponent(dateStr)}`;
        window.location.href = url;
    }
}

/**
 * UIの更新（ボタンの活性・非活性など）
 */
function updateUIWithAttendanceData() {
    const inTimeEl = document.getElementById('stat-in-time');
    const outTimeEl = document.getElementById('stat-out-time');
    const badge = document.getElementById('current-status-badge');
    const btnIn = document.getElementById('btn-in');
    const btnOut = document.getElementById('btn-out');

    if (inTimeEl) inTimeEl.innerText = attendanceData.inTime || '--:--';
    if (outTimeEl) outTimeEl.innerText = attendanceData.outTime || '--:--';
    
    if (badge) {
        if (attendanceData.outTime) {
            badge.innerText = '退勤済';
            badge.className = 'badge bg-secondary';
        } else if (attendanceData.inTime) {
            badge.innerText = '出勤中';
            badge.className = 'badge bg-success';
        } else {
            badge.innerText = '未出勤';
            badge.className = 'badge bg-secondary';
        }
    }

    if (btnIn) btnIn.disabled = !!attendanceData.inTime;
    if (btnOut) {
        btnOut.disabled = !attendanceData.inTime || !!attendanceData.outTime;
        if (!btnOut.disabled) {
            btnOut.classList.remove('btn-outline-danger');
            btnOut.classList.add('btn-danger');
        } else {
            btnOut.classList.add('btn-outline-danger');
            btnOut.classList.remove('btn-danger');
        }
    }
}

/**
 * リアルタイム時計
 */
function updateClock() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'short' };
    
    const dateStrEl = document.getElementById('current-date-str');
    if (dateStrEl) dateStrEl.innerText = now.toLocaleDateString('ja-JP', options);
    
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    
    const clockEl = document.getElementById('realtime-clock');
    if (clockEl) clockEl.innerText = `${h}:${m}:${s}`;
}

/**
 * カレンダー生成
 */
function generateCalendar() {
    const year = currentViewDate.getFullYear();
    const month = currentViewDate.getMonth();
    const today = new Date();
    const label = document.getElementById('calendar-month-label');
    if(label) label.innerText = `${year}年 ${month + 1}月`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const calendarBody = document.getElementById('calendar-body');
    if(!calendarBody) return;
    calendarBody.innerHTML = '';

    let dateNum = 1;
    for (let i = 0; i < 6; i++) {
        let row = document.createElement('tr');
        for (let j = 0; j < 7; j++) {
            let cell = document.createElement('td');
            if (i === 0 && j < firstDay) {
                // 空白
            } else if (dateNum > daysInMonth) {
                // 空白
            } else {
                const d = dateNum;
                const fullDateStr = `${year}年${month + 1}月${d}日 (${['日','月','火','水','木','金','土'][j]})`;
                cell.innerHTML = `<span class="calendar-day-num">${d}</span>`;
                
                if (year === today.getFullYear() && month === today.getMonth() && d === today.getDate()) {
                    cell.classList.add('today');
                    if (attendanceData.inTime) cell.innerHTML += `<div class="small mt-1 text-success fw-bold"><span class="status-dot status-in"></span>${attendanceData.inTime}</div>`;
                    if (attendanceData.outTime) cell.innerHTML += `<div class="small text-danger fw-bold"><span class="status-dot status-out"></span>${attendanceData.outTime}</div>`;
                }
                
                cell.addEventListener('dblclick', () => {
                    showPage('request', fullDateStr);
                });
                dateNum++;
            }
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
        if (dateNum > daysInMonth) break;
    }
}

/**
 * 通知表示
 */
function showToast(message, type = 'success') {
    const toastEl = document.getElementById('liveToast');
    const toastMsg = document.getElementById('toast-msg');
    if(!toastEl || !toastMsg || !toastInstance) return;

    toastMsg.innerText = message;
    toastEl.classList.remove('bg-success', 'bg-danger', 'bg-primary', 'bg-warning');
    let bgClass = 'bg-' + type;
    toastEl.classList.add(bgClass);
    toastInstance.show();
}

/**
 * 打刻処理 (POST送信)
 */
document.addEventListener('click', async function(e) {
    if (e.target && (e.target.id === 'btn-in' || e.target.id === 'btn-out')) {
        const type = e.target.id === 'btn-in' ? 'in' : 'out';
        const csrfToken = document.querySelector('meta[name=\"csrf-token\"]').content;

        try {
            const response = await fetch('/stamp/punch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ type: type })
            });

            if (response.ok) {
                const result = await response.json();
                attendanceData = result.data;
                updateUIWithAttendanceData();
                showToast(result.message, type === 'in' ? 'success' : 'danger');
            } else {
                const error = await response.json();
                showToast(error.message || 'エラーが発生しました', 'warning');
            }
        } catch (error) {
            console.error("Punch failed", error);
            showToast('通信エラーが発生しました', 'warning');
        }
    }
});

// カレンダー操作用
window.changeMonth = (diff) => {
    currentViewDate.setMonth(currentViewDate.getMonth() + diff);
    generateCalendar();
};
window.resetToToday = () => {
    currentViewDate = new Date();
    generateCalendar();
};