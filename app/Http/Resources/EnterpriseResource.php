<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnterpriseResource extends JsonResource
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
      'name_comercial' => $this->name_comercial,
      'description' => $this->description,
      'cell_phone' => $this->cell_phone,
      'address' => $this->address,
      'RUC' => $this->RUC,
      'image' => $this->image->url ?? $this->image,
    ];
  }
}
