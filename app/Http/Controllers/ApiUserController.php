<?php

namespace App\Http\Controllers;

use App\Actions\Users\GetPaginatedUsersAction;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiUserController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetPaginatedUsersAction())($request);
    }
}
