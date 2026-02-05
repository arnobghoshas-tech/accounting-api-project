<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'website' => $this->website,
            'status' => $this->status,
            'location' => $this->location,
        ];
    }
}
