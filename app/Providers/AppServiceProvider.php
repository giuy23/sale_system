<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Builder::macro('filterData', function (Request $request) {
      /** @var Builder $this */
      $query = $this;

      $search = $request->query('search');
      $filters = $request->query('searchBy', []);

      return $query->where(function ($query) use ($search, $filters) {
          if (!empty($filters)) {
              foreach ($filters as $filter) {
                  if ($filter === 'id') {
                      $query->orWhere('id', $search);
                  }
                  if ($filter === 'name') {
                      $query->orWhere('name', 'like', '%' . $search . '%');
                  }
                  if ($filter === 'description') {
                      $query->orWhere('description', 'like', '%' . $search . '%');
                  }
              }
          }
      });
  });
  }
}
