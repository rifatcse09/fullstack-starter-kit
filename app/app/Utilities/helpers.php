<?php
declare(strict_types=1);

use App\Enums\MixpanelEvent;
use App\Enums\PlanFeature;
use App\Exceptions\FeatureNotAllowedException;
use App\Models\Plan;
use App\Services\FeatureFlag\Feature;
use App\Services\Mixpanel\MixpanelTrack;
use App\Utilities\ApiJsonResponse;
use Carbon\Carbon;
use CuyZ\Valinor\Mapper\MappingError;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Shop;
use Illuminate\Contracts\Support\Arrayable;

if (!function_exists('shop')) {

    /**
     * @param $guard
     * @return Shop|null
     */
    function shop($guard = null): ?Shop
    {
        /**
         * @var Shop $shop
         */
        $shop = auth($guard)->user();

        return $shop;
    }
}

if (!function_exists('admin_api')) {
    function admin_api(): string
    {
        return '/admin/api/' . env('SHOPIFY_API_VERSION');
    }
}

if (!function_exists('json_parse')) {
    /**
     * @param string $data
     * @param bool $exception
     * @return array
     * @throws Exception
     */
    function json_parse(string $data, bool $exception = true): array
    {
        $data = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            if ($exception) {
                throw new \Exception('Invalid JSON, Failed to parse!');
            }
            $data = [];
        }

        return $data;
    }
}

if (!function_exists('should_hold_for_rate_limit_ql')) {
    /**
     * @param array|object $graphResponse
     * @return bool
     */
    function should_hold_for_rate_limit_ql(array|object $graphResponse): bool
    {
        $remaing = Arr::get($graphResponse, 'body.extensions.cost.throttleStatus.currentlyAvailable', 0);
        // for each shopify graph ql mutation cost will be 10 points, so we will hold if remaining is less or equal to 10
        return $remaing <= 10;
    }
}

if (!function_exists('load_graphql_array_schema')) {
    /**
     * @param string $path
     * @param array $data
     * @return array
     * @throws Exception
     */
    function load_graphql_array_schema(string $path, array $data = []): array
    {
        extract($data, EXTR_SKIP);
        $path = 'graph/' . str_replace('.', '/', $path) . '.array.php';
        $path = resource_path('views/' . $path);

        if (!file_exists($path)) {
            throw new \Exception("File not found for tcp response");
        }

        return require $path;
    }
}

if (!function_exists('load_graphql_blade_schema')) {
    /**
     * @param string $path
     * @param array $data
     * @return string
     * @throws Exception
     */
    function load_graphql_blade_schema(string $path, array $data = []): string
    {
        $location = resource_path('views/graph/' . str_replace('.', '/', $path) . '.blade.php');

        if (!file_exists($location)) {
            throw new \Exception("File not found for tcp response");
        }

        return view('graph.' . $path, $data)->render();
    }
}

if (!function_exists('api')) {
    /**
     * @param array|Arrayable|string|null $data
     * @return ApiJsonResponse
     */
    function api(array|Arrayable|string|null $data = []): ApiJsonResponse
    {
        return new ApiJsonResponse($data);
    }
}

if (!function_exists('get_media_prefix_directory_name')) {
    /**
     * @return string
     */
    function get_media_prefix_directory_name(): string
    {
        return Carbon::today()->format('d_m_Y') . '_media';
    }
}

if (!function_exists('carbon')) {
    /**
     * @param string|null $date
     * @param string $timezone
     * @return Carbon
     */
    function carbon(string $date = null, string $timezone = 'UTC'): Carbon
    {
        if (!$date) {
            return Carbon::now($timezone);
        }

        return (new Carbon($date, $timezone));
    }
}

