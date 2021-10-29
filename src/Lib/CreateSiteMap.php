<?php

namespace Rvsitebuilder\Laravelsitemap\Lib;

use Illuminate\Support\Facades\Date;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class CreateSiteMap
{
    /**
     * getSitemapFileDescription.
     *
     * @param path_sitemap = path to sitemap file
     * @param mixed $path
     *
     * @return
     */
    public static function getSitemapFileDescription($path): string
    {
        $last_created = '';
        if (file_exists($path)) {
            $dt = date('Y-m-d H:i:s', filemtime($path));
            $dt = Date::parse($dt)->timezone('Asia/Bangkok')->format('d/m/Y H:i:s');
            $last_created = '<a href="' . secure_url('/') . '/' . $path . "\" target=\"_blank\">${path}</a> was last modified: " . $dt . '
      <a href="' . route('admin.laravelsitemap.laravelsitemap.download') . '" target="_blank">Download</a>';
        }

        return $last_created;
    }

    public static function createSitemapFile($pathSitemap): bool
    {
        SitemapGenerator::create(secure_url('/'))->hasCrawled(function (Url $url) {
            $arr_leaving_out = [];
            $laravelsitemap_leaving_out = config('rvsitebuilder.laravelsitemap.leaving_out');
            if (trim($laravelsitemap_leaving_out) != '') {
                $arr_leaving_out = explode(',', $laravelsitemap_leaving_out);
                if (\count($arr_leaving_out) > 0) {
                    //trim space in all array
                    $arr_leaving_out = array_map('trim', $arr_leaving_out);
                }
            }

            if (\in_array($url->segment(1), $arr_leaving_out, true)) {
                return;
            }

            return $url;
        })->writeToFile($pathSitemap);

        return true;
    }
}
