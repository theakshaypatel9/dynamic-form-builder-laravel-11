@extends('layouts.app')

@section('header')
    <h2 class="text-xl font-semibold">Available Forms</h2>
@endsection

@section('content')
    <div class="p-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <table class="table-auto w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">Title</th>
                            <th class="px-4 py-2 border">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forms as $form)
                            <tr>
                                <td class="px-4 py-2 border">{{ $form->title }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <a href="{{ route('user.forms.show', $form->id) }}"
                                        class="px-3 py-1 bg-blue-600 text-white rounded">Fill Form</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
