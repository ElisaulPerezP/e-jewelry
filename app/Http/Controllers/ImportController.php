<?php

namespace App\Http\Controllers;

use App\Actions\imports\ChangeImportStatusAction;
use App\Actions\imports\CreateImportRegisterAction;
use App\Actions\imports\GetImportsAction;
use App\Http\Requests\Import\ImportRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Resources\ImportResource;
use App\Models\Import;

class ImportController extends Controller
{
    public function index(IndexRequest $request)
    {
        return (new GetImportsAction())->execute($request);
    }

    public function store(ImportRequest $request)
    {
        return (new CreateImportRegisterAction())->execute($request);
    }

    public function show(Import $import)
    {
        return new ImportResource($import);
    }

    public function update(Import $import)
    {
        return (new ChangeImportStatusAction())->execute($import);
    }
}
