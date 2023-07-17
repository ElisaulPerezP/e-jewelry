<?php

namespace App\Actions\imports;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\CartItemResource;
use App\Models\Import;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class GetImportsAction
{
    public function __invoke(IndexRequest $request): AnonymousResourceCollection
    {
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        $activeImports = $request->query('flag', '1');

        $query = Import::where('user_id', Auth::user()->id);

        if ($activeImports === 1) {
            $query->whereIn('status', ['uploaded', 'reconciled']);
        }

        $query->whereExists(function ($query) use ($searching) {
            $query->select('id')
                ->from('imports')
                ->whereColumn('imports.name', 'imports.comments')
                ->where('name', 'like', '%' . $searching . '%');
        });

        $paginatedImports = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return CartItemResource::collection($paginatedImports);
    }
}
