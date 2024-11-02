<?php

namespace App\Providers;

use Carbon\Carbon;
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

    Builder::macro('filterByDate', function (Request $request) {
      /** @var Builder $this */
      $query = $this;
      $start_date = $request->query('start_date');
      if ($start_date !== null) {
        $start_date = Carbon::parse($start_date)->format('Y-m-d');
      }
      $end_date = Carbon::parse($request->query('end_date'))->format('Y-m-d') ?? Carbon::today()->format('Y-m-d');

      return $query->where(function ($query) use ($start_date, $end_date) {
        if ($start_date !== null) {
          $query->whereDate('created_at', '>=', $start_date);
        }
        $query->whereDate('created_at', '<=', $end_date);
      });
    });
  }
}
