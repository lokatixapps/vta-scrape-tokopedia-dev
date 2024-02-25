<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\ProductCategory;
use App\Models\Product;
use Auth;
use DataTables;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.index-product');
    }

    public function getProductData(Request $request)
    {
        $data = Product::with(['productCategory:id,name'])->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('data', function ($data) {
                $jsonData = $data->data;
                $decodedData = json_decode($jsonData, true);
                $stringData = json_encode($decodedData);
                $formattedData = substr($stringData, 0, 150);

                return $formattedData;
            })
            ->addColumn('action', function ($data) {
                $actionBtn = '
                    <a href="' . route('product.edit', $data->id) . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                    <a href="' . route('product.show', $data->id) . '" class="btn btn-info"><i class="bi bi-eye-fill"></i></a>
                    <form action="' . route('product.destroy', $data->id) . '"  method="POST">
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
            ->filter(function ($query) use ($request) {
                $keyword = $request->input('search.value');
                if (!empty($keyword)) {
                    $query->where(function ($subquery) use ($keyword) {
                        $subquery->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('data', 'like', '%' . $keyword . '%')
                            ->orWhereHas('productCategory', function ($productCategoryQuery) use ($keyword) {
                                $productCategoryQuery->where('name', 'like', '%' . $keyword . '%');
                            });
                    });
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = ProductCategory::orderBy('created_at', 'desc')->get();

        return view('dashboard.create-product', compact('productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'product_category_id' => ['required', Rule::in(ProductCategory::pluck('id')->toArray())],
                'name' => 'required|string|max:200',
            ],
            [
                'name.required' => 'Nama Produk wajib diisi.',
                'name.string' => 'Nama Produk wajib berupa teks.',
                'name.max' => 'Nama Produk maksimal 100 karakter.',
            ]
        );

        $scrapeAPI = new ScrapeController();
        $scrapeData = $scrapeAPI->scrapeProductData($request->name);

        if ($scrapeData) {
            $data = $scrapeData;
        } else {
            $dataDecode = json_decode($scrapeData);
            Log::error('[CUSTOMER] Terjadi kesalahan saat create data product.', [
                'error' => $dataDecode->error
            ]);

            return redirect()
                ->route('product.create')
                ->with('error', 'Mohon maaf, permintaan gagal.');
        }

        DB::beginTransaction();
        try {
            $product = Product::create([
                'product_category_id' => filter_var($request->product_category_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
                'name' => filter_var($request->name, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
                'data' => $data,
            ]);

            DB::commit();
            Log::info('[ADMIN] Berhasil create product.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('[ADMIN] Terjadi kesalahan saat create data product. (' . $e->getMessage() . ')');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal, mohon coba kembali.');
        }

        if ($product) {
            return redirect()
                ->route('product.index')
                ->with('message', 'Data Produk berhasil ditambah.');
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
        $product = Product::findOrFail($id);

        $locationData = $product->data;
        $data = json_decode($locationData, true);
        $locationCounts = [];

        // LOCATION CATEGORY
        foreach ($data['data'] as $productDetail) {
            $location = $productDetail['location'];

            if (!empty($location) && $location != "NULL") {
                if (!isset($locationCounts[$location])) {
                    $locationCounts[$location] = 1;
                } else {
                    $locationCounts[$location]++;
                }
            }
        }

        $formattedLocationGroupData = [];
        foreach ($locationCounts as $location => $count) {
            $formattedLocationGroupData[] = [
                'location' => $location,
                'total_items' => $count
            ];
        }
        // LOCATION CATEGORY

        // PRICE CATEGORY
        $groupedPrices = [];

        foreach ($data['data'] as $item) {
            $price = $item['price'];
            $group = '';

            if ($price < 500000) {
                $group = 'Rp.0 - Rp.500.000';
            } elseif ($price >= 500000 && $price < 1000000) {
                $group = 'Rp.500.000 - Rp.1.000.000';
            } elseif ($price >= 1000000 && $price < 1500000) {
                $group = 'Rp.1.000.000 - Rp.1.500.000';
            } else {
                $group = 'Above Rp.1.500.000';
            }

            if (!isset($groupedPrices[$group])) {
                $groupedPrices[$group] = ['items' => [], 'total_items' => 0];
            }
            $groupedPrices[$group]['items'][] = $item['title'];
            $groupedPrices[$group]['total_items']++;
        }

        $formattedPriceGroupData = [];

        foreach ($groupedPrices as $group => $items) {
            $formattedPriceGroupData[] = [
                'price_group' => $group,
                'items' => $items['items'],
                'total_items' => $items['total_items']
            ];
        }
        // PRICE CATEGORY

        // PRICE LOCATION CATEGORY
        $formattedPriceLocationGroupData = [];

        foreach ($data['data'] as $item) {
            $location = $item['location'];
            $price = $item['price'];

            if (!isset($processedData[$location])) {
                $processedData[$location] = ['location' => $location, 'price_data' => []];
            }
            $formattedPriceLocationGroupData[$location]['price_data'][] = $price;
        }
        // PRICE LOCATION CATEGORY

        return view('dashboard.show-product', compact('product', 'formattedLocationGroupData', 'formattedPriceGroupData', 'formattedPriceLocationGroupData'));
    }

    public function getDetailProductData(string $id)
    {
        $product = Product::with(['productCategory:id,name'])->where('id', $id)->first();
        $data = json_decode($product->data, true);

        $filteredData = array_filter($data['data'], function ($item) {
            return isset($item['title']);
        });

        return DataTables::of($filteredData)
            ->addIndexColumn()
            ->addColumn('title', function ($filteredData) {
                $titleProduct = $filteredData['title'];

                return $titleProduct;
            })
            ->addColumn('price', function ($filteredData) {
                $titleProduct = 'Rp.' . number_format($filteredData['price']);

                return $titleProduct;
            })
            ->addColumn('location', function ($filteredData) {
                $titleProduct = $filteredData['location'];

                return $titleProduct;
            })
            ->addColumn('url', function ($filteredData) {
                $urlBtn = '
                    <a href="' . $filteredData['url'] . '" class="btn btn-info"><i class="bi bi-eye-fill"></i></a>
                    ';
                return $urlBtn;
            })
            ->rawColumns(['url'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        return view('dashboard.edit-product', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:200',
                'data' => 'required|string|max:100000000',
            ],
            [
                'name.required' => 'Nama Produk wajib diisi.',
                'name.string' => 'Nama Produk wajib berupa teks.',
                'name.max' => 'Nama Produk maksimal 100 karakter.',
                'data.required' => 'Data Produk wajib diisi.',
                'data.string' => 'Data Produk wajib berupa teks.',
                'data.max' => 'Data Produk maksimal 100000000 karakter.',
            ]
        );

        $product = Product::findOrFail($id);

        DB::beginTransaction();
        try {
            $updateProduct = $product->update([
                'name' => filter_var($request->name, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
                'data' => filter_var($request->data, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES),
            ]);

            DB::commit();
            Log::info('[ADMIN] Berhasil update product.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('[ADMIN] Terjadi kesalahan saat update data product. (' . $e->getMessage() . ')');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal, mohon coba kembali.');
        }

        if ($updateProduct) {
            return redirect()
                ->route('product.index')
                ->with('message', 'Data Produk berhasil diubah.');
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
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()
            ->route('product.index')
            ->with('message', 'Data Produk berhasil dihapus.');
    }
}
