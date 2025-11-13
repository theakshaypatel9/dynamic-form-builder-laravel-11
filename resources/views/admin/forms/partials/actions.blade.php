<a href="{{ route('admin.forms.edit', $row->id) }}" class="btn btn-sm btn-primary">Edit</a>
<a href="{{ route('admin.forms.show', $row->id) }}" class="btn btn-sm btn-success" target="_blank">View</a>
<a href="{{ route('admin.forms.submissions.index', $row->id) }}" class="btn btn-sm btn-info">View Submissions</a>
<button class="btn btn-sm btn-danger delete-form" data-id="{{ $row->id }}">Delete</button>

<script>
    $(document).on('click', '.delete-form', function() {
        if (!confirm('Delete this form?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: '/admin/forms/' + id,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                location.reload();
            }
        });
    });
</script>
