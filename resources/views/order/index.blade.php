@extends('layouts.app')
@section('title', 'Order')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Order List</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
            <i class="icon-base ti tabler-plus"></i> Create Order
        </button>
    </div>

    <style>
        #order-table{
            width:100% !important;
            min-width:1200px;
        }

        #order-table th,
        #order-table td{
            white-space:nowrap;
            vertical-align:middle;
        }

        .qtyBox{
            display:flex;
            align-items:center;
            gap:6px
        }

        .qtyBox button{
            width:35px;
            height:35px
        }

        .qtyBox input{
            text-align:center;
            width:70px
        }

        .productPreview{
            background:#f6f6f8;
            min-height:55px;
            display:none
        }

      .order-total-box{
    border:1px solid #ddd;
    border-radius:6px;
     background:#ffffff;
    padding:15px;
}

.order-total-title{
    font-weight:600;
    margin-bottom:12px;
}

.total-field{
    background:#fff;
    font-weight:600;
}
    </style>

    <div class="card-datatable table-responsive pt-0">
        <table class="table" id="order-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Order Status</th>
                    <th>Payment Status</th>
                      <th>Due</th>
                    <th>Created By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="order-table-body">
                @include('order.data-table')
            </tbody>
        </table>
    </div>
</div>

<!-- =======================
     ADD / EDIT ORDER MODAL
======================= -->
<div class="modal fade" id="addOrderModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form id="orderForm">
                @csrf
                <input type="hidden" name="order_id" id="orderId">

                <div class="modal-header">
                    <h5 class="modal-title" id="addOrderModalTitle">Add Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Customer + Order Status + Paid -->

             <div class="row mb-3">

    <div class="col-md-12">
        <label>Customer</label>
        <select name="customer_id" id="customer_id" class="form-control">
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">
                    {{ $customer->first_name }} {{ $customer->last_name }}
                </option>
            @endforeach
        </select>
    </div>



</div>


                    <!-- Product Rows -->
                    <div id="productRows">
                        <div class="productRow border rounded p-3 mb-3">
                            <div class="row">






                                <div class="col-md-4">
                                    <label>Category</label>
                                    <select name="category_id[]" id="category_id" class="form-control category">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Sub Category</label>
                                    <select name="sub_category_id[]" id="sub_category_id" class="form-control subCategory">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Product</label>
                                    <select name="product_id[]" id="product_id" class="form-control product">
                                        <option value="">Select Product</option>
                                    </select>
                                </div>
                            </div>
<hr>


                            <!-- Product Table -->
                            <div class="table-responsive productTableWrapper" style="display:none;">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Subcategory</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="productTable">
                                        <tr>
                                            <td>
                                                <img src="" class="productImg rounded" width="40" style="display:none">
                                            </td>
                                            <td class="productName"></td>
                                            <td class="catName"></td>
                                            <td class="subCatName"></td>
                                            <td>
                                                <div class="qtyError alert border bg-white py-1 px-2 mb-2 d-none"></div>
                                                <div class="qtyBox">
                                                    <button type="button" class="btn btn-outline-secondary qtyMinus">-</button>
                                                    <input type="number" name="quantity[]" class="form-control quantity" value="1" min="1">
                                                    <button type="button" class="btn btn-outline-secondary qtyPlus">+</button>
                                                </div>
                                            </div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control price" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control subtotal" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger removeRow">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm mb-3" id="addRow">
                        + Add Another Product
                    </button>

                   <!-- =========================
     ORDER INFORMATION CARD
========================= -->
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">Order Information</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Order Number</label>
                <input type="text" name="order_number" id="orderNumber" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Order Date</label>
                <input type="date" id="order_date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Order Status</label>
                <select name="order_status" id="order_status" class="form-select">
                 <option value="created">Created</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- =========================
     ORDER TOTALS CARD
========================= -->
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">Order Totals</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tax</label>
                <input type="number"  name="tax" id="taxAmount" class="form-control" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Discount</label>
                <input type="number"  name="discount" id="discountAmount" class="form-control" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="totalAmount" class="form-control fw-bold" readonly>
            </div>
               <div class="col-md-4">
        <label>Paid Amount</label>
        <input type="number" name="paid_amount" id="paidAmount" class="form-control" value="0" min="0">
    </div>
            <div class="col-md-4">
                <label class="form-label">Due</label>
                <input type="text" id="dueAmount" class="form-control fw-bold" readonly>
            </div>
        </div>
    </div>
</div>

<!-- =========================
     ADDITIONAL INFORMATION CARD
========================= -->
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">Additional Information</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Enter order notes here..."></textarea>
            </div>
        </div>
    </div>
