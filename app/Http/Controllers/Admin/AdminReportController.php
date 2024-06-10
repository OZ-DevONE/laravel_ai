<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class AdminReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Report::class)
            ->with(['photo', 'user']);

        // Фильтрация по статусу
        if ($request->has('filter.status') && $request->input('filter.status') !== null) {
            $query->where('status', $request->input('filter.status'));
        }

        // Фильтрация по дате
        if ($request->has('filter.created_at') && $request->input('filter.created_at') !== null) {
            $date = $request->input('filter.created_at');
            $query->whereDate('created_at', $date);
        }

        // Сортировка по количеству жалоб
        if ($request->has('sort') && $request->input('sort') === 'complaint_count') {
            $query->orderBy('complaint_count', 'desc');
        }

        // Сначала выводить жалобы с множественными жалобами
        $query->orderBy('complaint_count', 'desc');

        $reports = $query->paginate(10)->appends($request->query());

        return view('admin.reports.index', compact('reports'));
    }

    public function edit($id)
    {
        $report = Report::with('user')->findOrFail($id);
    
        return view('admin.reports.edit', compact('report'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'string|in:' . implode(',', Report::STATUSES) . '|max:255',
            'admin_comment' => 'nullable|array',
            'admin_comment.*' => 'nullable|string|max:1000',
        ]);
    
        $report = Report::findOrFail($id);
    
        foreach ($report->userReports as $userReport) {
            $status = $request->status[$userReport->user_id] ?? $userReport->status;
            $adminComment = $request->admin_comment[$userReport->user_id] ?? null;
    
            $userReport->update([
                'status' => $status,
                'admin_comment' => $adminComment,
            ]);
        }
    
        return redirect()->route('admin.reports.index')->with('success', 'Жалобы успешно обновлены.');
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Жалоба успешно удалена.');
    }

    public function show($id)
    {
        $report = Report::with(['user', 'photo'])->findOrFail($id);

        return view('admin.reports.show', compact('report'));
    }

}
