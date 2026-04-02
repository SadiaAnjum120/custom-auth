@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Sub Category List</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
            <i class="icon-base ti tabler-plus"></i> Create Sub Category
        </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
        <table class="table" id="subcategory-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Main Category</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="subcategory-table-body">
                @include('sub-category.data-table')
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubCategoryModalTitle">Add Sub Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Main Category Dropdown -->
                <div class="mb-3">
                    <label class="form-label">Select Category</label>
                    <select id="mainCategoryId" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sub Category Name</label>
                    <input type="text" id="subCategoryName" class="form-control">
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="subCategoryStatus" checked>
                    <label class="form-check-label">Active</label>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSubCategoryButton">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script>
$(document).ready(function(){


    // GLOBAL EDIT ID

    window.editSubCategoryId = null;


    // DATATABLE INIT (Only Once)

    $('#subcategory-table').DataTable();


    // SAVE / CREATE / UPDATE

    $('#saveSubCategoryButton').off('click').on('click', function(e){

        e.preventDefault();

        var category_id = $('#mainCategoryId').val();
        var name        = $('#subCategoryName').val();
        var is_active   = $('#subCategoryStatus').is(':checked') ? 1 : 0;
        var _token      = $('meta[name="csrf-token"]').attr('content');

        var url  = window.editSubCategoryId
                    ? '/subcategory/update/' + window.editSubCategoryId
                    : '{{ route("subcategory.store") }}';

        var type = window.editSubCategoryId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: {
                category_id: category_id,
                name: name,
                is_active: is_active,
                _token: _token
            },
            success: function(response){

                if(response.success){

                    // Hide Modal
                    $('#addSubCategoryModal').modal('hide');

                    // Reset Form
                    resetForm();

                    // Reload Table
                    $('#subcategory-table-body').html(response.html);


                    // Toastr Notification

                    toastr.success(window.editSubCategoryId ? 'Sub Category updated successfully!' : 'Sub Category added successfully!');
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


    // EDIT

    $(document).on('click', '.edit-subcategory', function(){

        window.editSubCategoryId = $(this).data('id');

        $.ajax({
            url: '/subcategory/edit/' + window.editSubCategoryId,
            type: 'GET',
            success: function(response){

                if(response.success){

                    // Fill Modal Fields
                    $('#mainCategoryId').val(response.data.category_id);
                    $('#subCategoryName').val(response.data.name);
                    $('#subCategoryStatus')
                        .prop('checked', response.data.is_active == 1);

                    // Change Title
                    $('#addSubCategoryModalTitle')
                        .text('Edit Sub Category');

                    // Show Modal
                    new bootstrap.Modal(
                        document.getElementById('addSubCategoryModal')
                    ).show();
                }

            },
            error: function(xhr){
                console.log(xhr.responseText);
                toastr.error('Failed to fetch sub category!');
            }
        });

    });


    // DELETE

    $(document).on('click', '.delete-subcategory', function(){

        var id     = $(this).data('id');
        var _token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed){

                $.ajax({
                    url: '/subcategory/delete/' + id,
                    type: 'DELETE',
                    data: { _token: _token },
                    success: function(response){

                        if(response.success){
                            $('#subcategory-table-body').html(response.html);
                            Swal.fire(
                                'Deleted!',
                                'Sub Category has been deleted.',
                                'success'
                            );
                        }

                    },
                    error: function(xhr){
                        console.log(xhr.responseText);
                        toastr.error('Failed to delete sub category!');
                    }
                });

            }
        });

    });


    // RESET FORM FUNCTION
// RESET MODAL WHEN CLOSED


    function resetForm(){

        $('#mainCategoryId').val('');
        $('#subCategoryName').val('');
        $('#subCategoryStatus').prop('checked', true);
        $('#addSubCategoryModalTitle').text('Add Sub Category');

        window.editSubCategoryId = null;
    }
$('#addSubCategoryModal').on('hidden.bs.modal', function () {
    resetForm();
});
});
</script>
@endsection
