@extends('layouts.app')

@section('content')
    <h2>Categorize Files</h2>
    <table class="table">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Category</th>
                <th>Stage</th>
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
                        <option value="{{ $category }}" {{ $file->category == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control stage-select" data-file-id="{{ $file->id }}">
                        @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ $file->stage == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary update-file" data-file-id="{{ $file->id }}">Update</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('.update-file');
    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fileId = this.dataset.fileId;
            const category = document.querySelector(`.category-select[data-file-id="${fileId}"]`).value;
            const stage = document.querySelector(`.stage-select[data-file-id="${fileId}"]`).value;
            
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
                body: JSON.stringify({ category: category, stage: stage })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('File updated successfully');
                    // Optionally, you can remove the row or update its appearance
                    // this.closest('tr').remove();
                } else {
                    alert('Error updating file');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating file');
            });
        });
    });
});
</script>
@endsection
