<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationHelper;
use App\Http\Resources\MinimalProductResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request): LengthAwarePaginator
    {
        return PaginationHelper::paginate((MinimalProductResource::collection(Product::all()))->collection, 25);
    }

    public function productsByCategory(Request $request, int $categoryId)
    {
        $category = Category::with('children')->findOrFail($categoryId);

        $allCategories = $category->getAllSubcategories();

        $allCategoryIds = $allCategories->pluck('id');

        $products = Product::whereHas('categories', function ($query) use ($allCategoryIds) {
            $query->whereIn('category_id', $allCategoryIds);
        })->get();

        return PaginationHelper::paginate(MinimalProductResource::collection($products)->collection, 25);
    }

    public function show(Request $request, string $sku): JsonResource {
        $product = Product::with('categories')->findOrFail($sku);
        return new ProductResource($product);
    }

    public function filter(Request $request)
    {
        $user = auth('sanctum')->user();

        $query = Product::query()
            ->select(['products.*', DB::raw('COALESCE(contract_lists.price, price_list_product.price, products.price) AS calculated_price')])
            ->leftJoin('contract_lists', function($join) use ($user) {
                $join->on('products.SKU', '=', 'contract_lists.SKU')
                    ->where('contract_lists.user_id', '=', $user->id);
            })
            ->leftJoin('price_list_product', function ($join) use ($user) {
                $join->on('products.SKU', '=', 'price_list_product.SKU')
                    ->where('price_list_product.price_list_id', '=', $user->price_list_id);
            });

        if ($request->filled('name')) {
            $query->where('products.name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('category_id')) {
            $categoryId = $request->input('category_id');
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        if ($request->filled('min_price')) {
            $minPrice = $request->input('min_price') * 100;
            $query->having('calculated_price', '>=', $minPrice);
        }

        if ($request->filled('max_price')) {
            $maxPrice = $request->input('max_price') * 100;
            $query->having('calculated_price', '<=', $maxPrice);
        }

        if ($request->input('sort_by') === 'price') {
            $query->orderBy('calculated_price', $request->input('sort_order', 'asc'));
        } else {
            $query->orderBy('products.name', $request->input('sort_order', 'asc'));
        }

        $products = $query->get();

        return PaginationHelper::paginate(ProductResource::collection($products)->collection, 25);
    }
}
