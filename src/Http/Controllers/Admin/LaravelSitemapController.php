<?php

namespace Rvsitebuilder\Laravelsitemap\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Rvsitebuilder\Laravelsitemap\Lib\CreateSiteMap;

class LaravelSitemapController extends Controller
{
    private $path_sitemap = 'sitemap.xml';

    /**
     * Display genarate sitemap page.
     */
    public function index(Request $request)
    {
        $last_created = CreateSiteMap::getSitemapFileDescription($this->path_sitemap);

        return view('rvsitebuilder/laravelsitemap::admin.laravelsitemap.index', [
            'url' => secure_url('/'),
            'last_created' => $last_created,
        ]);
    }

    public function generate(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('rvsitebuilder:resetserviceprovider');

            // Genarate Sitemap
            CreateSiteMap::createSitemapFile($this->path_sitemap);

            // get file created
            $last_created = CreateSiteMap::getSitemapFileDescription($this->path_sitemap);

            // Response data
            return response()->json([
                'msg' => 'The item has been created.',
                'status' => 'success',
                'last_created' => $last_created,
            ], 200);
        } catch (\Exception $e) {
            $errorMsg = 'false:' . $e->getMessage() . ' at line:' . $e->getLine();
            Log::error(sprintf('Error Msg %s at %s :: LINE %s ::  %s', $errorMsg, __FILE__, __LINE__, __METHOD__));

            return response()->json([
                'status' => 'error : ' . $errorMsg,
            ]);
        }
    }

    public function download()
    {
        if (file_exists($this->path_sitemap)) {
            return response(file_get_contents($this->path_sitemap))
                ->header('Content-type', 'text/xml')
                ->header('Content-Disposition', 'attachment; filename="' . $this->path_sitemap . '');
        }
    }
}
