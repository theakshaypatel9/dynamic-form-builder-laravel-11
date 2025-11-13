@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Submission #{{ $submission->id }} for "{{ $form->title }}"
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h4 class="font-semibold mb-4">Submitted Data:</h4>
                <pre class="bg-gray-100 p-4 rounded">{{ json_encode($submission->data, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
@endsection
