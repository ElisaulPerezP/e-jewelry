<?php

namespace App\Actions\Reports;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class GetPaginatedReportsAction
{
    public function __invoke(IndexRequest $request): AnonymousResourceCollection
    {
        Cache::forget('reports');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');

        $paginatedReports = Cache::rememberForever('reports', function () use ($currentPage, $perPage) {
            $query = Report::query();

            return $query->paginate((int)$perPage, ['id', 'file_path', 'user_id', 'created_at'], 'page', (int)$currentPage);
        });

        return ReportResource::collection($paginatedReports);
    }
}
