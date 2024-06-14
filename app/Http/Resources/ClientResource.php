<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
      'full_name' => $this->full_name,
      'document_number' => $this->document_number,
      'cell_phone' => $this->cell_phone,
      'state' => $this->state ?? 1,
    ];
  }
}
