<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $this->logo,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'website' => $this->website,
            'status' => $this->status,
        ];
    }
}
