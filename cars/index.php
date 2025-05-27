<?php
include('database.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --info-color: #2196f3;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-bottom: none;
            padding: 1.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }
        
        .car-image {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .car-image:hover {
            transform: scale(1.05);
        }
        
        .no-image {
            width: 100px;
            height: 70px;
            background: linear-gradient(135deg, #e0e0e0 0%, #bdbdbd 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #757575;
        }
        
        .table {
            background-color: white;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .modal-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .delete-modal .modal-header {
            background: linear-gradient(to right, var(--danger-color), #d32f2f);
        }
        
        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .action-btn {
            margin: 2px;
            border-radius: 50px;
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 50px !important;
            margin: 0 2px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 50px;
            padding: 5px 15px;
            border: 1px solid #ddd;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border-radius: 50px;
            padding: 5px 15px;
            border: 1px solid #ddd;
        }
        
        .car-detail-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .car-detail-item:last-child {
            border-bottom: none;
        }
        
        .car-detail-label {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .car-detail-value {
            font-weight: 500;
        }
        
        .car-image-large {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-car-front me-2"></i> Car Inventory Management</h3>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCarModal">
                        <i class="bi bi-plus-circle"></i> Add New Car
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="carsTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Car Modal -->
    <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCarModalLabel"><i class="bi bi-car-front me-2"></i> Add New Car</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addCarForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="make" class="form-label">Make</label>
                                    <input type="text" class="form-control" id="make" name="make" required>
                                </div>
                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control" id="model" name="model" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <input type="number" class="form-control" id="year" name="year" min="1900" max="<?= date('Y') + 1 ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Upload a high-quality image of the car (max 5MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Car</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Car Modal -->
    <div class="modal fade" id="viewCarModal" tabindex="-1" aria-labelledby="viewCarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCarModalLabel"><i class="bi bi-car-front me-2"></i> Car Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="carDetails">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Car Modal -->
    <div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCarModalLabel"><i class="bi bi-pencil-square me-2"></i> Edit Car</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCarForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body" id="editCarFormBody">
                        <!-- Content will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Car Modal -->
    <div class="modal fade delete-modal" id="deleteCarModal" tabindex="-1" aria-labelledby="deleteCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCarModalLabel"><i class="bi bi-exclamation-triangle me-2"></i> Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-center mb-3">Are you sure you want to delete this car?</h5>
                    <p class="text-center text-muted">This action cannot be undone. All information about this car will be permanently removed from the system.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete"><i class="bi bi-trash me-1"></i> Delete Permanently</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
    $(document).ready(function() {
        var table = $('#carsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "fetch_cars.php",
                "type": "POST"
            },
            "columns": [
                { 
                    "data": "image_path",
                    "render": function(data, type, row) {
                        if (data) {
                            return '<img src="' + data + '" class="car-image">';
                        } else {
                            return '<div class="no-image"><i class="bi bi-car-front"></i></div>';
                        }
                    },
                    "orderable": false
                },
                { "data": "make" },
                { "data": "model" },
                { "data": "year" },
                { 
                    "data": "price",
                    "render": function(data) {
                        return '$' + parseFloat(data).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                },
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-sm btn-info view-btn action-btn" data-id="${data}" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning edit-btn action-btn" data-id="${data}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn action-btn" data-id="${data}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                    },
                    "orderable": false
                }
            ],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search cars...",
                "lengthMenu": "Show _MENU_ cars per page",
                "zeroRecords": "No cars found",
                "info": "Showing _START_ to _END_ of _TOTAL_ cars",
                "infoEmpty": "No cars available",
                "infoFiltered": "(filtered from _MAX_ total cars)",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "<i class='bi bi-chevron-right'></i>",
                    "previous": "<i class='bi bi-chevron-left'></i>"
                }
            },
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "drawCallback": function(settings) {
                $('.paginate_button.previous').html('<i class="bi bi-chevron-left"></i>');
                $('.paginate_button.next').html('<i class="bi bi-chevron-right"></i>');
            }
        });

        $('#addCarForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            
            $.ajax({
                url: 'add_car.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#addCarModal').find('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
                },
                success: function(response) {
                    $('#addCarModal').modal('hide');
                    $('#addCarForm')[0].reset();
                    table.ajax.reload();
                    
                    // Show success toast
                    $('body').append(`
                        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-success text-white">
                                    <strong class="me-auto">Success</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Car added successfully!
                                </div>
                            </div>
                        </div>
                    `);
                    
                    // Remove toast after 3 seconds
                    setTimeout(function() {
                        $('.toast').toast('hide').remove();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                },
                complete: function() {
                    $('#addCarModal').find('button[type="submit"]').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Save Car');
                }
            });
        });

        $(document).on('click', '.view-btn', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: 'fetch_car.php',
                type: 'POST',
                data: { id: id, action: 'view' },
                beforeSend: function() {
                    $('#carDetails').html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                success: function(response) {
                    $('#carDetails').html(response);
                    $('#viewCarModal').modal('show');
                }
            });
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $('#edit_id').val(id);
            
            $.ajax({
                url: 'fetch_car.php',
                type: 'POST',
                data: { id: id, action: 'edit' },
                beforeSend: function() {
                    $('#editCarFormBody').html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                success: function(response) {
                    $('#editCarFormBody').html(response);
                    $('#editCarModal').modal('show');
                }
            });
        });

        $('#editCarForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            
            $.ajax({
                url: 'update_car.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#editCarModal').find('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
                },
                success: function(response) {
                    $('#editCarModal').modal('hide');
                    table.ajax.reload();
                    
                    // Show success toast
                    $('body').append(`
                        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-success text-white">
                                    <strong class="me-auto">Success</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Car updated successfully!
                                </div>
                            </div>
                        </div>
                    `);
                    
                    // Remove toast after 3 seconds
                    setTimeout(function() {
                        $('.toast').toast('hide').remove();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                },
                complete: function() {
                    $('#editCarModal').find('button[type="submit"]').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Save Changes');
                }
            });
        });

        var carIdToDelete;
        $(document).on('click', '.delete-btn', function() {
            carIdToDelete = $(this).data('id');
            $('#deleteCarModal').modal('show');
        });

        $('#confirmDelete').on('click', function() {
            $.ajax({
                url: 'delete_car.php',
                type: 'POST',
                data: { id: carIdToDelete },
                beforeSend: function() {
                    $('#confirmDelete').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
                },
                success: function(response) {
                    $('#deleteCarModal').modal('hide');
                    table.ajax.reload();
                    
                    // Show success toast
                    $('body').append(`
                        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-success text-white">
                                    <strong class="me-auto">Success</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Car deleted successfully!
                                </div>
                            </div>
                        </div>
                    `);
                    
                    // Remove toast after 3 seconds
                    setTimeout(function() {
                        $('.toast').toast('hide').remove();
                    }, 3000);
                },
                complete: function() {
                    $('#confirmDelete').prop('disabled', false).html('<i class="bi bi-trash me-1"></i> Delete Permanently');
                }
            });
        });
    });
    </script>
</body>
</html>