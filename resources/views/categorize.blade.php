@extends('layouts.app')

@section('content')
    <h2>Categorize Files</h2>
    <table class="table">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>{{ $file->name }}</td>
                <td>
                    <select class="form-control category-select" data-file-id="{{ $file->id }}">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary update-category" data-file-id="{{ $file->id }}">Update</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('.update-category');
    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fileId = this.dataset.fileId;
            const category = document.querySelector(`.category-select[data-file-id="${fileId}"]`).value;
            
            if (!category) {
                alert('Please select a category');
                return;
            }

            fetch(`/categorize/${fileId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ category: category })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Category updated successfully');
                    this.closest('tr').remove();
                } else {
                    alert('Error updating category');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating category');
            });
        });
    });
});
</script>
@endsection
