<?php

namespace App\Http\Controllers\Reports;

use App\Actions\Reports\GetCartItemsPayedAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiAdministrationController extends Controller
{
    public function indexCartItemsPayed(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetCartItemsPayedAction())($request);
    }
}
