@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Form</h2>
        <div class="card p-3">
            <div class="mb-3">
                <label>Title</label>
                <input id="title" class="form-control">
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea id="description" class="form-control"></textarea>
            </div>

            <h4>Fields</h4>
            <table class="table" id="fields-table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Options (comma)</th>
                        <th>Required</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div id="form-actions">
                <button class="btn btn-secondary" id="add-field">Add Field</button>
                <button class="btn btn-primary" id="save-form">Save Form</button>
            </div>
        </div>
    </div>

    <!-- field template -->
    <table style="display:none">
        <tbody id="field-row-template">
            <tr>
                <td><input class="form-control field-label" /></td>
                <td>
                    <select class="form-control field-type">
                        <option value="text">Text</option>
                        <option value="email">Email</option>
                        <option value="textarea">Textarea</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="select">Select</option>
                        <option value="number">Number</option>
                    </select>
                </td>
                <td><input class="form-control field-options" placeholder="option1,option2" /></td>
                <td><input type="checkbox" class="field-required" /></td>
                <td><button class="btn btn-danger remove-field">Remove</button></td>
            </tr>
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            function addField() {
                let $row = $('#field-row-template').find('tr').clone();
                $('#fields-table tbody').append($row);
            }

            $('#add-field').on('click', function(e) {
                e.preventDefault();
                addField();
            });

            $('#fields-table').on('click', '.remove-field', function() {
                $(this).closest('tr').remove();
            });

            $('#save-form').on('click', function() {
                let title = $('#title').val();
                let description = $('#description').val();
                let fields = [];

                $('#fields-table tbody tr').each(function() {
                    let label = $(this).find('.field-label').val();
                    let type = $(this).find('.field-type').val();
                    let optionRaw = $(this).find('.field-options').val();
                    let required = $(this).find('.field-required').is(':checked');

                    let options = optionRaw ? optionRaw.split(',').map(s => s.trim()) : [];
                    fields.push({
                        label: label,
                        type: type,
                        option: options,
                        required: required
                    });
                });

                $.ajax({
                    url: "{{ route('admin.forms.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        title: title,
                        description: description,
                        config: {
                            fields: fields
                        }
                    },
                    success: function(res) {
                        alert('Form saved');
                        window.location.href = "{{ route('admin.forms.index') }}";
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
