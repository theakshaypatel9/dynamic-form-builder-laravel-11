@extends('layouts.app')

@section('header')
    <h2 class="text-xl font-semibold">Fill Form: {{ $form->title }}</h2>
@endsection

@section('content')
    <div class="p-6">
        <form method="POST" action="{{ route('user.forms.submit', $form->id) }}">
            @csrf

            @foreach ($form->config['fields'] as $field)
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">{{ $field['label'] }}</label>

                    @if ($field['type'] === 'textarea')
                        <textarea name="{{ Str::slug($field['label'], '_') }}" class="form-input w-full border-gray-300 rounded-md"
                            required="{{ $field['required'] }}"></textarea>
                    @elseif ($field['type'] === 'radio')
                        @foreach ($field['option'] as $option)
                            <div>
                                <label class="mr-4">
                                    <input type="radio" name="{{ Str::slug($field['label'], '_') }}"
                                        value="{{ $option }}" required="{{ $field['required'] }}">
                                    {{ ucfirst($option) }}
                                </label>
                            </div>
                        @endforeach
                    @else
                        <input type="{{ $field['type'] }}" name="{{ Str::slug($field['label'], '_') }}"
                            class="form-input w-full border-gray-300 rounded-md" required="{{ $field['required'] }}">
                    @endif
                </div>
            @endforeach

            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Submit</button>
        </form>
    </div>
@endsection