</div>
</div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveOrderButton" class="btn btn-primary">Save Order</button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){

    let table = $('#order-table').DataTable({ responsive:false, autoWidth:false });
    window.editOrderId = null;

    function reloadTable(html){
        $('#order-table-body').html(html);
        table.destroy();
        table = $('#order-table').DataTable({ responsive:false, autoWidth:false });
    }

    function calculateSummary(){

    let subtotal = 0;

    $('.subtotal').each(function(){
        subtotal += parseFloat($(this).val()) || 0;
    });

    let tax = parseFloat($('#taxAmount').val()) || 0;
    let discount = parseFloat($('#discountAmount').val()) || 0;
    let paid = parseFloat($('#paidAmount').val()) || 0;

    let total = subtotal + tax - discount;

    if(total < 0){
        total = 0;
    }

    let due = total - paid;

    if(due < 0){
        due = 0;
    }

    $('#totalAmount').val(total.toFixed(2));
    $('#dueAmount').val(due.toFixed(2));
}

    // =========================
    // Add Row
    // =========================
 $('#addRow').click(function(){

    let row = $('.productRow:first').clone(false, false);

    // reset dropdowns like product module
    row.find('.category').val('');

    row.find('.subCategory')
        .empty()
        .append('<option value="">Select Sub Category</option>');

    row.find('.product')
        .empty()
        .append('<option value="">Select Product</option>');

    // reset UI text
    row.find('.catName').text('');
    row.find('.subCatName').text('');
    row.find('.productName').text('');

    row.find('input').not('.quantity').val('');
    row.find('.quantity').val(1);

    row.find('.productTableWrapper').hide();

    $('#productRows').append(row);
});

    // Remove Row
    $(document).on('click','.removeRow',function(){
        if($('.productRow').length>1){
            $(this).closest('.productRow').remove();
            calculateSummary();
        }
    });

    // Category Change
$(document).on('change', '.category', function(){

    let row = $(this).closest('.productRow');
    let categoryId = $(this).val();

    let subDropdown = row.find('.subCategory');
    let productDropdown = row.find('.product');

    // ✅ RESET both dropdowns (IMPORTANT)
    subDropdown.empty().append('<option value="">Select Sub Category</option>');
    productDropdown.empty().append('<option value="">Select Product</option>');

    // update UI
    row.find('.catName').text($(this).find('option:selected').text());
    row.find('.subCatName').text('');
    row.find('.productName').text('');

    // ❗ Stop if no category
    if(!categoryId) return;

    // Load subcategories
    $.get('/sub-categories/' + categoryId, function(data){

        data.forEach(sub => {
            subDropdown.append(`<option value="${sub.id}">${sub.name}</option>`);
        });

    });
});

$(document).on('change', '.subCategory', function(){

    let row = $(this).closest('.productRow');
    let subId = $(this).val();

    let productDropdown = row.find('.product');

    // ✅ Reset product
    productDropdown.empty().append('<option value="">Select Product</option>');

    row.find('.subCatName').text($(this).find('option:selected').text());
    row.find('.productName').text('');

    if(!subId) return;

    $.get('/products/' + subId, function(data){

        data.forEach(item => {
            productDropdown.append(`<option value="${item.id}"
                data-image="${item.image}"
                data-stock="${item.quantity}"
                data-price="${item.price}">
                ${item.name}
            </option>`);
        });

    });
});
   $(document).on('change', '.product', function () {

    let row = $(this).closest('.productRow');
    let productId = $(this).val();

    if(!productId) return;

    // 🔥 Professional duplicate handling
    let existingRow = null;

    $('.product').not(this).each(function () {
        if ($(this).val() === productId) {
            existingRow = $(this).closest('.productRow');
        }
    });

    if (existingRow) {

        // 👉 Instead of alert, merge quantity
        let qtyField = existingRow.find('.quantity');
        let currentQty = parseInt(qtyField.val()) || 1;

        qtyField.val(currentQty + 1).trigger('change');

        // ❗ remove duplicate row
        row.remove();

        calculateSummary();

        return;
    }

    // ✅ Normal flow
    let selected = $('option:selected', this);

    let name = selected.text();
    let image = selected.data('image');
    let stock = selected.data('stock');
    let price = selected.data('price');

    if (!price) return;

    row.find('.productTableWrapper').show();
    row.find('.productName').text(name);
    row.find('.productImg').attr('src', image).show();
    row.find('.price').val(price);

    let qty = row.find('.quantity');
    qty.attr('min', 1);
    qty.attr('max', stock);

    row.find('.subtotal').val(price * qty.val());

    calculateSummary();
});

    // Quantity Plus/Minus/Change
