<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $productCategory = ProductCategory::get();
        $product = Product::count();

        $productChart = Product::select(DB::raw("COUNT(*) as count, MONTH(products.created_at) as month"))
            ->whereRaw('YEAR(products.created_at) = YEAR(CURRENT_DATE)')
            ->groupBy(DB::raw('MONTH(products.created_at)'))
            ->orderBy(DB::raw('MONTH(products.created_at)'))
            ->get(['count', 'month']);

        return view('dashboard.index-dashboard', compact('productCategory', 'product', 'productChart'));
    }

    public function getProductData(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['productCategory:id,name'])->whereHas('productCategory', function ($query) use ($request) {
                return $query->where('id', $request->id);
            })->get();

            $index = 1;
            $data->each(function ($item) use (&$index) {
                $item->DT_RowIndex = $index++;

                $item->action = '
                    <a href="' . route('product.edit', $item->id) . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                    <a href="' . route('product.show', $item->id) . '" class="btn btn-info"><i class="bi bi-eye-fill"></i></a>
                    <form action="' . route('product.destroy', $item->id) . '"  method="POST">
                    ' . csrf_field() . '
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-block"><i class="bi bi-trash"></i></button>
                    </form>
                ';
            });

            return response()->json($data);
        }
    }
}