if (!function_exists('pagination_meta')) {

    /**
     * @param AbstractPaginator $paginator
     * @return array
     */
    function pagination_meta(AbstractPaginator $paginator): array
    {
        return [
            'cur_page_total' => $paginator->count(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'has_more' => $paginator->hasMorePages(),
            'next_page_url' => $paginator->nextPageUrl(),
            'total' => $paginator->total()
        ];
    }
}

if (!function_exists('cursor_pagination_meta')) {
    /**
     * @param CursorPaginator $paginator
     * @return array
     */
    function cursor_pagination_meta(CursorPaginator $paginator): array
    {
        return [
            'cur_page_total' => $paginator->count(),
            'has_more' => $paginator->hasMorePages(),
            'is_last_page' => $paginator->onLastPage(),
            'next_page_url' => $paginator->nextPageUrl(),
            'previous_page_url' => $paginator->previousPageUrl(),
        ];
    }
}

if (!function_exists('upper_case_snake_case_to_sentence')) {

    /**
     * @param string $sentence
     * @return string
     */
    function upper_case_snake_case_to_sentence(string $sentence): string
    {
        return Str::ucfirst(Str::lower(Str::replace('_', ' ', $sentence)));
    }
}

if (!function_exists('discount')) {

    /**
     * @return string
     */
    function discount(): string
    {
        return 'RVXP-' . Str::upper(Str::random(6));
    }
}

if (!function_exists('url_concat')) {
    function url_concat(...$uris): string
    {
        /**
         * @var \App\Utilities\UrlManager $urlManager
         */
        $urlManager = app(\App\Utilities\UrlManager::class);

        foreach ($uris as $uri) {
            if (blank($uri)) {
                continue;
            }

            $urlManager->concat($uri);
        }

        $uri = $urlManager->getUrl();
        $urlManager->setBaseUrl('');

        return $uri;

    }
}

if (!function_exists('backend_url')) {
    /**
     * @param $uri
     * @return string
     */
    function backend_url($uri): string
    {
        return url_concat(env('APP_URL', 'http://localhost:8008'), env('ROUTE_BACKEND_PREFIX', '/-'), $uri);
    }
}

if (!function_exists('app_url')) {
    function app_url($uri): string
    {
        return url_concat(env('APP_URL', 'http://localhost:8008'), $uri);
    }
}

if (!function_exists('from_gid')) {
    /**
     * @param $gid
     * @props int $id
     * @props string $type
     * @return ?object
     */
    function from_gid($gid): ?object
    {
        if (!str_contains($gid, 'gid://')) {
            return null;
        }

        $data = [];

        $extractedData = explode('/', substr($gid, strpos($gid, 'gid') + 6));

        $data['node'] = $extractedData[1];
        $data['id'] = (int)$extractedData[2];

        return (object)$data;

    }
}

if (!function_exists('load_data')) {
    /**
     * @param string $path
     * @param array $data
     * @return array
     * @throws Exception
     */
    function load_data(string $path, array $data = []): array
    {
        extract($data, EXTR_SKIP);
        $path = 'data/' . str_replace('.', '/', $path) . '.data.php';
        $file = resource_path($path);

        if (!file_exists($file)) {
            throw new \Exception("Data file not found");
        }

        return require $file;
    }
}


if (!function_exists('slug')) {
    /**
     * @param string|null $string
     * @return string
     */
    function slug(string $string = null): string
    {
        return Str::slug($string);
    }
}


if (!function_exists('rating_show')) {
    /**
     * @param string $ratingValue
     * @return string
     */
    function rating_show(string $ratingValue = '1'): string
    {
        return match ($ratingValue) {
            '5' => "★★★★★",
            '4' => "★★★★☆",
            '3' => "★★★☆☆",
            '2' => "★★☆☆☆",
            default => "★☆☆☆☆",
        };
    }
}

if (!function_exists('text_replacer')) {

    /**
     * @param string $text
     * @param array $data
     * @return string
     */
    function text_replacer(string $text, array $data): string
    {
        if (array_is_list($data)) {
            return $text;
        }

        $replacer = [];

        foreach ($data as $key => $value) {
            $replacer['[' . strtolower($key) . ']'] = $value;
        }

        return strtr($text, $replacer);
    }
}

if (!function_exists('signed_url')) {

    /**
     * @param string $url
     * @return string
     */
    function signed_url(string $url): string
    {
        $salt = config('app.key');

        $hashString = $salt . '::' . $url;
        $urlData = parse_url($url);
        parse_str($urlData['query'] ?? '', $query);

        $hash = sha1($hashString);
        $query['hash'] = $hash;

        $query = http_build_query($query);

        return urldecode($urlData['scheme'] . '://' . $urlData['host'] . (isset($urlData['port']) ? ':' . $urlData['port'] : '') . $urlData['path'] . '?' . $query);

    }
}


if (!function_exists('validate_signed_url')) {

    /**
     * @param string $url
     * @return bool
     */
    function validate_signed_url(string $url): bool
    {
        $salt = config('app.key');

        $parsedUrl = parse_url($url);

        if (!isset($parsedUrl['query'])) {
            return false;
        }

        parse_str($parsedUrl['query'], $query);

        if (!isset($query['hash'])) {
            return false;
        }

        $hash = $query['hash'];

        unset($query['hash']);
        $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . (isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '') . $parsedUrl['path'] . (!empty($query) ? '?' . http_build_query($query) : '');

        $hashString = $salt . '::' . $url;

        return sha1($hashString) === $hash;

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
            $value = (float)$value;
        }

        return 0 - abs($value);
    }
}


