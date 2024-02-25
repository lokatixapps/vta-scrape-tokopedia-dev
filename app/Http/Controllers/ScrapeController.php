<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class ScrapeController extends Controller
{
    public function scrapeProductData($product)
    {
        $endpoint = 'http://127.0.0.1:5000/scrape';

        try {
            $response = Http::timeout(60)->get($endpoint, [
                'product' => $product
            ]);

            $response->throw();

            Log::info('[SCRAPE SISTEM] Berhasil get data product.');
        } catch (ConnectionException $e) {
            Log::error('[SCRAPE] Terjadi kesalahan koneksi saat get data product. (' . $e->getMessage() . ')');

            return (object) ['success' => false, 'data' => null, 'error' => 'Terjadi kesalahan koneksi.'];
        } catch (RequestException $e) {
            Log::error('[SCRAPE] Terjadi kesalahan permintaan saat get data product. (' . $e->getMessage() . ')');

            return (object) ['success' => false, 'data' => null, 'error' => 'Terjadi kesalahan permintaan.'];
        } catch (\Exception $e) {
            Log::error('[SCRAPE] Terjadi kesalahan umum saat get data product. (' . $e->getMessage() . ')');

            return (object) ['success' => false, 'data' => null, 'error' => 'Terjadi kesalahan umum.'];
        }

        if ($response->failed()) {
            return (object) ['success' => false, 'data' => null, 'error' => 'Mohon maaf, server Scrape gagal menerima permintaan.'];
        }

        $response = $response->body();

        return $response ? $response : (object) ['success' => false, 'data' => null, 'error' => 'Gagal memproses respons JSON.'];
    }
}
