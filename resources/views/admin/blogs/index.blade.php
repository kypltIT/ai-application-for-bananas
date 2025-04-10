@extends('layouts.admin.app')
@section('title', 'Blog List')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.blog-categories.index') }}">Blog
                                    Management </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Blog List</li>
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
                                            <h3>Blog List</h3>
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
                                                        data-bs-target="#add_blog"><img
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
                                            <th>Title</th>
                                            <th>Slug</th>
                                            <th>Category</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($blogs as $blog)
                                            <tr>
                                                <td class="profile-image">{{ $blog->title }}</td>
                                                <td class="text-wrap">{{ $blog->slug }}</td>
                                                <td class="text-wrap">{{ $blog->blogCategory->name }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $blog->image) }}" alt="Blog Image"
                                                        class="img-fluid" style="width: 100px; height: 100px;">
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#edit_blog_{{ $blog->id }}">
                                                        <i class="fa-solid fa-pen-to-square m-r-5"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#delete_blog_{{ $blog->id }}">
                                                        <i class="fa fa-trash-alt m-r-5"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit_blog_{{ $blog->id }}" tabindex="-1"
                                                aria-labelledby="editBlogLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editBlogLabel">Edit
                                                                Blog</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.blogs.update', $blog->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">

                                                                <div class="mb-3">
                                                                    <label for="title" class="form-label">Title</label>
                                                                    <input type="text" class="form-control"
                                                                        id="title" name="title"
                                                                        value="{{ $blog->title }}" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="blog_category_id"
                                                                        class="form-label">Category</label>
                                                                    <select class="form-select" id="blog_category_id"
                                                                        name="blog_category_id" required>
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}"
                                                                                {{ $blog->blog_category_id == $category->id ? 'selected' : '' }}>
                                                                                {{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Image (342px x
                                                                        223px)</label>
                                                                    <input type="file" class="form-control"
                                                                        id="image" name="image"
                                                                        @if (!$blog->image) required @endif>
                                                                </div>

                                                                @if ($blog->image)
                                                                    <div class="mb-3">
                                                                        <img src="{{ asset('storage/' . $blog->image) }}"
                                                                            alt="Blog Image" class="img-fluid"
                                                                            style="height: 100px;">
                                                                    </div>
                                                                @endif


                                                                <div class="mb-3">
                                                                    <label for="content"
                                                                        class="form-label">Content</label>
                                                                    <textarea class="form-control" id="content_{{ $blog->id }}" name="content" rows="5">{{ $blog->content }}</textarea>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update
                                                                    Blog</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete_blog_{{ $blog->id }}" tabindex="-1"
                                                aria-labelledby="deleteBlogLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteBlogLabel">
                                                                Delete
                                                                Blog</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete this blog?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete
                                                                    Blog</button>
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
                            <div class="modal fade" id="add_blog" tabindex="-1" aria-labelledby="addBlogLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addBlogLabel">Add New Blog
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.blogs.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Title</label>
                                                    <input type="text" class="form-control" id="title"
                                                        name="title" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="blog_category_id" class="form-label">Category</label>
                                                    <select class="form-select" id="blog_category_id"
                                                        name="blog_category_id" required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Image (342px x
                                                        223px)</label>
                                                    <input type="file" class="form-control" id="image"
                                                        name="image" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="content" class="form-label">Content</label>
                                                    <textarea class="form-control" id="content" name="content" rows="5"></textarea>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Add New Blog
                                                </button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '#content',
                plugins: 'lists',
                toolbar: 'lists',
                license_key: 'gpl',
            });

        });

        $(document).ready(function() {
            @foreach ($blogs as $blog)
                tinymce.init({
                    selector: '#content_{{ $blog->id }}',
                    plugins: 'lists',
                    toolbar: 'lists',
                    license_key: 'gpl',
                });
            @endforeach
        });
    </script>
@endsection
