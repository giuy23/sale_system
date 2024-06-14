<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
          'sur_name' => $this->sur_name,
          'email' => $this->email,
          'document_number' => $this->document_number,
          'cell_phone' => $this->cell_phone,
          'state' => $this->state,
          'role' => $this->role->name,
          'role_id' => $this->role->id,
          'image' => $this->image->url ?? $this->image,
        ];
    }
}
