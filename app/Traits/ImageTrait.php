<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;

trait ImageTrait
{
  public function uploadImage($image, $model, $path, $isPrincipal)
  {
    $imageName = time() . '.' . $image->getClientOriginalExtension();
    Storage::disk('public')->putFileAs($path, $image, $imageName);

    if ($isPrincipal) {
      $model->image()->create([
        'is_principal' => true,
        'url' => $imageName,
      ]);
    } else {
      $model->image()->create([
        'url' => $imageName,
      ]);
    }

    return $imageName;
  }
  public function editImage($image, $model, $path)
  {
    $imageName = time() . '.' . $image->getClientOriginalExtension();
    $oldImage = $model->image->url;

    $model->image()->update([
      'url' => $imageName
    ]);
    Storage::disk('public')->putFileAs($path, $image, $imageName);
    Storage::disk('public')->delete($path . '/' . $oldImage);

    return $imageName;
  }

  public function deleteImage($model, $path)
  {
    $image = $model->image->url;
    $model->image()->delete();
    Storage::disk('public')->delete($path . '/' . $image);
    return true;
  }


  public function createImage($model, $path)
  {
    $avatar = Avatar::create($model->name)->getImageObject()->encode('png');
    $imageName = time() . '.png';
    Storage::disk('public')->put($path . '/' . $imageName, (string) $avatar);
    // Storage::disk('public')->putFileAs($path, $avatar, $imageName);

    $model->image()->create([
      'url' => $imageName,
    ]);

    return $imageName;
  }
}
