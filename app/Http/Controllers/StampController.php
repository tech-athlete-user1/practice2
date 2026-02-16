<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
// use App\Models\Attendance; // モデルを作成済みの場合

class StampController extends Controller
{
    public function index()
    {
        return view('stamp');
    }

    /**
     * 今日の打刻データを取得 (API用)
     */
    public function getTodayStatus()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        // 実際のDB取得例 (Attendanceモデルがある場合)
        // $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();
        
        // モックデータ (DBがない場合のデバッグ用)
        $attendance = session("attendance_{$user->id}_{$today}", ['inTime' => null, 'outTime' => null]);

        return response()->json($attendance);
    }

    /**
     * 出勤・退勤アクション
     */
    public function punch(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type');
        $today = Carbon::today()->toDateString();
        $nowTime = Carbon::now()->format('H:i');

        // セッションまたはDBから現在の状態を取得
        $data = session("attendance_{$user->id}_{$today}", ['inTime' => null, 'outTime' => null]);

        if ($type === 'in') {
            if ($data['inTime']) {
                return response()->json(['message' => '既に出勤打刻済みです'], 400);
            }
            $data['inTime'] = $nowTime;
            $message = "おはようございます！ {$nowTime} に出勤しました。";
        } else {
            if (!$data['inTime']) {
                return response()->json(['message' => '出勤打刻がされていません'], 400);
            }
            if ($data['outTime']) {
                return response()->json(['message' => '既に退勤打刻済みです'], 400);
            }
            $data['outTime'] = $nowTime;
            $message = "お疲れ様でした！ {$nowTime} に退勤しました。";
        }

        // DBへの保存 (例: Attendance::updateOrCreate(...))
        session(["attendance_{$user->id}_{$today}" => $data]);

        return response()->json([
            'message' => $message,
            'data' => $data
        ]);
    }
}