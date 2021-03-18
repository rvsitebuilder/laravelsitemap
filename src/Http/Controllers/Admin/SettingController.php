<?php

namespace Rvsitebuilder\Laravelsitemap\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Rvsitebuilder\Core\Models\CoreConfig;
use Rvsitebuilder\Laravelsitemap\Http\Requests\SaveSettingRequest;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $setting_data = [];
        $setting_data['laravelsitemap_COOKIES'] = config('rvsitebuilder/laravelsitemap.COOKIES');
        $setting_data['laravelsitemap_CONNECT_TIMEOUT'] = config('rvsitebuilder/laravelsitemap.CONNECT_TIMEOUT');
        $setting_data['laravelsitemap_TIMEOUT'] = config('rvsitebuilder/laravelsitemap.TIMEOUT');
        $setting_data['laravelsitemap_ALLOW_REDIRECTS'] = config('rvsitebuilder/laravelsitemap.ALLOW_REDIRECTS');
        $setting_data['laravelsitemap_leaving_out'] = config('rvsitebuilder/laravelsitemap.leaving_out');
        // $setting_data['laravelsitemap_execute_javascript'] = ConfigLib::getDbConfig('laravelsitemap_execute_javascript', false);
        // $setting_data['laravelsitemap_chrome_binary_path'] = ConfigLib::getDbConfig('laravelsitemap_chrome_binary_path', '');
        return view('rvsitebuilder/laravelsitemap::admin.laravelsitemap.setting', [
            'setting_data' => $setting_data,
        ]);
    }

    public function savesetting(SaveSettingRequest $request): JsonResponse
    {
        try {
            $params = [];
            parse_str($request->setting_data, $params);

            $valueCookies = false;
            if (isset($params['setting_cookies']) && 'on' == $params['setting_cookies']) {
                $valueCookies = true;
            }
            CoreConfig::updateOrCreate(
                ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_COOKIES'],
                ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_COOKIES', 'value' => $valueCookies]
            );

            if (isset($params['setting_connect_timeout'])) {
                $params['setting_connect_timeout'] = (is_numeric($params['setting_connect_timeout']) ? $params['setting_connect_timeout'] : 10);
                CoreConfig::updateOrCreate(
                    ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_CONNECT_TIMEOUT'],
                    ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_CONNECT_TIMEOUT', 'value' => $params['setting_connect_timeout']]
                );
            }
            if (isset($params['setting_timeout'])) {
                $params['setting_timeout'] = (is_numeric($params['setting_timeout']) ? $params['setting_timeout'] : 10);
                CoreConfig::updateOrCreate(
                    ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_TIMEOUT'],
                    ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_TIMEOUT', 'value' => $params['setting_timeout']]
                );
            }
            $valueAllowRedirects = false;
            if (isset($params['setting_allow_redirects']) && 'on' == $params['setting_allow_redirects']) {
                $valueAllowRedirects = true;
            }

            CoreConfig::updateOrCreate(
                ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_ALLOW_REDIRECTS'],
                ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_ALLOW_REDIRECTS', 'value' => $valueAllowRedirects]
            );

            if (isset($params['setting_leaving_out'])) {
                $params['setting_leaving_out'] = ('' != $params['setting_leaving_out'] ? $params['setting_leaving_out'] : '');
                CoreConfig::updateOrCreate(
                    ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_leaving_out'],
                    ['key' => 'rvsitebuilder/laravelsitemap.laravelsitemap_leaving_out', 'value' => $params['setting_leaving_out']]
                );
            }

            return response()->json([
                'msg' => 'Data has been save.',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            $errorMsg = 'false:' . $e->getMessage() . ' at line:' . $e->getLine();
            Log::error(sprintf('Error Msg %s at %s :: LINE %s ::  %s', $errorMsg, __FILE__, __LINE__, __METHOD__));

            return response()->json([
                'status' => 'error : ' . $errorMsg,
            ]);
        }
    }
}
