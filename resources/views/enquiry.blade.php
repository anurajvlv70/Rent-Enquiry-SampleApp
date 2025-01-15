@extends('layouts.app')
@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <!-- Button to add -->
                    <button type="button" class="btn btn-primary mb-4 mt-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add Inquiry
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for Adding New Inquiry -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add enquiry</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="add-enquiry-form" action="{{ route('enquiries.store') }}" method="POST">
    @csrf
    <div>
        <label for="title">Enquiry Title:</label>
        <input type="text" class="form-control" name="title" id="title" required />
    </div>
<div class="row">
    <div class="col-md-6">
        <label for="rental_start_date">Rental Start Date:</label>
        <input type="date" class="form-control" name="rental_start_date" id="rental_start_date" required />
    </div>

    <div  class="col-md-6">
        <label for="rental_end_date">Rental End Date:</label>
        <input type="date" class="form-control" name="rental_end_date" id="rental_end_date" required />
    </div>
    </div>
    <div id="product-selection">
        <label>Select Products:</label>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-6">
                    <input type="checkbox" name="products[{{ $product->id }}]" value="{{ $product->id }}" />
                    <label>{{ $product->name }}({{$product->available_stock}})</label>
                    <input
                        type="number"
                        name="quantities[{{ $product->id }}]"
                        min="1"
                        placeholder="Quantity" class="form-control"
                    />
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-4">Add Enquiry</button>
</form>

                </div>
            </div>
        </div>
    </div>

    <!-- Table for Inquiries -->
    <div class="container my-5">
        <h2 class="text-center mb-4">enquiry List</h2>
        <table class="table table-bordered table-hover table-striped align-middle">
    <thead>
        <tr>
            <th>Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Products</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($enquiries as $key=> $enquiry)
            <tr>
                <td>{{ $enquiry->title }}</td>
                <td>{{ $enquiry->rental_start_date }}</td>
                <td>{{ $enquiry->rental_end_date }}</td>
                <td>
                    <ul>
                        @foreach ($enquiry->products as  $product)
                            <li>{{ $product->name }} ({{ $product->pivot->quantity }})</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{$key}}editModal">
                        Edit
                    </button>
                    <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
                <div class="modal fade" id="{{$key}}editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add enquiry</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="edit-enquiry-form" action="{{ route('enquiries.edit', $enquiry->id)}}" method="POST">
    @csrf
    <div class="row">
    <div class="col-md-6">
        <label for="title">Enquiry Title:</label>
        <input type="text" name="title" class="form-control" value="{{$enquiry->title}}" id="title" required />
    </div>

    <div class="col-md-6">
        <label for="rental_start_date">Rental Start Date:</label>
        <input type="date" name="rental_start_date" class="form-control" value="{{$enquiry->rental_start_date}}" id="rental_start_date" required />
    </div>

    <div class="col-md-6">
        <label for="rental_end_date">Rental End Date:</label>
        <input type="date" name="rental_end_date" class="form-control" value="{{$enquiry->rental_end_date}}" id="rental_end_date" required />
    </div>
</div>
    <div id="product-selection">
        <label>Select Products:</label>
        <div class="row">
            @foreach ($products as $product)
            <div class="col-md-6">
                    <input type="checkbox" name="products[{{ $product->id }}]" value="{{ $product->id }}"
                    @if($enquiry->products->contains('id', $product->id)) checked @endif />
                     <label>{{ $product->name }}({{$product->available_stock}})</label>
                    <input
                        type="number"
                        name="quantities[{{ $product->id }}]"
                        min="1"
                        @if($enquiry->products->contains('id', $product->id))
                        value="{{ $enquiry->products->firstWhere('id', $product->id)->pivot->quantity }}"   
                        @endif                     
                        placeholder="Quantity"
                    />
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-4">Add Enquiry</button>
</form>

                </div>
            </div>
        </div>
    </div>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>

    <!-- Include JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#inquiries').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search inquiries",
                },
                order: [[0, 'asc']],
            });
        });
    </script>
@endsection
