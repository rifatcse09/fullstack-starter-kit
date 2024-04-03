<?php

namespace App\Services\V1\Restaurant;

use App\Models\RestaurantInfo;
// use App\Models\OrganizationMember;
// use App\Enums\OrganizationMemberRole;

class RestaurantService
{
    public function createRestaurant(array $data): RestaurantInfo
    {

        return RestaurantInfo::create($data);

    }

}
