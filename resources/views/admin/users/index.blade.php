@extends('admin.layouts.master')

@section('content')
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header border-0 rounded-top-3 py-3" style="background-color: #5156BE;">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 fw-semibold text-white">Users Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-light btn-sm text-primary fw-medium"
                            style="color: #5156BE !important;">
                            <i class="fas fa-plus me-1"></i> Create New User
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="users-table" class="table table-hover table-bordered align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center fw-semibold"
                                    style="color: #5156BE; font-size: 0.85rem;">ID</th>
                                <th scope="col" class="fw-semibold" style="color: #5156BE; font-size: 0.85rem;">Name</th>
                                <th scope="col" class="fw-semibold" style="color: #5156BE; font-size: 0.85rem;">Email
                                </th>
                                <th scope="col" class="fw-semibold" style="color: #5156BE; font-size: 0.85rem;">Roles
                                </th>
                                <th scope="col" class="text-center fw-semibold"
                                    style="color: #5156BE; font-size: 0.85rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .card-shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.35rem;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-permission {
            color: #5156BE;
            font-size: 15px;
        }

        .badge .badge {
            padding-bottom: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem;
            margin: 0 0.1rem;
            border-radius: 0.3rem;
            color: #5156BE !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #5156BE;
            color: white !important;
            border: none;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.3rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #ced4da;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .btn-action {
            margin: 0 0.2rem;
        }
    </style>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 1000,
                ajax: {
                    url: "{{ route('admin.users.index') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return '<span class="fw-semibold">' + data + '</span>';
                        }
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center action-buttons'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search users...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                drawCallback: function() {
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                }
            });

            $(document).on('click', '.delete-user', function() {
                let userId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete this user. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#6e707e',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/users') }}/" + userId,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.success,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON.error ||
                                        'Something went wrong!',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
