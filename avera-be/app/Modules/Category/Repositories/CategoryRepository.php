<?php

namespace App\Modules\Category\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Category\Contracts\CategoryRepositoryInterface;
use App\Modules\Category\Models\Category;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private Category $model) {}
    public function get(): LengthAwarePaginator
    {
        $perPage = 28;
        $version = $this->versionKey('categories');
        return $this->model->query()
            ->where('status', 'active')->where('parent_id', null)->with('image')->orderBy('name', 'ASC')->paginate($perPage);
    }
    public function getParent(): Collection
    {
        $version = $this->versionKey('categories');
        return 
        // Cache::remember(
            // "categories:list:parent:v{$version}",
            // now()->addHours(1),
            // fn() => 
            $this->model->query()
                ->where('status', 'active')
                ->whereNull('parent_id')
                ->with('image')
                ->orderBy('name', 'ASC')
                ->get();
        // );
    }
    public function getTree(): Collection
    {
        $version = $this->versionKey('categories');
        return Cache::remember(
            "categories:list:tree:v{$version}",
            now()->addHours(1),
            fn() => $this->model->query()
                ->where('status', 'active')
                ->whereNull('parent_id')
                ->with([
                    'children' => fn($q) =>
                    $q->where('status', 'active')
                        ->orderBy('name', 'ASC')
                ])
                ->orderBy('name', 'ASC')
                ->get()
        );
    }
    public function find(string $id): ?Category
    {
        $version = $this->versionKey('categories');
        $cacheKey = "categories:list:categoryId:{$id}:v{$version}";
        $category = Cache::get($cacheKey);
        if (!$category) {
            $category = $this->model->find($id);
            if ($category) {
                Cache::put($cacheKey, $category, now()->addMinutes(1));
            }
        }
        return $category;
    }
    public function findOrFail(string $id): ?Category
    {
        $category = $this->find($id);
        if (!$category) {
            throw new ResourceNotFoundException("Category");
        }
        return $category;
    }

    public function findBySlug(string $slug, bool $isFailMessage = false): ?Category
    {
        $version = $this->versionKey('categories');
        $category = Cache::remember(
            "categories:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->first()
        );
        if ($isFailMessage && !$category) {
            throw new ResourceNotFoundException('Category');
        }
        return $category;
    }
    public function store(array $data): Category
    {
        return $this->model->create($data);
    }
    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    // --- admin section

    private function baseAdminQuery(array $filters)
    {
        $allowedSortBy = ['name', 'status', 'created_at', 'updated_at'];
        $sortBy = in_array(
            $filters['sort_by'],
            $allowedSortBy
        )
            ? $filters['sort_by']
            : 'created_at';
        $direction = in_array(
            $filters['order_direction'],
            ['asc', 'desc']
        )
            ? $filters['order_direction']
            : 'desc';

        return $this->model->query()
            ->when($filters['allow_adult_content'] ?? null, fn($q, $v) => $q->where('allow_adult_content', $v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->orderBy($sortBy, $direction);
    }
    public function getAdmin(array $filters): LengthAwarePaginator
    {
        $allowedPerpage = [12, 24, 48];
        $perPage = in_array($filters['per_page'], $allowedPerpage) ? $filters['per_page'] : 24;
        return $this->baseAdminQuery($filters)->paginate($perPage);
    }
    public function getByTrashed(array $filters): LengthAwarePaginator
    {
        $allowedPerpage = [12, 24, 48];
        $perPage = in_array($filters['per_page'], $allowedPerpage) ? $filters['per_page'] : 24;
        return $this->baseAdminQuery($filters)->onlyTrashed()->paginate($perPage);
    }
    public function findByTrashed(string $id,bool $isFailMessage = false): ?Category
    {
        $category = $this->model->onlyTrashed()->find($id);
        if ($isFailMessage && !$category) {
            throw new ResourceNotFoundException('Category');
        }
        return $category;
    }
    public function delete(Category $category): bool
    {
        return $category->delete();
    }
    public function deletePermanent(Category $category): bool
    {
        return $category->deletePermanent();
    }
    public function restore(Category $category): bool
    {
        return $category->restore();
    }
}
