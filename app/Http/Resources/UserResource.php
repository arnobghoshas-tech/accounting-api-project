<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\BranchResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'company' => $this->whenLoaded('company', fn () => new CompanyResource($this->company)),
            'branch' => $this->whenLoaded('branch', fn () => new BranchResource($this->branch)),
            'roles' => $this->getRoleNames(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
