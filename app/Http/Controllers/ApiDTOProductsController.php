<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiDTOProductsController extends Controller
{
    public function export(): BinaryFileResponse
    {
        return Excel::download(new ProductsExport(), 'products.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