function updateSubtotal(row){
    let qty=parseInt(row.find('.quantity').val())||1;
    let price=parseFloat(row.find('.price').val())||0;
    row.find('.subtotal').val(qty*price);
    calculateSummary();
}

// Show inline error (Amazon style)
function showInlineError(row, msg){
    let box = row.find('.qtyError');
    box.removeClass('d-none').text(msg);
}

// Hide error
function hideInlineError(row){
    row.find('.qtyError').addClass('d-none').text('');
}

$(document).on('click','.qtyPlus',function(){
    let row=$(this).closest('.productRow');
    let qty=row.find('.quantity');
    let current=parseInt(qty.val())||1;
    let max=parseInt(qty.attr('max'))||9999;

    if(current >= max){
        showInlineError(row, 'You cannot add more than ' + max);
        row.find('.qtyPlus').prop('disabled', true);
        return;
    }

    qty.val(current+1);

    hideInlineError(row);
    row.find('.qtyMinus').prop('disabled', false);

    updateSubtotal(row);
});

$(document).on('click','.qtyMinus',function(){
    let row=$(this).closest('.productRow');
    let qty=row.find('.quantity');
    let current=parseInt(qty.val())||1;
    let min=parseInt(qty.attr('min'))||1;

    if(current <= min){
        row.find('.qtyMinus').prop('disabled', true);
        return;
    }

    qty.val(current-1);

    hideInlineError(row);
    row.find('.qtyPlus').prop('disabled', false);

    updateSubtotal(row);
});

