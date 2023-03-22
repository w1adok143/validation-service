<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use SwaggerLume\Exceptions\SwaggerLumeException;

if (!function_exists('success')) {
    function success(array $data = [], int $status = 200, array $headers = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ], $status, $headers);
    }
}

if (!function_exists('error')) {
    function error(\Exception|\Throwable $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'message' => !empty($e->getMessage()) ? $e->getMessage() : null,
                'code' => $e->getCode()
            ]
        ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400);
    }
}

if (!function_exists('errorDspHandler')) {
    function errorDspHandler(DspHandlerException $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'indexDetail' => $e->getErrorDetailIndex(),
                'indexOperation' => $e->getErrorOperationIndex(),
                'nameOperation' => $e->getErrorOperationName(),
                'handler' => $e->getErrorHandler(),
                'message' => $e->getMessage()
            ]
        ], $e->getStatusCode());
    }
}

if (!function_exists('lang')) {
    function lang(): string
    {
        return in_array(request('lang'), ['ru', 'ua']) ? request('lang') : 'ua';
    }
}

if (!function_exists('multilang')) {
    function multilang(string $key, array $arr = []): ?string
    {
        $config = Config::get('lang' . '/' . lang() . '/' . 'message');

        if (empty($config[$key])) {
            return null;
        }

        $text = $config[$key];

        if (!is_string($text)) {
            return null;
        }

        foreach ($arr as $value) {
            $text = str($text)->replaceFirst(':value', $value)->toString();
        }

        return $text;
    }
}

if (!function_exists('swagger_asset')) {
    function swagger_asset(string $asset): string
    {
        $file = swagger_ui_dist_path($asset);

        if (!file_exists($file)) {
            throw new SwaggerLumeException(sprintf('Requested L5 Swagger asset file (%s) does not exists', $asset));
        }
        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $secure = true;
        } else if(env('APP_ENV') !== 'local') {
            $secure = true;
        } else {
            $secure = false;
        }

        return route('swagger-lume.asset', ['asset' => $asset, 'v' => md5($file)], $secure);
    }
}

if (!function_exists('swagger_url')) {
    function swagger_url(string $path): string
    {
        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $secure = true;
        } else if(env('APP_ENV') !== 'local') {
            $secure = true;
        } else {
            $secure = false;
        }

        return url(parse_url($path, PHP_URL_PATH), [], $secure);
    }
}