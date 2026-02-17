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
    #product-table td {
        white-space: nowrap;
    }


    #product-table th:nth-child(9),
    #product-table td:nth-child(9),
    #product-table th:nth-child(10),
    #product-table td:nth-child(10) {
        min-width: 170px;
    }
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
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Sub Category</label>
                        <select id="productSubCategoryId" class="form-control">
                            <option value="">Select Sub Category</option>
                            @foreach($subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}">
                                    {{ $subCategory->name }}
                                </option>
                            @endforeach
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="productQuantity" class="form-control">
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

    $('#product-table').DataTable();


    // SAVE / CREATE / UPDATE

    $('#saveProductButton').off('click').on('click', function(e){

        e.preventDefault();

        var category_id = $('#productCategoryId').val();
        var sub_category_id = $('#productSubCategoryId').val();
        var name        = $('#productName').val();
        var price       = $('#productPrice').val();
        var cost        = $('#productCost').val();
        var quantity    = $('#productQuantity').val();
        var is_active   = $('#productStatus').is(':checked') ? 1 : 0;
        var imageFile   = $('#productImage')[0].files[0];
        var _token      = $('meta[name="csrf-token"]').attr('content');

        var url  = window.editProductId
                    ? '/product/update/' + window.editProductId
                    : '{{ route("product.store") }}';

        var formData = new FormData();
        formData.append('category_id', category_id);
        formData.append('sub_category_id', sub_category_id);
        formData.append('name', name);
        formData.append('price', price);
        formData.append('cost', cost);
        formData.append('quantity', quantity);
        formData.append('is_active', is_active);
        formData.append('_token', _token);

        if(imageFile){
            formData.append('image', imageFile);
        }

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
                    toastr.success(response.message || 'Product saved successfully!');
                }
            },
            error: function(xhr){
                // Laravel validation errors
                if(xhr.status === 422){
                    var errors = xhr.responseJSON.errors;
                    var message = '';
                    $.each(errors, function(key, value){
                        message += value[0] + '\n';
                    });
                    toastr.error(message);
                } else {
                    toastr.error('Something went wrong!');
                    console.log(xhr.responseText);
                }
            }
        });

    });

    //  EDIT
    $(document).on('click', '.edit-product', function(){
        window.editProductId = $(this).data('id');

        $.ajax({
            url: '/product/edit/' + window.editProductId,
            type: 'GET',
            success: function(response){
                if(response.success){
                    $('#productCategoryId').val(response.data.category_id);
                    $('#productSubCategoryId').val(response.data.sub_category_id);
                    $('#productName').val(response.data.name);
                    // SKU removed, handled in controller
                    $('#productPrice').val(response.data.price);
                    $('#productCost').val(response.data.cost);
                    $('#productQuantity').val(response.data.quantity);
                    $('#productStatus').prop('checked', response.data.is_active == 1);
                    $('#addProductModalTitle').text('Edit Product');
                    new bootstrap.Modal(document.getElementById('addProductModal')).show();
                }
            },
            error: function(xhr){
                toastr.error('Failed to fetch product data!');
                console.log(xhr.responseText);
            }
        });
    });

    //  DELETE
    $(document).on('click', '.delete-product', function(){

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
            if(result.isConfirmed){

                $.ajax({
                    url: '/product/delete/' + id,
                    type: 'POST',
                    data: {_token: _token, _method: 'DELETE'},
                    success: function(response){
                        if(response.success){
                            $('#product-table-body').html(response.html);
                             // SweetAlert Success
                            Swal.fire(
                                'Deleted!',
                                'Product deleted successfully.',
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

    // RESET FORM FUNCTION

    function resetForm(){
        $('#productCategoryId').val('');
        $('#productSubCategoryId').val('');
        $('#productName').val('');
        $('#productPrice').val('');
        $('#productCost').val('');
        $('#productQuantity').val('');
        $('#productImage').val('');
        $('#productStatus').prop('checked', true);
        $('#addProductModalTitle').text('Add Product');
        window.editProductId = null;
    }

});
</script>
@endsection
