<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
      'document_number' => $this->document_number,
      'name_company' => $this->name_company,
      'cellphone' => $this->cellphone,

      'image' =>  $this->image->url ?? $this->image,
    ];
  }
}
