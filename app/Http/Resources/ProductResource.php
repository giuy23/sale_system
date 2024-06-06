<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
      'description' => $this->description,
      'purchase_price' => $this->purchase_price,
      'sale_price' => $this->sale_price,
      'bar_code' => $this->bar_code,
      'quantity' => $this->quantity,
      'minimum_quantity' => $this->minimum_quantity,
      'state' => $this->state,
      // 'sub_category' => $this->subCategory,
      'sub_category_id' => $this->subCategory->id,
      // 'provider' => $this->provider,
      'provider_id' => $this->provider->id,
      'image' => $this->image->url ?? $this->image,
      // 'images' =>  $this->whenLoaded('images', $this->images),
    ];

    // $imageKey = $request->routeIs('products.index') ? 'mainImage' : 'images';

    // return [
    //   'id' => $this->id,
    //   'name' => $this->name,
    //   'description' => $this->description,
    //   'purchase_price' => $this->purchase_price,
    //   'sale_price' => $this->sale_price,
    //   'bar_code' => $this->bar_code,
    //   'quantity' => $this->quantity,
    //   'minimum_quantity' => $this->minimum_quantity,
    //   'state' => $this->state,
    //   'sub_category' => $this->subCategory,
    //   'provider' => $this->provider,
    //   'image' => $this->whenLoaded($imageKey, function () use ($imageKey) {
    //     return $imageKey === 'mainImage' ? $this->mainImage->url : $this->images;
    //   }),
    // ];
  }
}
