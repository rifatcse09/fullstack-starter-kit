<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Restaurant;

use App\Models\RestaurantInfo;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\V1\Restaurant\RestaurantService;
use App\Http\Requests\V1\Restaurant\RestaurantCreateRequest;

final class RestaurantInfoController extends Controller
{

    public function __construct(public RestaurantService $restaurantService)
    {}

    public function store(RestaurantCreateRequest $request) : JsonResponse
    {
        $data = $this->restaurantService->createRestaurant($request->all());

        return api([$data])->success('Restaurants Created Successfully');
    }

    public function getRestaurant(int $id) : JsonResponse
    {
        $data = RestaurantInfo::find($id);

        return api([$data])->success('Restaurants Created Successfully');
    }
}
