<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\ProductCategory;
use DataTables;
use DB;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.index-product-category');
    }

    public function getProductCategoryData()
    {
        $data = ProductCategory::with('products')->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $actionBtn = '
                    <a href="' . route('product-category.edit', $data->id) . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                    <a href="' . route('product-category.show', $data->id) . '" class="btn btn-info"><i class="bi bi-eye-fill"></i></a>
                    <form action="' . route('product-category.destroy', $data->id) . '"  method="POST">
                    ' . csrf_field() . '
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-block"><i class="bi bi-trash"></i></button>
                    </form>
                    ';
                return $actionBtn;
            })
            ->addColumn('created_at', function ($data) {
                $createdAt = Carbon::parse($data->created_at)->format('Y-m-d H:i:s');

                return $createdAt;
            })
            ->addColumn('updated_at', function ($data) {
                $updatedAt = Carbon::parse($data->updated_at)->format('Y-m-d H:i:s');

                return $updatedAt;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create-product-category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:1000',
            ],
            [
                'name.required' => 'Nama Kategori Produk wajib diisi.',
                'name.string' => 'Nama Kategori Produk wajib berupa teks.',
                'name.max' => 'Nama Kategori Produk maksimal 100 karakter.',
                'description.required' => 'Deskripsi Kategori Produk wajib diisi.',
                'description.string' => 'Deskripsi Kategori Produk wajib berupa teks.',
                'description.max' => 'Deskripsi Kategori Produk maksimal 1000 karakter.',
            ]
        );

        DB::beginTransaction();
        try {
            $productCategory = ProductCategory::create([
                'name' => filter_var($request->name, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
                'description' => filter_var($request->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
            ]);

            DB::commit();
            Log::info('[ADMIN] Berhasil create product category.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('[ADMIN] Terjadi kesalahan saat create data product category. (' . $e->getMessage() . ')');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal, mohon coba kembali.');
        }

        if ($productCategory) {
            return redirect()
                ->route('product-category.index')
                ->with('message', 'Data Kategori Produk berhasil ditambah.');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal, mohon coba kembali.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productCategory = ProductCategory::findOrFail($id);

        return view('dashboard.edit-product-category', compact('productCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productCategory = ProductCategory::findOrFail($id);

        return view('dashboard.edit-product-category', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:1000',
            ],
            [
                'name.required' => 'Nama Kategori Produk wajib diisi.',
                'name.string' => 'Nama Kategori Produk wajib berupa teks.',
                'name.max' => 'Nama Kategori Produk maksimal 100 karakter.',
                'description.required' => 'Deskripsi Kategori Produk wajib diisi.',
                'description.string' => 'Deskripsi Kategori Produk wajib berupa teks.',
                'description.max' => 'Deskripsi Kategori Produk maksimal 1000 karakter.',
            ]
        );

        $productCategory = ProductCategory::findOrFail($id);

        DB::beginTransaction();
        try {
            $updateProductCategory = $productCategory->update([
                'name' => filter_var($request->name, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
                'description' => filter_var($request->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
            ]);

            DB::commit();
            Log::info('[ADMIN] Berhasil update product category.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('[ADMIN] Terjadi kesalahan saat update data product category. (' . $e->getMessage() . ')');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal, mohon coba kembali.');
        }

        if ($updateProductCategory) {
            return redirect()
                ->route('product-category.index')
                ->with('message', 'Data Kategori Produk berhasil diubah.');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal, mohon coba kembali.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productCategory = ProductCategory::findOrFail($id);

        $productCategory->delete();

        return redirect()
            ->route('product-category.index')
            ->with('message', 'Data Kategori Produk berhasil dihapus.');
    }
}
