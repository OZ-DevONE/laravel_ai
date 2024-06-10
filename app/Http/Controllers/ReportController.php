<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $reports = QueryBuilder::for(Report::class)
            ->where('user_id', $userId)
            ->with(['user', 'photo'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::scope('created_after'),
                AllowedFilter::scope('created_before'),
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('reports.index', compact('reports'));
    }

    public function store(Request $request, $photoId)
    {
        $userId = Auth::id();
        $totalReportsByUser = Report::where('user_id', $userId)->count();
        if ($totalReportsByUser > 20) {
            return back()->with('error', 'Вы достигли лимита на отправку жалоб. Пожалуйста, дождитесь решения по текущим жалобам.');
        }
    
        $reportsOnSamePhoto = Report::where('user_id', $userId)->where('photo_id', $photoId)->count();
        if ($reportsOnSamePhoto >= 10) {
            return back()->with('error', 'Вы уже отправили максимальное количество жалоб на эту фотографию.');
        }
        
        $request->validate([
            'reason' => 'required|string|in:Нарушение цензуры,Оскорбительный контент,Спам,Прочее|max:255',
            'custom_reason' => 'nullable|string|max:200',
        ]);
    
        $existingReport = Report::where('photo_id', $photoId)->where('reason', $request->reason)->first();
    
        if ($existingReport) {
            if ($request->reason === 'Прочее') {
                $existingReport->update(['custom_reason' => $request->custom_reason]);
            }
            $existingReport->increment('complaint_count');
            return back()->with('success', 'Ваша жалоба добавлена к существующей. Пожалуйста, дождитесь решения.');
        } else {
            Report::create([
                'user_id' => $userId,
                'photo_id' => $photoId,
                'reason' => $request->reason,
                'custom_reason' => $request->custom_reason,
                'complaint_count' => 1,
            ]);
            return back()->with('success', 'Ваша жалоба успешно отправлена. Пожалуйста, дождитесь решения по вашей жалобе.');
        }
    }    
    

    public function destroy($id)
    {
        $userId = Auth::id();
        $report = Report::findOrFail($id);

        if ($report->user_id != $userId) {
            abort(403, 'Вы не можете удалить эту жалобу.');
        }

        if ($report->status === 'Новая Жалоба') {
            $report->delete();
            return back()->with('success', 'Жалоба успешно удалена.');
        } else {
            return back()->withErrors(['limit' => 'Вы не можете удалить эту жалобу, так как она уже обработана или редактировалась.']);
        }
    }

    public function edit($id)
    {
        $userId = Auth::id();
        $report = Report::where('id', $id)->where('user_id', $userId)->firstOrFail();

        if ($report->reason != 'Прочее' || $report->status != 'Новая Жалоба') {
            return redirect()->route('reports.index')->withErrors(['edit' => 'Вы можете редактировать только жалобы с причиной "Прочее" и статусом "Новая Жалоба".']);
        }

        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $report = Report::where('id', $id)->where('user_id', $userId)->firstOrFail();

        if ($report->reason != 'Прочее' || $report->status != 'Новая Жалоба') {
            return redirect()->route('reports.index')->withErrors(['edit' => 'Вы можете редактировать только жалобы с причиной "Прочее" и статусом "Новая Жалоба".']);
        }

        $request->validate([
            'reason' => 'required|string|in:Нарушение цензуры,Оскорбительный контент,Спам,Прочее|max:255',
            'custom_reason' => 'nullable|string|max:200',
        ]);

        $report->update([
            'reason' => $request->reason,
            'custom_reason' => $request->custom_reason,
        ]);

        return redirect()->route('reports.index')->with('success', 'Жалоба успешно обновлена.');
    }
}