if (!function_exists('feature_allows')) {
    /**
     * @param PlanFeature $feature
     * @param Shop|null $shop
     * @return void
     * @throws FeatureNotAllowedException
     */
    function feature_allows(PlanFeature $feature, ?Shop $shop = null): void
    {
        /**
         * @var Feature $featureFlag
         */
        $featureFlag = app(Feature::class);

        $featureFlag->allows($feature, $shop);
    }
}

if (!function_exists('feature_enabled')) {
    /**
     * @param PlanFeature $feature
     * @param ?Shop $shop
     * @return bool
     */
    function feature_enabled(PlanFeature $feature, ?Shop $shop = null): bool
    {
        /**
         * @var Feature $featureFlag
         */
        $featureFlag = app(Feature::class);

        return $featureFlag->hasEnabled($feature, $shop);
    }
}

if (!function_exists('feature_get')) {
    /**
     * @param PlanFeature $feature
     * @param Shop|null $shop
     * @return mixed
     */
    function feature_get(PlanFeature $feature, ?Shop $shop = null): mixed
    {
        /**
         * @var Feature $featureFlag
         */
        $featureFlag = app(Feature::class);

        return $featureFlag->getFlagValue($feature, $shop);
    }
}

if (!function_exists('is_trail_active')) {
    /**
     * @param Shop $shop
     * @return bool
     */
    function is_trail_active(Plan $plan, Shop $shop): bool
    {
        /**
         * @var Feature $featureFlag
         */
        $featureFlag = app(Feature::class);

        return $featureFlag->isTrialActive($plan, $shop);
    }
}


if (!function_exists('url_replacer')) {

    /**
     * @param string $url
     * @param array $attr
     * @return string
     */
    function url_replacer(string $url, array $attr): string
    {
        if (array_is_list($attr)) {
            return $url;
        }

        $replacer = [];

        foreach ($attr as $key => $value) {
            $replacer['{' . strtolower($key) . '}'] = $value;
        }

        return strtr($url, $replacer);
    }
}
if (!function_exists('str_unique')) {
    /**
     * @param int $length
     * @return string
     */
    function str_unique(int $length = 16): string
    {
        $side = rand(0, 1); // 0 = left, 1 = right
        $salt = rand(0, 9);
        $len = $length - 1;
        $string = Str::random($len <= 0 ? 7 : $len);

        $separatorPos = (int)ceil($length / 4);

        $string = $side === 0 ? ($salt . $string) : ($string . $salt);
        $string = substr_replace($string, '-', $separatorPos, 0);

        return substr_replace($string, '-', negative_value($separatorPos), 0);
    }
}

if (!function_exists('download_from_url')) {

//    function download_from_url(string $url, string $prefix = ''): ?string
//    {
//        if (! $stream = @fopen($url, 'r')) {
//            throw new \Exception('Can not open file from ' . $url);
//        }
//
//        $tempFile = tempnam(sys_get_temp_dir(), $prefix);
//
//        if (file_put_contents($tempFile, $stream)) {
//            return $tempFile;
//        }
//
//        return null;
//    }


    /**
     * @param string $url
     * @param string $prefix
     * @return string|null
     * @throws Exception
     */
    function download_from_url(string $url, string $prefix = ''): ?string
    {
        $tempFile = tempnam(sys_get_temp_dir(), $prefix);

        if (!copy($url, $tempFile)) {
            throw new \Exception('Unable to download file from ' . $url);
        }

        return $tempFile;
    }

}


