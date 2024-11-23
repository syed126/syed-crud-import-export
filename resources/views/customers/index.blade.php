@extends('layouts.app')

@section('content')
@if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Customers</h2>

            <div class="d-flex justify-content-start mb-3 button-space">
                <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" id="import-form">
                    @csrf
                    <input type="file" name="file" accept=".csv, .xlsx" id="file-input" style="display: none;">
                    <button type="button" class="btn btn-success mr-2" onclick="document.getElementById('file-input').click();">
                        <i class="fas fa-upload"></i> Import Customers
                    </button>
                </form>
                <form action="{{ route('customers.export') }}" method="GET" class="mr-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Export Customers
                    </button>
                </form>

                <a href="{{ route('customers.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Create New Customer
                </a>
            </div>

            <table class="table mt-4 table-bordered" id="customers-table">
    <thead class="bg-dark text-white">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Created By</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($customers as $index => $customer)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $customer->name ?? 'N/A' }}</td>
        <td>{{ $customer->email ?? 'N/A' }}</td>
        <td>{{ $customer->phone ?? 'N/A' }}</td>
        <td>{{ $customer->user->name ?? 'N/A' }}</td>
        <td>
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i>
            </a>

            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this customer?')">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">No data found</td>
    </tr>
@endforelse
    </tbody>
</table>


        </div>
    </div>
</div>


@endsection
