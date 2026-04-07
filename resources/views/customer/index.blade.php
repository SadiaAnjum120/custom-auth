@extends('layouts.app')
@section('title', 'Customer ')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Customer List</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="icon-base ti tabler-plus"></i> Create Customer
        </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table" id="customer-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="customer-table-body">
                @include('customer.data-table')
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" id="firstName" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" id="lastName" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" id="phoneNumber" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" class="form-control">
                </div>
           <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="customerStatus" checked />
                    <label class="form-check-label" for="customerStatus">Active</label>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCustomerButton">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    window.editCustomerId = null;

    $('#customer-table').DataTable();

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };

    // SAVE / UPDATE CUSTOMER
    $('#saveCustomerButton').on('click', function(e) {
        e.preventDefault();

        let first_name = $('#firstName').val();
        let last_name = $('#lastName').val();
        let phone = $('#phoneNumber').val();
        let email = $('#email').val();
          var is_active = $('#customerStatus').is(':checked') ? 1 : 0;
        let _token = $('meta[name="csrf-token"]').attr('content');

        let url = window.editCustomerId
            ? '/customers/update/' + window.editCustomerId
            : '{{ route("customer.store") }}';

        let type = window.editCustomerId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: {
                first_name: first_name,
                last_name: last_name,
                phone: phone,
                email: email,
                is_active: is_active,

                _token: _token
            },
            success: function(response) {

                if(response.success){

                    $('#addCustomerModal').modal('hide');

                    $('#firstName, #lastName, #phoneNumber, #email').val('');
                    $('#customerStatus').prop('checked', true);
                    $('#customerModalTitle').text('Add Customer');

                    window.editCustomerId = null;

                    $('#customer-table-body').html(response.html);

                    toastr.success(response.message);
                }
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    let message = '';
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

    // DELETE CUSTOMER
    $(document).on('click', '.delete-customer', function() {

        let id = $(this).data('id');
        let _token = $('meta[name="csrf-token"]').attr('content');

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
                    url: '/customers/destroy/' + id,
                    type: 'DELETE',
                    data: { _token: _token },
                    success: function(response) {

                        if(response.success){

                            $('#customer-table-body').html(response.html);

                            Swal.fire(
                                'Deleted!',
                                'Customer deleted successfully.',
                                'success'
                            );
                        }
                    }
                });
            }
        });
    });

    // EDIT CUSTOMER
    $(document).on('click', '.edit-customer', function() {

        window.editCustomerId = $(this).data('id');

        $.ajax({
            url: '/customers/edit/' + window.editCustomerId,
            type: 'GET',
            success: function(response){

                if(response.success){

                    $('#firstName').val(response.data.first_name);
                    $('#lastName').val(response.data.last_name);
                    $('#phoneNumber').val(response.data.phone);
                    $('#email').val(response.data.email);
                     $('#customerStatus').prop('checked', response.data.is_active == 1);

                    $('#customerModalTitle').text('Edit Customer');

                    new bootstrap.Modal(
                        document.getElementById('addCustomerModal')
                    ).show();
                }
            }
        });
    });

    // RESET MODAL
    $('#addCustomerModal').on('hidden.bs.modal', function () {
        $('#firstName, #lastName, #phoneNumber, #email').val('');
        $('#customerModalTitle').text('Add Customer');
        window.editCustomerId = null;
    });

});
</script>
@endsection
