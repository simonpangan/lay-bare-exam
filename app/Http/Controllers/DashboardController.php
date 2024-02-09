<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use JustSteveKing\StatusCode\Http;

class DashboardController extends Controller
{
    public function __construct(readonly Request $request) {}

    public function __invoke()
    {
        return ProductResource::collection($this->getProducts())
            ->additional([
                'status_code' => Http::OK,
                'message' => 'OK'
            ]);
    }

    private function getProducts()
    {
        return Product::query()
            ->with('category')
            ->when($this->request->get('search'), $this->addSearch())
            ->when($this->request->get('sort'), $this->addSort())
            ->when($this->request->get('category'), $this->addCategory())
            ->paginate(15);
    }

    private function addSort(): \Closure
    {
        return function (Builder $query, string $sort) {
            if ($sort === 'asc') {
                $query->orderBy('name');
            } elseif ($sort === 'desc') {
                $query->orderBy('name', 'desc');
            }
        };
    }

    private function addSearch(): \Closure
    {
        return function (Builder $query, string $search) {
            if (empty(trim($search))) {
                return;
            }

            return $query->where('name', 'like', "%{$search}%");
        };
    }

    private function addCategory(): \Closure
    {
        return function (Builder $query, string $category) {
            if (empty(trim($category))) {
                return;
            }

            $query->whereRelation('category', 'name', $category);
        };
    }
}
