@extends('layouts.app')

@section('title', 'Edit Product - Grozzoery')

@section('content')
<div class="container py-8">
    <div class="mb-8">
        <h1>Edit Product</h1>
        <p>Update your product information</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price *</label>
                        <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Compare Price</label>
                        <input type="number" name="compare_price" step="0.01" value="{{ old('compare_price', $product->compare_price) }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quantity *</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input form-textarea">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <textarea name="short_description" class="form-input form-textarea">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-input form-textarea">{{ old('meta_description', $product->meta_description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Featured</label>
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <span>Mark as featured product</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Product Images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-input">
                    <small>You can select multiple images. First image will be the main image.</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
