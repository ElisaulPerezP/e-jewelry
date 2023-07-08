<?php

namespace App\Actions\imports;

use App\Http\Requests\Import\ImportRequest;
use App\Http\Resources\ImportResource;
use App\Imports\ProductsImport;
use App\Models\Import;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CreateImportRegisterAction
{
    public function execute(ImportRequest $request): ImportResource|JsonResponse
    {
        $import = new Import();
        $import->user_id = auth()->user()->id;
        $import->name = $request->name;
        $import->comments = $request->comments;
        $import->file_name = $request->file->getClientOriginalName();

        if ($request->hasFile('file')) {
            $name = Str::uuid() . '.' . $request->file('file')->extension();
            $import->file_uuid = $name;
            $request->file('file')->storeAs('importes', $name, 'public');
        }
        $import->status = 'uploaded';
        $import->save();
        Excel::import(new ProductsImport(), storage_path('/app/public/importes/' . $import->file_uuid), null, \Maatwebsite\Excel\Excel::XLSX);

        return new ImportResource($import);
    }
}
