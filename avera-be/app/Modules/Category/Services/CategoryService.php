<?php

namespace App\Modules\Category\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Category\Contracts\CategoryServiceInterface;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Repositories\CategoryRepository;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}
    public function get(): LengthAwarePaginator
    {
        return $this->categoryRepository->get();
    }
    public function getParent(): Collection
    {
        return $this->categoryRepository->getParent();
    }
    public function getTree(): Collection
    {
        return $this->categoryRepository->getTree();
    }
    public function storeUser(array $data): Category
    {
        $data['status'] = 'pending';
        $data['slug'] = Str::slug($data['name']) . '-' . substr(Str::uuid(), 0, 6);
        $result = $this->categoryRepository->store($data);
        $this->invalidateCache('categories');
        return $result;
    }
    public function findBySlug(string $slug): ?Category
    {
        $category = $this->categoryRepository->findBySlug($slug, true);
        return $category;
    }

    public function find(string $id): ?Category
    {
        $category = $this->categoryRepository->findOrFail($id);
        return $category;
    }
    public function getAdmin(array $filters): LengthAwarePaginator
    {
        return $this->categoryRepository->getAdmin($filters);
    }
    public function storeAdmin(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']) . '-' . substr(Str::uuid(), 0, 6);
        $category = $this->categoryRepository->store($data);
        $this->invalidateCache('categories');
        return $category;
    }

    public function update(string $id, array $data): Category
    {
        $category = $this->categoryRepository->findOrFail($id);
        $result = $this->categoryRepository->update($category, $data);
        $this->invalidateCache('categories');
        return $result;
    }
    public function delete(string $id): bool
    {
        $category = $this->categoryRepository->findOrFail($id);
        return $this->categoryRepository->delete($category);
    }
    public function deletePermanent(string $id): bool
    {
        $category = $this->categoryRepository->findByTrashed($id, true);
        return $this->categoryRepository->deletePermanent($category);
    }
    public function restore(string $id): bool
    {
        $category = $this->categoryRepository->findByTrashed($id, true);
        return $this->categoryRepository->restore($category);
    }
    public function getByTrashed(array $filters): LengthAwarePaginator
    {
        return $this->categoryRepository->getByTrashed($filters);
    }
    public function findByTrashed(string $id): ?Category
    {
        $category = $this->categoryRepository->findByTrashed($id, true);
        return $category;
    }
}
