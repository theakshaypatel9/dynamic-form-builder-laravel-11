<a href="{{ route('admin.forms.submissions.show', [$formId, $row->id]) }}" class="btn btn-sm btn-primary">View</a>
<button class="btn btn-sm btn-danger delete-submission" data-id="{{ $row->id }}"
    data-form="{{ $formId }}">Delete</button>

<script>
    $(document).on('click', '.delete-submission', function() {
        if (!confirm('Delete this submission?')) return;
        let formId = $(this).data('form');
        let id = $(this).data('id');
        $.ajax({
            url: '/admin/forms/' + formId + '/submissions/' + id,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                $('#submissions-table').DataTable().ajax.reload();
            }
        });
    });
</script>
