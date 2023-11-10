<?php

use Illuminate\Support\Str;
use App\Utilities\ApiJsonResponse;

if (!function_exists('api')) {
    /**
     * @param array|string|null $data
     * @return ApiJsonResponse
     */
    function api(array|string|null $data = []): ApiJsonResponse
    {
        return new ApiJsonResponse($data);
    }
}
if (!function_exists('arrayOnly')) {
    function

    arrayOnly(array $data, array $columns)
    {

        $result = [];

        foreach ($columns as $column) {
            if (array_key_exists($column, $data)) {
                $result[$column] = $data[$column];
            }
        }
        return $result;
    }
}

if (!function_exists('getTransactionId')) {
    function getTransactionId()
    {
        return md5(microtime() . rand());
    }
}
if (!function_exists('getUniqueNumber')) {
    function getUniqueNumber()
    {
        return md5(microtime() . rand());
    }
}
if (!function_exists('condBuilder')) {
    function condBuilder(string $column, string $value, string $type = 'LIKE')
    {
        return [
            [$column, $type, $value]
        ];
    }
}
// if (!function_exists('getTransactionTypes')) {
//     function getTransactionTypes()
//     {
//         return \App\Enums\Transactions::TRANSACTION_TYPES;
//     }
// }

if (!function_exists("auth_user")) {
    function auth_user()
    {
        if (auth()->check()) {
            return auth()->user();
        }
    }
}
if (!function_exists('countSellerUnderReview')) {
    function countSellerUnderReview()
    {
        $result = App\Models\User::all()->where('seller_status', 'under_review')->count();
        return $result;
    }
}
if (!function_exists('parseHtmlToText')) {
    function parseHtmlToText($html)
    {
        return str_replace("&nbsp;", " ", strip_tags($html));
    }
}
if (!function_exists('separateString')) {
    function separateString($str, $eachBlockLength, $separator = '-')
    {
        return implode($separator, str_split($str, $eachBlockLength));
    }
}
if (!function_exists('secondToMilliSecond')) {
    function secondToMilliSecond($sec)
    {
        $milliSecond = $sec * 1000;
        if ($milliSecond == 0) {
            return '00000';
        }

        return $milliSecond;
    }
}

if(!function_exists('response_array')) {
    function response_array($data, int $statusCode = 200){
        return response()->json($data)->setStatusCode($statusCode);
    }
}

if (!function_exists('auth_user')) {
    function auth_user()
    {
        return auth()->user();
    }
}

if (!function_exists('uri_concat')) {
    function uri_concat(... $uris): string
    {
        $urlManager = new \App\Utilities\UrlManager();

        foreach ($uris as $uri) {
            if (blank($uri)) {
                continue;
            }

            $urlManager->concat($uri);
        }

        $uri = $urlManager->getUrl();
        unset($urlManager);

        return $uri;

    }
}

if (!function_exists('str_unique')) {
    /**
     * @param int $length
     * @return string
     */
    function str_unique(int $length = 16): string
    {
        $side = rand(0,1); // 0 = left, 1 = right
        $salt = rand(0,9);
        $len = $length - 1;
        $string = Str::random($len <= 0 ? 7 : $len);

        $separatorPos = (int) ceil($length/4);

        $string = $side === 0 ? ($salt . $string) : ($string . $salt);
        $string = substr_replace($string, '-', $separatorPos, 0);

        return substr_replace($string, '-', negative_value($separatorPos), 0);
    }
}

if (!function_exists('negative_value')) {
    /**
     * @param int|float $value
     * @param bool $float
     * @return int|float
     */
    function negative_value(int|float $value, bool $float = false): int|float
    {
        if ($float) {
            $value = (float) $value;
        }

        return 0 - abs($value);
    }
}
