<?php

namespace App\Api\Modules\User\Resource;

use App\Api\Support\Resources\BaseResource;
use Illuminate\Http\Request;

class UserBasicResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'picture' => $this->picture,
        ];
    }
}

