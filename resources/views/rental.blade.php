@extends('layouts.app')
@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        Add Product
                    </button>

                    <!-- Add Product Modal -->
                    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="addProductModalLabel">Add Product</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('product_submit') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-3">
                                            <!-- Name -->
                                            <div class="col-md-12">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" id="name" name="name" 
                                                       class="form-control" required>
                                                       <input type="hidden" name="mode" value="rental">
                                                       <input type="hidden" name="operation" value="create">
                                            </div>

                                            <!-- SKU -->
                                            <div class="col-md-12">
                                                <label for="sku" class="form-label">SKU</label>
                                                <input type="text" id="sku" name="sku" 
                                                       class="form-control" required>
                                            </div>

                                            <!-- Available Stock -->
                                            <div class="col-md-12">
                                                <label for="available_stock" class="form-label">Available Stock</label>
                                                <input type="number" id="available_stock" name="available_stock" 
                                                       class="form-control" required min="0">
                                            </div>

                                            <!-- Image -->
                                            <div class="col-md-12">
                                                <label for="image" class="form-label">Product Image</label>
                                                <input type="file" id="image" name="image" 
                                                       class="form-control" accept="image/*" required>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary w-100">
                                                Add Product
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Table -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Product List</h2>

        <!-- DataTable -->
        <table id="productTable" class="table table-striped table-hover align-middle" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="place">Name</th>
                    <th scope="col" class="place">SKU</th>
                    <th scope="col" class="place">Available Stock</th>
                    <th scope="col" class="place">Image</th> 
                    <th scope="col" class="place">Actions</th>
                </tr>
            </thead>
            <tbody>
                @isset($products)

                @foreach($products as $key => $product)
                    <tr>
                        <td class="place">{{ $product->name }}</td>
                        <td class="place">{{ $product->sku }}</td>
                        <td class="place">{{ $product->available_stock }}</td>
                        <td class="place">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width:50px; height:50px;">
                        </td>
                        <td>
                            <div class="d-flex">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal{{$key}}">
                                Edit
                            </button>
                            <form action="{{route('product_submit')}}" method="post">
                                @csrf
                            <input type="hidden" name="operation" value="delete">
                                                <input type="hidden" name="productId" value="{{ $product->id }}">
                                                <input type="hidden" name="mode" value="rental">
                                                <input type="submit" class="btn btn-danger" value="delete">
                            </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Product Modal -->
                    <div class="modal fade" id="editProductModal{{$key}}" tabindex="-1" aria-labelledby="editProductModal{{$key}}Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="editProductModal{{$key}}Label">Edit Product</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('product_submit') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-3">
                                            <!-- Name -->
                                            <div class="col-md-12">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" id="name" name="name" value="{{ $product->name }}" 
                                                       class="form-control" required>
                                                <input type="hidden" name="operation" value="edit">
                                                <input type="hidden" name="productId" value="{{ $product->id }}">
                                                <input type="hidden" name="mode" value="rental">
                                            </div>

                                            <!-- SKU -->
                                            <div class="col-md-12">
                                                <label for="sku" class="form-label">SKU</label>
                                                <input type="text" id="sku" name="sku" value="{{ $product->sku }}" 
                                                       class="form-control" required>
                                            </div>

                                            <!-- Available Stock -->
                                            <div class="col-md-12">
                                                <label for="available_stock" class="form-label">Available Stock</label>
                                                <input type="number" id="available_stock" name="available_stock" 
                                                       class="form-control" value="{{ $product->available_stock }}" required min="0">
                                            </div>

                                            <!-- Image -->
                                            <div class="col-md-12">
                                                <label for="image" class="form-label">Product Image</label>
                                                <input type="file" id="image" name="image" 
                                                       class="form-control" accept="image/*">
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary w-100">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @endisset
            </tbody>
        </table>
    </div>

    <!-- Include JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6
@endsection