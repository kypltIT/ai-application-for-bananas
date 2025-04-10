@extends('layouts.admin.app')
@section('title', 'Product Category List')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.product-categories.index') }}">Product
                                    Category Management </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Product Category List</li>
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
                                            <h3>Product Category List</h3>
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
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productCategories as $productCategory)
                                            <tr>
                                                <td class="profile-image">{{ $productCategory->name }}</td>
                                                <td>{{ $productCategory->description }}</td>
                                                <td>
                                                    <img width="50" height="50"
                                                        src="{{ asset('storage/' . $productCategory->image) }}"
                                                        class="rounded-circle m-r-5" alt="">
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#edit_product_category_{{ $productCategory->id }}">
                                                        <i class="fa-solid fa-pen-to-square m-r-5"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#delete_product_category_{{ $productCategory->id }}">
                                                        <i class="fa fa-trash-alt m-r-5"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit_product_category_{{ $productCategory->id }}"
                                                tabindex="-1" aria-labelledby="editProductCategoryLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editProductCategoryLabel">Edit
                                                                Product Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form
                                                            action="{{ route('admin.product-categories.update', $productCategory->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">

                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="name" name="name"
                                                                        value="{{ $productCategory->name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="description"
                                                                        class="form-label">Description</label>
                                                                    <textarea class="form-control" id="description" name="description" required>{{ $productCategory->description }}</textarea>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Image (262px x
                                                                        279px)</label>
                                                                    <input type="file" class="form-control"
                                                                        id="image" name="image"
                                                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/svg">
                                                                    <img width="50" height="50"
                                                                        src="{{ asset('storage/' . $productCategory->image) }}"
                                                                        class="rounded-circle m-r-5 mt-3" alt="">
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update
                                                                    Product Category</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade"
                                                id="delete_product_category_{{ $productCategory->id }}" tabindex="-1"
                                                aria-labelledby="deleteProductCategoryLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteProductCategoryLabel">
                                                                Delete
                                                                Product Category</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete this product
                                                                category?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form
                                                                action="{{ route('admin.product-categories.destroy', $productCategory->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete
                                                                    Product Category</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Add New Product Category Modal -->
                            <div class="modal fade" id="add_product_category" tabindex="-1"
                                aria-labelledby="addProductCategoryLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addProductCategoryLabel">Add New Product Category
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.product-categories.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">

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
                                                    <label for="image" class="form-label">Image (262px x 279px)</label>
                                                    <input type="file" class="form-control" id="image"
                                                        name="image" required
                                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/svg">
                                                </div>


                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Add New Product
                                                    Category</button>
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
