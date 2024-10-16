@extends('layouts.app')

@section('content')
    <h1>File Upload</h1>
        <div id="drop-area" class="border border-dashed p-5 text-center">
            <p>Drag and drop files here or click to select</p>
            <input type="file" id="fileInput" style="display: none;">
        </div>
        <div id="progress-wrapper" class="mt-3" style="display: none;">
            <div class="progress">
                <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </div>
        <div id="message" class="mt-3 alert" style="display: none;"></div>
        <h2 class="mt-5">Uploaded Files</h2>
        <ul id="file-list" class="list-group">
            @forelse ($files as $file)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $file->name }}
                    <button class="btn btn-danger btn-sm delete-file" data-id="{{ $file->id }}">Delete</button>
                </li>
            @empty
                <li class="list-group-item">No files uploaded</li>
            @endforelse
        </ul>
    </div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('fileInput');
        const progressWrapper = document.getElementById('progress-wrapper');
        const progressBar = document.getElementById('progress-bar');
        const fileList = document.getElementById('file-list');
        const messageElement = document.getElementById('message');

        // Set up CSRF token for all Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        dropArea.addEventListener('click', () => fileInput.click());
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('bg-light');
        });
        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('bg-light');
        });
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('bg-light');
            const files = e.dataTransfer.files;
            if (files.length) {
                uploadFile(files[0]);
            }
        });
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                uploadFile(fileInput.files[0]);
            }
        });

        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);

            progressWrapper.style.display = 'block';
            progressBar.style.width = '0%';
            progressBar.textContent = '0%';

            axios.post('/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressBar.style.width = percentCompleted + '%';
                    progressBar.textContent = percentCompleted + '%';
                }
            }).then(response => {
                if (response.data.success) {
                    updateFileList();
                    showMessage('File uploaded successfully', 'success');
                }
            }).catch(error => {
                console.error('Error uploading file:', error);
                showMessage('Error uploading file. Please try again.', 'danger');
            }).finally(() => {
                progressWrapper.style.display = 'none';
            });
        }

        function updateFileList() {
            axios.get('/').then(response => {
                const parser = new DOMParser();
                const htmlDoc = parser.parseFromString(response.data, 'text/html');
                const newFileList = htmlDoc.getElementById('file-list');
                fileList.innerHTML = newFileList.innerHTML;
                addDeleteListeners();
            }).catch(error => {
                console.error('Error updating file list:', error);
            });
        }

        function addDeleteListeners() {
            document.querySelectorAll('.delete-file').forEach(button => {
                button.addEventListener('click', function() {
                    const fileId = this.getAttribute('data-id');
                    deleteFile(fileId);
                });
            });
        }

        function deleteFile(fileId) {
            axios.delete(`/delete/${fileId}`)
                .then(response => {
                    if (response.data.success) {
                        updateFileList();
                        showMessage(response.data.message, 'success');
                    } else {
                        showMessage(response.data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error deleting file:', error);
                    showMessage('Error deleting file. Please try again.', 'danger');
                });
        }

        function showMessage(message, type) {
            messageElement.textContent = message;
            messageElement.className = `mt-3 alert alert-${type}`;
            messageElement.style.display = 'block';
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 5000);
        }

        // Add delete listeners when the page loads
        addDeleteListeners();
    </script>
@endsection
