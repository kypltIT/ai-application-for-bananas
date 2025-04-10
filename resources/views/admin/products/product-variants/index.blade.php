@extends('layouts.admin.app')
@section('title', 'Product Variant List')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Product
                                    Management </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Product Variant List</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>Product Variant List</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                            placeholder="Search here">
                                                        <a class="btn"><img
                                                                src="{{ asset('assets/admin/img/icons/search-normal.svg') }}"
                                                                alt=""></a>
                                                    </form>
                                                </div>
                                                <div class="add-group">
                                                    <button class="btn btn-primary add-pluss ms-2" data-bs-toggle="modal"
                                                        data-bs-target="#add_product_category"><img
                                                            src="{{ asset('assets/admin/img/icons/plus.svg') }}"
                                                            alt=""></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-01.svg') }}"
                                                alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-02.svg') }}"
                                                alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-03.svg') }}"
                                                alt=""></a>
                                        <a href="javascript:;"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-04.svg') }}"
                                                alt=""></a>

                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>SKU</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productVariants as $productVariant)
                                            <tr>
                                                <td class="profile-image">{{ $productVariant->name }}</td>
                                                <td>{{ $productVariant->description }}</td>
                                                <td>{{ $productVariant->price }}</td>
                                                <td>{{ $productVariant->stock }}</td>
                                                <td>{{ $productVariant->sku }}</td>
                                                <td>
                                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#add_product_variant_image_{{ $productVariant->id }}">
                                                        <i class="fa-solid fa-image m-r-5"></i> View Image
                                                    </button>
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#edit_product_variant_{{ $productVariant->id }}">
                                                        <i class="fa-solid fa-pen-to-square m-r-5"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#delete_product_variant_{{ $productVariant->id }}">
                                                        <i class="fa fa-trash-alt m-r-5"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit_product_variant_{{ $productVariant->id }}"
                                                tabindex="-1" aria-labelledby="editProductVariantLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editProductVariantLabel">Edit
                                                                Product Variant</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form
                                                            action="{{ route('admin.products.product-variants.update', $productVariant->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">

                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="name" name="name"
                                                                        value="{{ $productVariant->name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="description"
                                                                        class="form-label">Description</label>
                                                                    <textarea class="form-control" id="description" name="description" required>{{ $productVariant->description }}</textarea>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="price" class="form-label">Price</label>
                                                                    <input type="number" class="form-control"
                                                                        id="price" name="price"
                                                                        value="{{ $productVariant->price }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="stock" class="form-label">Stock</label>
                                                                    <input type="number" class="form-control"
                                                                        id="stock" name="stock"
                                                                        value="{{ $productVariant->stock }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="sku" class="form-label">SKU</label>
                                                                    <input type="text" class="form-control"
                                                                        id="sku" name="sku"
                                                                        value="{{ $productVariant->sku }}" required>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update
                                                                    Product Variant</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete_product_variant_{{ $productVariant->id }}"
                                                tabindex="-1" aria-labelledby="deleteProductVariantLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteProductVariantLabel">
                                                                Delete
                                                                Product Variant</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete this product
                                                                variant?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form
                                                                action="{{ route('admin.products.product-variants.destroy', $productVariant->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete
                                                                    Product Variant</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade"
                                                id="add_product_variant_image_{{ $productVariant->id }}" tabindex="-1"
                                                aria-labelledby="addProductVariantImageLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="addProductVariantImageLabel">Add
                                                                Product Variant Image</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form
                                                            action="{{ route('admin.products.product-variants.images.store') }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="product_variant_id"
                                                                    value="{{ $productVariant->id }}">
                                                                <label for="filepond">Upload Image (336px x 400px)</label>
                                                                <input type="file" name="images[]"
                                                                    id="filepond{{ $productVariant->id }}" multiple
                                                                    class="form-control"
                                                                    accept=".jpeg,.png,.jpg,.gif,.svg">

                                                                <hr>

                                                                <div class="my-3">
                                                                    <div class="row g-3">
                                                                        @forelse ($productVariant->images as $image)
                                                                            <div class="col-sm-6 col-md-4 col-lg-3"
                                                                                id="image-{{ $image->id }}">
                                                                                <div class="card shadow-sm border-0">
                                                                                    <div class="position-relative">
                                                                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                                                                            alt="Product Variant Image"
                                                                                            class="card-img-top rounded"
                                                                                            style="height: 180px; object-fit: cover;">
                                                                                        <button type="button"
                                                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 px-2 py-1"
                                                                                            onclick="deleteImage('{{ route('admin.products.product-variants.images.destroy', $image->id) }}', '{{ $image->id }}')">
                                                                                            <i
                                                                                                class="fas fa-trash-alt"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @empty
                                                                            <div class="col">
                                                                                <p class="text-muted">No images available.
                                                                                </p>
                                                                            </div>
                                                                        @endforelse
                                                                    </div>

                                                                    <script>
                                                                        function deleteImage(url, imageId) {
                                                                            fetch(url, {
                                                                                    method: 'DELETE',
                                                                                    headers: {
                                                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                                        'Content-Type': 'application/json'
                                                                                    }
                                                                                })
                                                                                .then(response => {
                                                                                    if (response.ok) {
                                                                                        document.getElementById('image-' + imageId).remove();
                                                                                    } else {
                                                                                        alert('Error deleting image.');
                                                                                    }
                                                                                });
                                                                        }
                                                                    </script>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Upload
                                                                    Image</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal fade" id="add_product_category" tabindex="-1"
                                aria-labelledby="addProductCategoryLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addProductVariantLabel">Add New Product Variant
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.products.product-variants.store', $product->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price</label>
                                                    <input type="number" class="form-control" id="price"
                                                        name="price" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="stock" class="form-label">Stock</label>
                                                    <input type="number" class="form-control" id="stock"
                                                        name="stock" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Add New Product
                                                    Variant</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
