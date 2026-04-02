@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Product List</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="icon-base ti tabler-plus"></i> Create Product
        </button>
    </div>

    <style>
        #product-table th,
        #product-table td { white-space: nowrap; }

        #product-table th:nth-child(9),
        #product-table td:nth-child(9),
        #product-table th:nth-child(10),
        #product-table td:nth-child(10) { min-width: 170px; }
    </style>

    <div class="card-datatable table-responsive pt-0">
        <table class="table" id="product-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>SubCategory</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                @include('product.data-table')
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalTitle">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Category</label>
                        <select id="productCategoryId" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Sub Category</label>
                        <select id="productSubCategoryId" class="form-control">
                            <option value="">Select Sub Category</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" id="productName" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" id="productPrice" class="form-control" step="0.01">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cost</label>
                        <input type="number" id="productCost" class="form-control" step="0.01">
                    </div>
                    <div class="row">




    <!-- CREATE MODE QUANTITY -->
    <div class="col-md-4 mb-3" id="createQuantityWrapper">
        <label class="form-label">Quantity</label>
        <input type="number" id="productQuantity" class="form-control">
    </div>
</div>

<!-- EDIT MODE STOCK SECTION -->
<div id="stockSection" style="display:none;">

    <div class="mb-2">
        <label class="form-label">Current Stock</label>
        <input type="text" id="currentStock" class="form-control" readonly>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Stock Action</label>
            <select id="stockAction" class="form-control">
                <option value="">Select Action</option>
                <option value="add">Add</option>
                <option value="subtract">Subtract</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" id="stockQuantity" class="form-control">
        </div>
    </div>

</div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" id="productImage" class="form-control">
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="productStatus" checked>
                    <label class="form-check-label">Active</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProductButton">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){

    window.editProductId = null;
    window.editSubCategoryId = null;

    $('#product-table').DataTable();

    // =========================
    // CATEGORY CHANGE
    // =========================
    $('#productCategoryId').on('change', function(){
        var categoryId = $(this).val();
        var subCategoryDropdown = $('#productSubCategoryId');

        subCategoryDropdown.empty().append('<option value="">Select Sub Category</option>');

        if(categoryId){
            $.get('/product/subcategories/' + categoryId, function(data){
                $.each(data, function(index, sub){
                    subCategoryDropdown.append('<option value="'+sub.id+'">'+sub.name+'</option>');
                });

                if(window.editSubCategoryId){
                    subCategoryDropdown.val(window.editSubCategoryId);
                    window.editSubCategoryId = null;
                }
            });
        }
    });

    // =========================
    // SAVE PRODUCT
    // =========================
    $('#saveProductButton').off('click').on('click', function(e){
        e.preventDefault();

        var formData = new FormData();

        formData.append('category_id', $('#productCategoryId').val());
        formData.append('sub_category_id', $('#productSubCategoryId').val());
        formData.append('name', $('#productName').val());
        formData.append('price', $('#productPrice').val());
        formData.append('cost', $('#productCost').val());
        formData.append('quantity', $('#productQuantity').val());
        formData.append('stock_action', $('#stockAction').val());
        formData.append('stock_quantity', $('#stockQuantity').val());
        formData.append('is_active', $('#productStatus').is(':checked') ? 1 : 0);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        var imageFile = $('#productImage')[0].files[0];
        if(imageFile) formData.append('image', imageFile);

        var url = window.editProductId
            ? '/product/update/' + window.editProductId
            : '{{ route("product.store") }}';

        if(window.editProductId){
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if(response.success){
                    $('#addProductModal').modal('hide');
                    resetForm();
                    $('#product-table-body').html(response.html);
                    toastr.success(response.message);
                }
            },
            error: function(xhr){
                if(xhr.status === 422){
                    var errors = xhr.responseJSON.errors;
                    var message = '';
                    $.each(errors, function(key, value){
                        message += value[0] + '\n';
                    });
                    toastr.error(message);
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });

    // =========================
    // EDIT PRODUCT
    // =========================
    $(document).on('click', '.edit-product', function(){

        window.editProductId = $(this).data('id');

        $.get('/product/edit/' + window.editProductId, function(response){

            if(response.success){

                window.editSubCategoryId = response.data.sub_category_id;

                $('#productCategoryId').val(response.data.category_id).trigger('change');
                $('#productName').val(response.data.name);
                $('#productPrice').val(response.data.price);
                $('#productCost').val(response.data.cost);
                $('#productStatus').prop('checked', response.data.is_active == 1);

                // Hide create quantity
                $('#createQuantityWrapper').hide();

                // Show stock section
                $('#stockSection').show();
                $('#currentStock').val(response.data.quantity);

                $('#addProductModalTitle').text('Edit Product');

                new bootstrap.Modal(document.getElementById('addProductModal')).show();
            }
        });
    });


    // DELETE PRODUCT
    $(document).on('click', '.delete-product', function () {

    var id = $(this).data('id');
    var _token = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '/product/delete/' + id,
                type: 'POST',
                data: { _token: _token, _method: 'DELETE' },

                success: function (response) {
                    if (response.success) {
                        $('#product-table-body').html(response.html);
                        Swal.fire('Deleted!', 'Product deleted successfully.', 'success');
                    }
                },

                error: function (xhr) {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }

            });

        }

    });

});


    // =========================
    // RESET FORM
    // =========================
    function resetForm(){
        $('#productCategoryId').val('');
        $('#productSubCategoryId').empty().append('<option value="">Select Sub Category</option>');
        $('#productName').val('');
        $('#productPrice').val('');
        $('#productCost').val('');
        $('#productQuantity').val('');
        $('#stockAction').val('');
        $('#stockQuantity').val('');
        $('#productImage').val('');
        $('#productStatus').prop('checked', true);

        $('#createQuantityWrapper').show();
        $('#stockSection').hide();

        $('#addProductModalTitle').text('Add Product');

        window.editProductId = null;
        window.editSubCategoryId = null;
    }

    $('#addProductModal').on('hidden.bs.modal', function(){
        resetForm();
    });

});




</script>
@endsection
