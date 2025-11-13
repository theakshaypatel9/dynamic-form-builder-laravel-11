@extends('layouts.app')

@section('header')
    <h2 class="text-xl font-semibold text-gray-800 leading-tight">
        Form Submissions — {{ $form->title }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <table id="submissions-table" class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-3 py-2">ID</th>
                            <th class="border px-3 py-2">User ID</th>
                            <th class="border px-3 py-2">Data</th>
                            {{-- <th class="border px-3 py-2">Submitted At</th> --}}
                            <th class="border px-3 py-2">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#submissions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.forms.submissions.data', $form->id) }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'data',
                        name: 'data'
                    },
                    // {
                    //     data: 'created_at',
                    //     name: 'created_at'
                    // },
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
