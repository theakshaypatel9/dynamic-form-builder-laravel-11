@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Form
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form id="editFormBuilder" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Form Title -->
                    <div class="mb-3">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Form Title</label>
                        <input type="text" name="title"
                            class="form-input w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ $form->title }}" required>
                    </div>

                    {{-- description --}}
                    <div class="mb-5">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Form Description</label>
                        <input type="text" name="description"
                            class="form-input w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ $form->description }}" required>
                    </div>

                    <!-- Dynamic Fields Section -->
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-800">Form Fields</h3>
                            <button type="button" id="add-field"
                                class="px-4 py-2 bg-indigo-600 text-black rounded-md hover:bg-indigo-700 btn btn-secondary btn-sm">
                                + Add Field
                            </button>
                        </div>

                        <div id="dynamic-fields" class="space-y-3">
                            <!-- Fields will be dynamically rendered here -->
                        </div>
                    </div>

                    <!-- Hidden JSON config -->
                    <input type="hidden" name="config" id="config-field" value='@json($form->config)'>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="submit"
                            class="px-5 py-2 bg-green-600 text-black font-medium rounded-md hover:bg-green-700 btn btn-primary btn-sm">
                            💾 Update Form
                        </button>
                    </div>
                </form>
            </div>

            <!-- Success/Error Messages -->
            <div id="form-messages" class="mt-4"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            let config = @json($form->config);
            let fields = config?.fields || [];

            const renderFields = () => {
                const container = $('#dynamic-fields');
                container.html('');

                if (fields.length === 0) {
                    container.html(`<p class="text-gray-500 italic">No fields added yet.</p>`);
                }

                fields.forEach((field, index) => {
                    container.append(`
                <div class="p-3 border rounded-md flex justify-between items-center bg-gray-50">
                    <div>
                        <span class="font-semibold">${field.label}</span>
                        <span class="text-sm text-gray-600 ml-1">(${field.type})</span>
                        ${field.option ? `<span class="text-xs text-gray-500 ml-2">[${field.option}]</span>` : ''}
                    </div>
                    <div class="space-x-2">
                        <button type="button" class="px-2 py-1 text-blue-600 border border-blue-500 rounded edit-field hover:bg-blue-50" data-index="${index}">Edit</button>
                        <button type="button" class="px-2 py-1 text-red-600 border border-red-500 rounded delete-field hover:bg-red-50" data-index="${index}">Delete</button>
                    </div>
                </div>
            `);
                });

                $('#config-field').val(JSON.stringify({
                    fields
                }));
            };

            renderFields();

            // Add new field
            $('#add-field').on('click', function() {
                const label = prompt('Enter Field Label:');
                const type = prompt('Enter Field Type (text, email, textarea, radio, select):');
                let option = '';

                if (['radio', 'select', 'checkbox'].includes(type)) {
                    option = prompt('Enter options (comma separated):');
                }

                if (label && type) {
                    fields.push({
                        label,
                        type,
                        option: option ? option.split(',').map(o => o.trim()) : [],
                        required: true
                    });
                    renderFields();
                }
            });

            // Edit/Delete buttons
            $(document).on('click', '.delete-field', function() {
                const index = $(this).data('index');
                if (confirm('Are you sure you want to delete this field?')) {
                    fields.splice(index, 1);
                    renderFields();
                }
            });

            $(document).on('click', '.edit-field', function() {
                const index = $(this).data('index');
                const field = fields[index];
                const newLabel = prompt('Edit Field Label:', field.label);
                const newType = prompt('Edit Field Type (text, email, textarea, radio, select):', field
                    .type);
                let newOption = '';

                if (['radio', 'select', 'checkbox'].includes(newType)) {
                    newOption = prompt('Edit Options (comma separated):', field.option ? field.option.join(
                        ', ') : '');
                }

                if (newLabel && newType) {
                    fields[index].label = newLabel;
                    fields[index].type = newType;
                    fields[index].option = newOption ? newOption.split(',').map(o => o.trim()) : [];
                    renderFields();
                }
            });

            // 🔄 Submit via AJAX
            $('#editFormBuilder').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    title: $('input[name="title"]').val(),
                    description: $('input[name="description"]').val(),
                    config: {
                        fields: fields
                    },
                    _method: 'PUT'
                };

                $.ajax({
                    url: "{{ route('admin.forms.update', $form->id) }}",
                    method: "POST", // Laravel will detect PUT via _method
                    data: formData,
                    beforeSend: function() {
                        $('#form-messages').html(
                            `<p class="text-blue-600">⏳ Updating form...</p>`);
                    },
                    success: function(response) {
                        $('#form-messages').html(
                            `<p class="text-green-600 font-semibold">✅ Form updated successfully!</p>`
                        );

                        // Optionally redirect after 1s
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.forms.index') }}";
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#form-messages').html(
                            `<p class="text-red-600 font-semibold">❌ Update failed. Please check console.</p>`
                        );
                    }
                });
            });
        });
    </script>
@endsection