$(document).on('keyup change','.quantity',function(){
    let row=$(this).closest('.productRow');
    let qty=$(this);
    let value=parseInt(qty.val())||0;
    let min=parseInt(qty.attr('min'))||1;
    let max=parseInt(qty.attr('max'))||9999;

    if(value < min){
        value = min;
        showInlineError(row, 'Minimum quantity is ' + min);
    }
    else if(value > max){
        value = max;
        showInlineError(row, 'You cannot add more than ' + max);
    }
    else{
        hideInlineError(row);
    }

    qty.val(value);

    // Button control
    row.find('.qtyMinus').prop('disabled', value <= min);
    row.find('.qtyPlus').prop('disabled', value >= max);

    updateSubtotal(row);
});
    // Paid amount change

        $('#paidAmount, #taxAmount, #discountAmount').on('keyup change', function(){
    calculateSummary();
});
$('#addOrderModal').on('show.bs.modal', function () {
  if (window.editOrderId) return;
    function randomString(length) {
        let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    let today = new Date();
    let date = today.getFullYear()
        + String(today.getMonth()+1).padStart(2,'0')
        + String(today.getDate()).padStart(2,'0');

    let orderNumber = 'ORD-' + date + '-' + randomString(5);

    $('#orderNumber').val(orderNumber);
});

    // Save Order
    $('#saveOrderButton').off('click').on('click', function(e){
        e.preventDefault();
        var formData = $('#orderForm').serialize();
        var url = window.editOrderId ? '/orders/update/' + window.editOrderId : '/orders/store';
        if(window.editOrderId){ formData += '&_method=PUT'; }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response){
                if(response.success){
                    $('#addOrderModal').modal('hide');
                    resetOrderForm();
                    $('#order-table-body').html(response.html);
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

    // Edit Order
$(document).on('click', '.edit-order', function () {

    window.editOrderId = $(this).data('id');

    $.get('/orders/edit/' + window.editOrderId, function (response) {

        if (!response.success) {
            toastr.error('Failed to fetch order data!');
            return;
        }

        let order = response.order || {};
        let orderItems = response.orderItems || [];

        // ====== MAIN ORDER DATA ======
        $('#orderId').val(order.id || '');
        $('#orderNumber').val(order.order_number || '');
        $('#order_date').val(order.order_date ? order.order_date.split(' ')[0] : '');
        $('#customer_id').val(order.customer_id || '');
        $('#paidAmount').val(order.paid_amount || 0);
        $('#taxAmount').val(order.tax || 0);
        $('#discountAmount').val(order.discount || 0);
        $('#order_status').val(order.order_status || 'created');
        $('textarea[name="notes"]').val(order.notes || '');

        // ====== RESET ALL PRODUCT ROWS ======
        $('.productRow:not(:first)').remove();
        let firstRow = $('.productRow:first');

        firstRow.find('.category').val('');
        firstRow.find('.subCategory').html('<option value="">Select Sub Category</option>');
        firstRow.find('.product').html('<option value="">Select Product</option>');
        firstRow.find('.catName').text('');
        firstRow.find('.subCatName').text('');
        firstRow.find('.productName').text('');
        firstRow.find('.productImg').attr('src', '').hide();
        firstRow.find('.price').val('');
        firstRow.find('.subtotal').val('');
        firstRow.find('.quantity').val(1);
        firstRow.find('.productTableWrapper').hide();

        // ====== POPULATE SINGLE ROW ======
        function populateRow(row, item) {
            return new Promise((resolve) => {

                // CATEGORY
                row.find('.category').val(item.category_id);
                row.find('.catName').text(row.find('.category option:selected').text());

                // LOAD SUBCATEGORIES
                $.get('/sub-categories/' + item.category_id, function (subs) {
                    let subDropdown = row.find('.subCategory');
                    subDropdown.html('<option value="">Select Sub Category</option>');
                    subs.forEach(sub => subDropdown.append(`<option value="${sub.id}">${sub.name}</option>`));
                    subDropdown.val(item.sub_category_id);
                    row.find('.subCatName').text(subDropdown.find('option:selected').text());

                    // LOAD PRODUCTS
                    $.get('/products/' + item.sub_category_id + '?order_id=' + window.editOrderId, function (products) {
                        let productDropdown = row.find('.product');
                        productDropdown.html('<option value="">Select Product</option>');

                        products.forEach(p => {
                            productDropdown.append(`
                                <option value="${p.id}"
                                    data-image="${p.image || ''}"
                                    data-stock="${p.quantity || 0}"
                                    data-price="${p.price || 0}">
                                    ${p.name}
                                </option>
                            `);
                        });

                        productDropdown.val(item.product_id);

                        let selected = productDropdown.find('option:selected');
                        let name = selected.text();
                        let image = selected.data('image') || '';
                        let price = parseFloat(selected.data('price')) || 0;
                        let stock = parseInt(selected.data('stock')) || 0;
                        let qtyInOrder = parseInt(item.quantity) || 1;

                        // 🔹 MAX quantity = stock + already in order
                        let maxQty = stock + qtyInOrder;
                        row.find('.quantity').attr('max', maxQty);

                        // UI UPDATE
                        row.find('.productTableWrapper').show();
                        row.find('.productName').text(name);
                        if (image) row.find('.productImg').attr('src', image).show(); else row.find('.productImg').hide();
                        row.find('.price').val(price.toFixed(2));
                        row.find('.quantity').val(qtyInOrder);
                        row.find('.subtotal').val((price * qtyInOrder).toFixed(2));

                        resolve();
                    });
                });
            });
        }

        // ====== POPULATE ALL ROWS ======
        async function populateAllRows(items) {
            for (let i = 0; i < items.length; i++) {
                let row = (i === 0) ? $('.productRow').eq(0) : ($('#addRow').trigger('click'), $('.productRow').eq(i));
                await populateRow(row, items[i]);
            }
        }

        populateAllRows(orderItems).then(() => {
            calculateSummary();
            $('#addOrderModalTitle').text('Edit Order');
            $('#addOrderModal').modal('show');
        });

    });
});

// ====== FRIENDLY MAX QUANTITY VALIDATION ======
$(document).on('input', '.quantity', function () {
    let val = parseInt($(this).val());
    let max = parseInt($(this).attr('max'));
    if (val > max) {
         showInlineError(row, `You can add only ${max} quantity based on current stock`);
        $(this).val(max); // auto-adjust value
    } else {
        // Hide inline error if quantity is within limit
        row.find('.qtyError').addClass('d-none').text('');
    }

});
    // Delete Order
    $(document).on('click','.delete-order',function(){
        var id = $(this).data('id');
        var _token = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes delete it!'
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url:'/orders/delete/' + id,
                    type:'POST',
                    data:{ _token:_token, _method:'DELETE' },
                    success:function(response){
                        if(response.success){
                            $('#order-table-body').html(response.html);
                            Swal.fire('Deleted!','Order deleted successfully.','success');
                        }
                    },
                    error:function(){
                        Swal.fire('Error!','Something went wrong.','error');
                    }
                });
            }
        });
    });

    // Reset Form
    function resetOrderForm(){
        $('#orderForm')[0].reset();
        $('#orderId').val('');
        $('#addOrderModalTitle').text('Add Order');
        window.editOrderId = null;
        $('.productRow:not(:first)').remove();
        $('.productRow:first .productTableWrapper').hide();
        calculateSummary();
    }

    // Reset on Modal Close
    $('#addOrderModal').on('hidden.bs.modal', function(){
        resetOrderForm();
    });

});
</script>
@endsection
