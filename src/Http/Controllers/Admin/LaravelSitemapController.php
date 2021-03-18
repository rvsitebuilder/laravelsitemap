<?php

namespace Rvsitebuilder\Laravelsitemap\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url as SitemapUrl;

class LaravelSitemapController extends Controller
{
    private $path_sitemap = 'sitemap.xml';

    /**
     * Display genarate sitemap page.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $last_created = $this->getSitemapFileDescription($this->path_sitemap);

        return view('rvsitebuilder/laravelsitemap::admin.laravelsitemap.index', [
            'url' => secure_url('/'),
            'last_created' => $last_created,
        ]);
    }

    public function generate(Request $request)
    {
        try {
            // Clear cache
            $this->clearcache();

            // Genarate Sitemap
            $this->createSitemapFile();

            // get file created
            $last_created = $this->getSitemapFileDescription($this->path_sitemap);

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

    /**
     * clearcache.
     *
     * @param
     *
     * @return
     */
    public function clearcache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('rvsitebuilder:resetserviceprovider');

        return true;
    }

    /**
     * createSitemapFile.
     *
     * @param
     *
     * @return
     */
    public function createSitemapFile()
    {
        SitemapGenerator::create(secure_url('/'))->hasCrawled(function (SitemapUrl $url) {
            $arr_leaving_out = [];
            $laravelsitemap_leaving_out = config('rvsitebuilder/laravelsitemap.leaving_out');
            if ('' != trim($laravelsitemap_leaving_out)) {
                $arr_leaving_out = explode(',', $laravelsitemap_leaving_out);
                if (\count($arr_leaving_out) > 0) {
                    $arr_leaving_out = array_map('trim', $arr_leaving_out); //trim space in all srray
                }
            }

            if (\in_array($url->segment(1), $arr_leaving_out)) {
                return;
            }

            return $url;
        })->writeToFile($this->path_sitemap);

        return true;
    }

    /**
     * getSitemapFileDescription.
     *
     * @param path_sitemap = path to sitemap file
     * @param mixed $path
     *
     * @return
     */
    public function getSitemapFileDescription($path)
    {
        $last_created = '';
        if (file_exists($path)) {
            $dt = date('Y-m-d H:i:s', filemtime($path));
            $dt = Date::parse($dt)->timezone('Asia/Bangkok')->format('d/m/Y H:i:s');
            $last_created = '<a href="' . secure_url('/') . '/' . $path . "\" target=\"_blank\">$path</a> was last modified: " . $dt . ' 
      <a href="' . route('admin.laravelsitemap.laravelsitemap.download') . '" target="_blank">Download</a>';
        }

        return $last_created;
    }
}
