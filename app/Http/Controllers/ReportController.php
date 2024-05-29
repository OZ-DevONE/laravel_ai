<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $reports = Report::where('user_id', $userId)->with('photo')->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function store(Request $request, $photoId)
    {
        $userId = Auth::id();
        
        // Валидация данных
        $request->validate([
            'reason' => 'required|string|max:255',
            'custom_reason' => 'nullable|string|max:1000',
        ]);

        // Проверка лимитов жалоб
        $totalReportsCount = Report::where('user_id', $userId)->count();
        $photoReportsCount = Report::where('user_id', $userId)->where('photo_id', $photoId)->count();

        if ($totalReportsCount >= 20) {
            return back()->withErrors(['limit' => 'Вы не можете оставить более 20 жалоб. Пожалуйста, дождитесь решения по вашим предыдущим жалобам.']);
        }

        if ($photoReportsCount >= 10) {
            return back()->withErrors(['limit' => 'Вы не можете оставить более 10 жалоб на эту фотографию. Пожалуйста, дождитесь решения по вашим предыдущим жалобам.']);
        }

        // Создание жалобы
        Report::create([
            'user_id' => $userId,
            'photo_id' => $photoId,
            'reason' => $request->reason,
            'custom_reason' => $request->custom_reason,
        ]);

        return back()->with('success', 'Ваша жалоба успешно отправлена. Пожалуйста, дождитесь решения по вашей жалобе.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $report = Report::findOrFail($id);

        if ($report->user_id != $userId) {
            abort(403, 'Вы не можете удалить эту жалобу.');
        }

        $report->delete();

        return back()->with('success', 'Жалоба успешно удалена.');
    }
}
