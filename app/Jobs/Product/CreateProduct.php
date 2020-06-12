<?php

class CreateProduct
{
    public function handle()
    {
        $this->data['slug'] = Str::slug($this->data['name']);

        if (!empty($this->data['cover']) && $this->data['cover'] instanceof UploadedFile) {
            $this->data['cover'] = $this->product_repo->saveCoverImage($this->data['cover']);
        }

        $this->data['is_featured'] = !empty($this->data['is_featured']) && $this->data['is_featured'] === 'true' ? 1 : 0;

        $this->product_repo->save($this->data, $product);

        if (!empty($this->data['features'])) {
            $this->saveProductFeatures($product, $this->data['features']);
        }

        if (isset($this->data['image']) && !empty($this->data['image'])) {
            $this->product_repo->saveProductImages(collect($this->data['image']), $product);
        }

        if (isset($this->data['category']) && !empty($this->data['category'])) {
            $categories = !is_array($this->data['category']) ? explode(',', $this->data['category']) : $this->data['category'];
            $this->product_repo->syncCategories($categories, $product);
        } else {
            $this->detachCategories($product);
        }
    }
}
