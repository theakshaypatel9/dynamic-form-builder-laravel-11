@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $form->title }}</h2>
        <p>{{ $form->description }}</p>


        <div id="form-fields">
            @foreach ($form->config['fields'] ?? [] as $index => $f)
                <div class="mb-3">
                    <label>{{ $f['label'] }} @if ($f['required'])
                            <span>*</span>
                        @endif
                    </label>
                    @php $name = 'field_'.$index; @endphp
                    @if (in_array($f['type'], ['text', 'email', 'number']))
                        <input name="{{ $name }}" class="form-control" type="{{ $f['type'] }}"
                            @if ($f['required']) required @endif>
                    @elseif($f['type'] == 'textarea')
                        <textarea name="{{ $name }}" class="form-control" @if ($f['required']) required @endif></textarea>
                    @elseif($f['type'] == 'radio')
                        @foreach ($f['option'] as $opt)
                            <div><label><input type="radio" name="{{ $name }}" value="{{ $opt }}"
                                        @if ($f['required']) required @endif> {{ $opt }}</label>
                            </div>
                        @endforeach
                    @elseif($f['type'] == 'checkbox')
                        @foreach ($f['option'] as $opt)
                            <div><label><input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}">
                                    {{ $opt }}</label></div>
                        @endforeach
                    @elseif($f['type'] == 'select')
                        <select name="{{ $name }}" class="form-control"
                            @if ($f['required']) required @endif>
                            <option value="">-- Select --</option>
                            @foreach ($f['option'] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endforeach
        </div>

        <button id="submit-form" class="btn btn-primary">Submit</button>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
