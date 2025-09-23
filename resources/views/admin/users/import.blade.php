@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header-container bg-gradient-primary rounded-3 mb-4">
                <div class="container py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="h2 text-white mb-0"><i class="fas fa-users me-2"></i>Import Users</h1>
                            <p class="text-white-50 mb-0">Upload CSV file to import multiple users at once</p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <a href="{{ route('admin.users.import.template') }}" class="btn btn-light">
                                    <i class="fas fa-download me-2"></i>Download Template
                                </a>
                                <a href="{{ route('admin.imports.history') }}" class="btn btn-outline-light">
                                    <i class="fas fa-history me-2"></i>Import History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <!-- Upload Card -->
                    <div id="upload-section">
                        <div class="drop-zone border-3 border-dashed rounded-3 p-5 text-center mb-4" id="dropZone">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Drop your CSV file here</h4>
                            <p class="text-muted mb-3">or click to browse</p>
                            <input type="file" name="file" id="file" class="d-none" accept=".csv,.txt">
                            <button type="button" class="btn btn-primary btn-lg" id="browse-btn">
                                <i class="fas fa-folder-open me-2"></i>Browse Files
                            </button>
                            <div class="mt-2">
                                <small class="text-muted">Supports: CSV files up to 10MB</small>
                            </div>
                        </div>

                        <div id="file-info" class="alert alert-info d-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-csv me-2"></i>
                                    <span id="file-name"></span>
                                    <small class="text-muted ms-2" id="file-size"></small>
                                </div>
                                <button type="button" class="btn-close" id="remove-file"></button>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" id="import-btn" class="btn btn-success btn-lg px-5" disabled>
                                <i class="fas fa-rocket me-2"></i>Start Import
                            </button>
                        </div>
                    </div>

                    <!-- Progress Section -->
                    <div id="progress-section" class="d-none">
                        <div class="text-center mb-4">
                            <i class="fas fa-sync-alt fa-spin fa-2x text-primary mb-3"></i>
                            <h3 class="text-primary">Importing Users</h3>
                            <p class="text-muted">Please wait while we process your file</p>
                        </div>

                        <!-- Animated Progress Bar -->
                        <div class="progress-container mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-sm">Progress</span>
                                <span class="text-sm fw-bold" id="progress-percent">0%</span>
                            </div>
                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar" style="width: 0%; border-radius: 6px;"></div>
                            </div>
                        </div>

                        <!-- Stats Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="stat-card bg-primary text-white rounded-3 p-3 text-center">
                                    <i class="fas fa-list fa-2x mb-2"></i>
                                    <h4 class="mb-1" id="total-rows">0</h4>
                                    <small>Total Rows</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-success text-white rounded-3 p-3 text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h4 class="mb-1" id="successful-rows">0</h4>
                                    <small>Successful</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-warning text-white rounded-3 p-3 text-center">
                                    <i class="fas fa-sync fa-2x mb-2"></i>
                                    <h4 class="mb-1" id="processed-rows">0</h4>
                                    <small>Processed</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-danger text-white rounded-3 p-3 text-center">
                                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                    <h4 class="mb-1" id="failed-rows">0</h4>
                                    <small>Failed</small>
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Import Details</h6>
                                        <div class="details-list">
                                            <div class="detail-item">
                                                <span class="label">Import ID:</span>
                                                <span class="value" id="import-id">-</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="label">Status:</span>
                                                <span class="value"><span id="import-status" class="badge bg-warning">Processing</span></span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="label">Started:</span>
                                                <span class="value" id="start-time">-</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="label">Last Update:</span>
                                                <span class="value" id="last-update">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-chart-line me-2"></i>Performance</h6>
                                        <div id="performance-chart" style="height: 120px;">
                                            <!-- Simple bar chart using CSS -->
                                            <div class="chart-bars d-flex align-items-end justify-content-around h-100">
                                                <div class="chart-bar bg-primary" style="height: 30%;"></div>
                                                <div class="chart-bar bg-success" style="height: 70%;"></div>
                                                <div class="chart-bar bg-warning" style="height: 50%;"></div>
                                                <div class="chart-bar bg-danger" style="height: 20%;"></div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">Real-time processing metrics</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" id="cancel-btn" class="btn btn-outline-danger">
                                <i class="fas fa-times me-2"></i>Cancel Import
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="success-animation mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-success mb-3">Import Completed!</h3>
                <p id="success-message" class="text-muted mb-4">Your users have been imported successfully.</p>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="bg-light rounded p-3">
                            <h5 class="text-success mb-1" id="modal-successful">0</h5>
                            <small class="text-muted">Successful</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3">
                            <h5 class="text-danger mb-1" id="modal-failed">0</h5>
                            <small class="text-muted">Failed</small>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="new-import-btn">
                        <i class="fas fa-plus me-2"></i>Start New Import
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="error-animation mb-4">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-danger mb-3">Import Failed</h3>
                <p id="error-message" class="text-muted mb-4">There was an error processing your file.</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fas fa-redo me-2"></i>Try Again
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Progress Bar -->
<div id="floating-progress" class="floating-progress d-none">
    <div class="floating-content">
        <div class="d-flex align-items-center">
            <i class="fas fa-sync-alt fa-spin me-3 text-primary"></i>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-sm">Importing users...</span>
                    <span class="text-sm fw-bold" id="floating-percent">0%</span>
                </div>
                <div class="progress" style="height: 4px;">
                    <div id="floating-progress-bar" class="progress-bar" style="width: 0%"></div>
                </div>
            </div>
            <button type="button" class="btn-close ms-3" id="minimize-progress"></button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .header-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .drop-zone {
        border-color: #dee2e6;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .drop-zone.active {
        border-color: #0d6efd;
        background-color: #e7f1ff;
        transform: scale(1.02);
    }

    .drop-zone:hover {
        border-color: #0d6efd;
        cursor: pointer;
    }

    .stat-card {
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .detail-item {
        display: flex;
        justify-content: between;
        margin-bottom: 0.5rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item .label {
        font-weight: 600;
        color: #6c757d;
        min-width: 120px;
    }

    .detail-item .value {
        color: #495057;
    }

    .chart-bar {
        width: 20px;
        border-radius: 2px 2px 0 0;
        transition: height 0.5s ease;
    }

    .floating-progress {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 350px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        z-index: 9999;
        border: 1px solid #e0e0e0;
    }

    .floating-content {
        padding: 15px;
    }

    .progress-container {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .success-animation {
        animation: bounceIn 0.6s ease;
    }

    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-lg {
        padding: 12px 30px;
        font-size: 1.1rem;
    }

    .card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Smooth transitions for all elements */
    * {
        transition: all 0.3s ease;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        let importId = null;
        let progressInterval = null;
        let currentFile = null;

        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // File selection handlers - Using event delegation for dynamically created elements
        $(document).on('click', '#browse-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#file')[0].click(); // Use [0] to access the DOM element directly
        });

        $(document).on('click', '#dropZone', function(e) {
            // Only trigger file dialog if clicking on the drop zone itself, not child elements
            if (e.target === this || $(e.target).hasClass('fas') || $(e.target).is('h4, p')) {
                e.preventDefault();
                $('#file')[0].click();
            }
        });

        $(document).on('click', '#change-file', function(e) {
            e.preventDefault();
            e.stopPropagation();
            resetFileSelection();
        });

        // File input change handler
        $('#file').on('change', function(e) {
            if (this.files && this.files.length > 0) {
                handleFileSelect(this.files[0]);
            }
        });

        // Drag and drop functionality
        $('#dropZone').on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('active');
        });

        $('#dropZone').on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('active');
        });

        $('#dropZone').on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('active');

            if (e.originalEvent.dataTransfer.files.length > 0) {
                handleFileSelect(e.originalEvent.dataTransfer.files[0]);
            }
        });

        function handleFileSelect(file) {
            // File validation
            const allowedTypes = ['text/csv', 'application/csv', 'text/plain'];
            const isValidType = allowedTypes.includes(file.type) || file.name.match(/\.(csv|txt)$/i);

            if (!isValidType) {
                showErrorModal('Please select a CSV file.');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                showErrorModal('File size must be less than 10MB.');
                return;
            }

            if (file.size === 0) {
                showErrorModal('The selected file is empty.');
                return;
            }

            currentFile = file;

            // Update file info
            $('#file-name').text(file.name);
            $('#file-size').text(formatFileSize(file.size));
            $('#file-info').removeClass('d-none').addClass('fade-in');

            // Enable import button with animation
            $('#import-btn').prop('disabled', false).addClass('pulse-animation');

            // Update drop zone with success state
            updateDropZoneSuccess(file);
        }

        function updateDropZoneSuccess(file) {
            $('#dropZone').html(`
                <i class="fas fa-file-csv fa-3x text-success mb-3"></i>
                <h4 class="text-success">File Selected</h4>
                <p class="text-muted mb-3">${file.name} (${formatFileSize(file.size)})</p>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary" id="change-file">
                        <i class="fas fa-redo me-2"></i>Change File
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="preview-file">
                        <i class="fas fa-eye me-2"></i>Preview
                    </button>
                </div>
            `);
        }

        function resetFileSelection() {
            currentFile = null;
            $('#file').val(''); // Clear the file input
            $('#file-info').addClass('d-none').removeClass('fade-in');
            $('#import-btn').prop('disabled', true).removeClass('pulse-animation');

            // Reset drop zone to initial state
            $('#dropZone').html(`
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Drop your CSV file here</h4>
                <p class="text-muted mb-3">or click to browse</p>
                <button type="button" class="btn btn-primary btn-lg" id="browse-btn">
                    <i class="fas fa-folder-open me-2"></i>Browse Files
                </button>
                <div class="mt-2">
                    <small class="text-muted">Supports: CSV files up to 10MB</small>
                </div>
            `);
        }

        // File preview functionality
        $(document).on('click', '#preview-file', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (currentFile) {
                previewCSVFile(currentFile);
            }
        });

        function previewCSVFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const text = e.target.result;
                const lines = text.split('\n').slice(0, 6); // Show first 5 rows + header

                let previewHtml = '<div class="table-responsive"><table class="table table-sm table-bordered">';
                lines.forEach((line, index) => {
                    if (line.trim()) {
                        const cells = line.split(',');
                        previewHtml += '<tr>';
                        cells.forEach(cell => {
                            const tag = index === 0 ? 'th' : 'td';
                            previewHtml += `<${tag} class="${index === 0 ? 'bg-light' : ''}">${cell.trim()}</${tag}>`;
                        });
                        previewHtml += '</tr>';
                    }
                });
                previewHtml += '</table></div>';

                // Show preview in modal
                showPreviewModal(previewHtml, lines.length - 1);
            };
            reader.readAsText(file);
        }

        function showPreviewModal(content, rowCount) {
            const modalHtml = `
                <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-file-csv me-2"></i>File Preview
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Showing first ${rowCount} rows of data
                                </div>
                                ${content}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="proceed-import">
                                    <i class="fas fa-rocket me-2"></i>Proceed with Import
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if any
            $('#previewModal').remove();
            $('body').append(modalHtml);
            $('#previewModal').modal('show');

            // Handle proceed button
            $('#proceed-import').on('click', function() {
                $('#import-btn').focus().addClass('pulse-animation');
            });
        }

        // Remove file handler
        $('#remove-file').on('click', function(e) {
            e.preventDefault();
            resetFileSelection();
        });

        // Import button handler
        $('#import-btn').on('click', function(e) {
            e.preventDefault();
            if (!currentFile) {
                showErrorModal('Please select a file first.');
                return;
            }
            startImport();
        });

        function startImport() {
            const formData = new FormData();
            formData.append('file', currentFile);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Show progress section
            $('#upload-section').addClass('d-none');
            $('#progress-section').removeClass('d-none').addClass('fade-in');

            // Show floating progress bar
            $('#floating-progress').removeClass('d-none').addClass('fade-in');

            // Reset progress values
            updateProgress({
                progress_percent: 0,
                total_rows: 0,
                processed_rows: 0,
                successful_rows: 0,
                failed_rows: 0,
                status: 'starting'
            });

            $('#import-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Starting Import...');

            $.ajax({
                url: '{{ route("admin.users.import") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                timeout: 300000, // 5 minutes timeout
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        importId = response.data.import_id;
                        $('#import-id').text(importId);
                        $('#start-time').text(new Date().toLocaleString());

                        // Start progress polling
                        pollImportProgress();
                    } else {
                        showErrorModal(response.message || 'Import failed to start');
                        resetToUpload();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    let errorMessage = 'An error occurred while starting the import.';

                    if (textStatus === 'timeout') {
                        errorMessage = 'The import request timed out. Please try with a smaller file.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.message || errorMessage;
                        } catch (e) {
                            // Keep default error message
                        }
                    }

                    console.error('Import error:', textStatus, errorThrown, xhr);
                    showErrorModal(errorMessage);
                    resetToUpload();
                }
            });
        }

        function pollImportProgress() {
            if (!importId) return;

            progressInterval = setInterval(() => {
                $.ajax({
                    url: `/admin/imports/${importId}/status-json`,
                    type: 'GET',
                    timeout: 10000,
                    success: function(response) {
                        console.log('Progress response:', response); // Add this for debugging
                        updateProgress(response);

                        if (response.status === 'completed' || response.status === 'failed') {
                            clearInterval(progressInterval);
                            progressInterval = null;

                            if (response.status === 'completed') {
                                setTimeout(() => {
                                    showSuccessModal(response);
                                }, 1000);
                            } else {
                                setTimeout(() => {
                                    showErrorModal(response.error_message || 'Import failed');
                                }, 1000);
                            }
                        }
                    },
                    error: function(xhr, textStatus) {
                        console.error('Progress poll error:', textStatus, xhr.status, xhr.responseText);
                        // Add specific error handling for 404s
                        if (xhr.status === 404) {
                            console.error('Status endpoint not found - check your routes');
                            clearInterval(progressInterval);
                            showErrorModal('Unable to track import progress. Please check the import manually.');
                        }
                    }
                });
            }, 2000);
        }

        function updateProgress(data) {
            const percent = Math.min(data.progress_percent || 0, 100);

            // Update main progress bar with animation
            $('#progress-bar').css('width', percent + '%');
            $('#progress-percent').text(percent + '%');

            // Update floating progress bar
            $('#floating-progress-bar').css('width', percent + '%');
            $('#floating-percent').text(percent + '%');

            // Update stats with animation
            animateCounter('#total-rows', data.total_rows || 0);
            animateCounter('#processed-rows', data.processed_rows || 0);
            animateCounter('#successful-rows', data.successful_rows || 0);
            animateCounter('#failed-rows', data.failed_rows || 0);

            // Update status
            const statusText = (data.status || 'processing').charAt(0).toUpperCase() + (data.status || 'processing').slice(1);
            $('#import-status').text(statusText);
            $('#last-update').text(new Date().toLocaleTimeString());

            // Update status badge color
            const statusBadge = $('#import-status');
            statusBadge.removeClass('bg-warning bg-success bg-danger bg-info');

            switch(data.status) {
                case 'processing':
                    statusBadge.addClass('bg-warning');
                    break;
                case 'completed':
                    statusBadge.addClass('bg-success');
                    break;
                case 'failed':
                    statusBadge.addClass('bg-danger');
                    break;
                default:
                    statusBadge.addClass('bg-info');
            }
        }

        function animateCounter(selector, targetValue) {
            const $element = $(selector);
            const currentValue = parseInt($element.text()) || 0;

            if (currentValue !== targetValue) {
                $({ counter: currentValue }).animate({ counter: targetValue }, {
                    duration: 500,
                    step: function() {
                        $element.text(Math.ceil(this.counter));
                    },
                    complete: function() {
                        $element.text(targetValue);
                    }
                });
            }
        }

        function showSuccessModal(data) {
            const successMessage = `Successfully imported ${data.successful_rows || 0} users.` +
                (data.failed_rows ? ` ${data.failed_rows} records failed.` : '');

            $('#success-message').text(successMessage);
            $('#modal-successful').text(data.successful_rows || 0);
            $('#modal-failed').text(data.failed_rows || 0);

            $('#successModal').modal('show');

            // Hide floating progress
            setTimeout(() => {
                $('#floating-progress').addClass('d-none');
            }, 500);
        }

        function showErrorModal(message) {
            $('#error-message').text(message || 'An unknown error occurred.');
            $('#errorModal').modal('show');

            // Hide floating progress
            setTimeout(() => {
                $('#floating-progress').addClass('d-none');
            }, 500);
        }

        function resetToUpload() {
            $('#progress-section').addClass('d-none');
            $('#upload-section').removeClass('d-none');
            $('#import-btn').prop('disabled', !currentFile).html('<i class="fas fa-rocket me-2"></i>Start Import');

            if (!currentFile) {
                resetFileSelection();
            }

            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }

            importId = null;
        }

        // Modal event handlers
        $('#new-import-btn').on('click', function() {
            resetToUpload();
            $('#successModal').modal('hide');
        });

        $('#cancel-btn').on('click', function() {
            if (confirm('Are you sure you want to cancel the import? This action cannot be undone.')) {
                resetToUpload();
                if (progressInterval) {
                    clearInterval(progressInterval);
                    progressInterval = null;
                }
            }
        });

        $('#minimize-progress').on('click', function() {
            $('#floating-progress').addClass('d-none');
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Prevent form submission on enter key
        $(document).on('keypress', function(e) {
            if (e.which === 13 && !$(e.target).is('textarea')) {
                e.preventDefault();
            }
        });

        // Clear any existing intervals on page unload
        $(window).on('beforeunload', function() {
            if (progressInterval) {
                clearInterval(progressInterval);
            }
        });
    });
</script>
@endpush
