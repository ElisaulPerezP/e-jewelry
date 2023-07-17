<?php

namespace App\Http\Controllers;

use App\Actions\Reports\GetPaginatedReportsAction;
use App\Exports\DeliveryReportExport;
use App\Http\Requests\IndexRequest;
use App\Jobs\MailerExportLink;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiDTOReportController extends Controller
{
    public function export(): RedirectResponse
    {
        $filepath = Str::uuid() . '.xlsx';

        (new DeliveryReportExport())->queue($filepath, 'reports')->chain([
            new MailerExportLink(request()->user()),
        ]);
        Report::create([
            'file_path' => $filepath,
            'user_id'=> request()->user()->id,
        ]);

        return back()->withSuccess('Reporte iniciado: ' . $filepath);
    }
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetPaginatedReportsAction())($request);
    }

    public function download(string $filePath): StreamedResponse
    {
        return Storage::disk('reports')->download($filePath);
    }
}
