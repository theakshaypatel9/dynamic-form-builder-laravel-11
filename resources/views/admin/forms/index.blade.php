@extends('layouts.app')

<style>
    <style>.btn {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        color: white;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-primary {
        background-color: #2563eb;
    }

    .btn-success {
        background-color: #16a34a;
    }

    .btn-danger {
        background-color: #dc2626;
    }

    .btn-sm {
        padding: 2px 6px;
        font-size: 0.8rem;
    }
</style>

</style>
@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-semibold">Forms</h2>
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">Create Form</a>
        </div>
        <div class="overflow-x">
            <table class="table w-full" id="forms-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $('#forms-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.forms.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endsection
