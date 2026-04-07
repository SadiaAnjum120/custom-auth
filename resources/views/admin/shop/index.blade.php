
@extends('layouts.app')
@section('title', 'Shop List')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">All Shops</h5>

    </div>

    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table" id="shop-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Owner Name </th>
                    <th>Email</th>
                    <th>Shop Name</th>
                    <th>Status</th>
                    <th>impersonate</th>
                    <th>Action</th>

                </tr>
            </thead>
           <tbody id="shop-table-body">
                @include('admin.shop.data-table')
            </tbody>

          </table>
   </div>

</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {

    // Initialize DataTable once
    var shopTable = $('#shop-table').DataTable({
        responsive: true
    });

    // Handle approve/reject/suspend click
    $(document).on('click', '.action-btn', function(e){
        e.preventDefault(); // stop page reload

        var id = $(this).data('id');
        var action = $(this).data('action');
        var _token = $('meta[name="csrf-token"]').attr('content');
        var button = $(this);

        $.ajax({
          url: '/admin/shops/' + action + '/' + id,
            type: 'POST',
            data: {_token: _token},
            success: function(response){
                if(response.success){
                    toastr.success(response.message);

                   // destroy and rebuild datatable
        shopTable.destroy();

        $('#shop-table-body')
            .load(location.href + ' #shop-table-body>*', function () {

                shopTable = $('#shop-table').DataTable({
                    responsive: true
                });
                  });

                    // Safely update status badge
                    var row = button.closest('tr');
                    var badge = row.find('td:nth-child(5) span');

                    // Keep the original 'badge' class intact
                    badge.removeClass('bg-success bg-danger bg-warning bg-secondary text-dark')
                         .addClass(response.badge_class)
                         .text(response.status_text);
                }
            },
            error: function(xhr){
    console.log(xhr.responseText); // server ka exact error
    toastr.error('Action failed!');
}
        });
    });

});
function confirmImpersonate(shopId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to impersonate this shop!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#696cff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Impersonate!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/admin/impersonate/' + shopId;
        }
    });
}


</script>
@endsection

