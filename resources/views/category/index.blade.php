@extends('layouts.app')
@section('title', 'Category List')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Category List</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="icon-base ti tabler-plus"></i> Create Category
        </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table" id="category-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="category-table-body">
                @include('category.data-table')
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalTitle">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label for="categoryName" class="form-label">Category Name</label>
                    <input type="text" id="categoryName" class="form-control" placeholder="Enter category name" />
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="categoryStatus" checked />
                    <label class="form-check-label" for="categoryStatus">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCategoryButton">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
$(document).ready(function() {

    window.editCategoryId = null;

    $('#category-table').DataTable();

    // Toastr settings
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    // SAVE / CREATE / UPDATE
    $('#saveCategoryButton').off('click').on('click', function(e) {
        e.preventDefault();

        var name = $('#categoryName').val();
        var is_active = $('#categoryStatus').is(':checked') ? 1 : 0;
        var _token = $('meta[name="csrf-token"]').attr('content');

        var url = window.editCategoryId ? '/category/update/' + window.editCategoryId : '{{ route('category.store') }}';
        var type = window.editCategoryId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: { name: name, is_active: is_active, _token: _token },
            success: function(response) {
                if(response.success){

                    $('#addCategoryModal').modal('hide');
                    $('#categoryName').val('');
                    $('#categoryStatus').prop('checked', true);
                    $('#addCategoryModalTitle').text('Add Category');
                    window.editCategoryId = null;

                    $('#category-table-body').html(response.html);

                    //  Toastr Success
                    toastr.success(response.message);
                }
            },
            error: function(xhr){
                if(xhr.status === 422){
                    var errors = xhr.responseJSON.errors;
                    var message = '';
                    $.each(errors, function(key, value){ message += value[0] + '\n'; });
                    toastr.error(message);
                } else {
                    toastr.error('Something went wrong!');
                    console.log(xhr.responseText);
                }
            }
        });

    });

    // DELETE with SweetAlert
    $(document).on('click', '.delete-category', function() {
        var id = $(this).data('id');
        var _token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: '/category/destroy/' + id,
                    type: 'DELETE',
                    data: { _token: _token },
                    success: function(response) {
                        if(response.success){
                            $('#category-table-body').html(response.html);

                            //  SweetAlert Success
                            Swal.fire(
                                'Deleted!',
                                'Category deleted successfully.',
                                'success'
                            );
                        }
                    },
                    error: function(xhr){
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                });

            }
        });
    });

    // EDIT
    $(document).on('click', '.edit-category', function() {
        window.editCategoryId = $(this).data('id');

        $.ajax({
            url: '/category/edit/' + window.editCategoryId,
            type: 'GET',
            success: function(response){
                if(response.success){
                    $('#categoryName').val(response.data.name);
                    $('#categoryStatus').prop('checked', response.data.is_active == 1);
                    $('#addCategoryModalTitle').text('Edit Category');

                    new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
                }
            },
            error: function(xhr){
                toastr.error("Failed to fetch data!");
            }
        });
    });

    $('#addCategoryModal').on('hidden.bs.modal', function () {
        $('#categoryName').val('');
        $('#categoryStatus').prop('checked', true);
        $('#addCategoryModalTitle').text('Add Category');
        window.editCategoryId = null;
    });
});
</script>
@endsection