if (!function_exists('sanitizeAndCapitalizeName')) {
    /**
     * @param string $text
     * @return string
     */
    function sanitizeAndCapitalizeName(string $text): string
    {
        return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
    }
}

if (!function_exists('mixpanel_event_enqueue')) {
    /**
     * @param Shop $shop
     * @param MixpanelEvent $event
     * @param array $properties
     * @return void
     */
    function mixpanel_event_enqueue(Shop $shop, MixpanelEvent $event, array $properties = []): void
    {
        /**
         * @var MixpanelTrack $mixpanel
         */
        $mixpanel = app(MixpanelTrack::class);

        $mixpanel->eventEnqueue($shop, $event, $properties);
    }
}

if (!function_exists('str_inject_before')) {

    /**
     * @param string $contents
     * @param string $text
     * @param string $find
     * @param bool $newline
     * @return string
     */
    function str_inject_before(string $contents, string $text, string $find, bool $newline = true): string
    {
        $bodyPos = strpos($contents, $find);

        if ($bodyPos === false) {
            return $contents;
        }

        if ($newline) {
            $text = $text . PHP_EOL;
        }

        return substr_replace($contents, $text, $bodyPos, 0);
    }
}

if (!function_exists('str_inject_after')) {

    /**
     * @param string $contents
     * @param string $text
     * @param string $find
     * @param bool $newline
     * @return string
     */
    function str_inject_after(string $contents, string $text, string $find, bool $newline = true): string
    {
        $bodyPos = strpos($contents, $find);

        if ($bodyPos === false) {
            return $contents;
        }

        $bodyPos += strlen($find);

        if ($newline) {
            $text = PHP_EOL . $text;
        }

        return substr_replace($contents, $text, $bodyPos, 0);
    }
}

if (!function_exists('str_append')) {

    /**
     * @param string $contents
     * @param string $text
     * @param bool $newline
     * @return string
     */
    function str_append(string $contents, string $text, bool $newline = true): string
    {
        if ($newline) {
            $text = "\n" . $text;
        }

        return $contents . $text;
    }
}
if (!function_exists('str_prepend')) {

    /**
     * @param string $contents
     * @param string $text
     * @param bool $newline
     * @return string
     */
    function str_prepend(string $contents, string $text, bool $newline = true): string
    {
        if ($newline) {
            $text = $text . "\n";
        }

        return $text . $contents;
    }
}


if (!function_exists('get_client_token')) {
    /**
     * @param Request $request
     * @return string
     */
    function get_client_token(Request $request): string
    {
        $data = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        return sha1(json_encode($data));
    }
}

if (!function_exists('do_until')) {
    /**
     * @param callable $do
     * @param callable $until
     * @param int $sleep
     * @return void
     */
    function do_until(callable $do, callable $until, int $sleep = 1000): void
    {
        while (!$until()) {
            $do();
            usleep($sleep);
        }
    }
}

if (!function_exists('pipe_do_until')) {
    /**
     * @param callable $do
     * @param callable $until
     * @param int $sleep
     * @return mixed
     */
    function pipe_do_until(callable $do, callable $until, int $sleep = 1000): mixed
    {
        $res = null;

        while (true) {
            $res = $do($res);
            if ($until($res)) {
                break;
            }

            usleep($sleep);
        }

        return $res;
    }
}


if (!function_exists('make_dto')) {
    /**
     * @pure
     *
     * @template T of object
     *
     * @param string|class-string<T> $signature
     * @return T
     * @phpstan-return (
     *     $signature is class-string<T>
     *         ? T
     *         : ($signature is class-string ? object : mixed)
     * )
     *
     */
    function make_dto(string $signature, array $data, ?callable $opt = null): mixed
    {
        try {
            return (new \CuyZ\Valinor\MapperBuilder())
                ->supportDateFormats('Y-m-d H:i:s', 'Y-m-d', 'd M Y')
                ->mapper()
                ->map(
                    $signature,
                    $data
                );
        } catch (\CuyZ\Valinor\Mapper\MappingError $error) {
            return null;
        }
    }
}
