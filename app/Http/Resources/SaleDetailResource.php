<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
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
      'sale_id' => $this->sale_id,
      'product_id' => $this->product_id,
      'price' => floatval($this->price),
      'quantity' => $this->quantity,
      'discount' => floatval($this->discount),
      'total' => floatval($this->total),
      'created_at' => $this->created_at,
      'product_name' => $this->product->name ?? 'PRODUCTO ElIMINADO',
    ];
  }
}
