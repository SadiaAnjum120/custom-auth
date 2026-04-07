@forelse($shops as $index => $shop)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $shop->name }}</td>
    <td>{{ $shop->email }}</td>
    <td>{{ $shop->shop_name }}</td>

    {{-- ================= STATUS BADGE ================= --}}
    <td>
        @if($shop->approval_status == 'pending')
            <span class="badge bg-warning text-dark">Pending</span>

        @elseif($shop->approval_status == 'approved')
            <span class="badge bg-success">Approved</span>

        @elseif($shop->approval_status == 'rejected')
            <span class="badge bg-danger">Rejected</span>

        @elseif($shop->approval_status == 'suspended')
            <span class="badge bg-secondary">Suspended</span>
        @endif
    </td>


    {{-- ================= IMPERSONATE ================= --}}
    <td>
        @if($shop->approval_status == 'approved')
            <button onclick="confirmImpersonate({{ $shop->id }})"
                class="btn btn-warning btn-sm">
                Impersonate
            </button>
        @else
            <button class="btn btn-secondary btn-sm" disabled>
                Not Allowed
            </button>
        @endif
    </td>


    {{-- ================= ACTION BUTTONS ================= --}}
    <td>

        {{-- PENDING --}}
        @if($shop->approval_status == 'pending')

            <button class="btn btn-success btn-sm action-btn"
                data-id="{{ $shop->id }}"
                data-action="approve">
                Approve
            </button>

            <button class="btn btn-danger btn-sm action-btn"
                data-id="{{ $shop->id }}"
                data-action="reject">
                Reject
            </button>

        @endif


        {{-- APPROVED --}}
        @if($shop->approval_status == 'approved')

            <button class="btn btn-warning btn-sm action-btn"
                data-id="{{ $shop->id }}"
                data-action="suspend">
                Suspend
            </button>

        @endif


        {{-- REJECTED --}}
        @if($shop->approval_status == 'rejected')

            <button class="btn btn-success btn-sm action-btn"
                data-id="{{ $shop->id }}"
                data-action="approve">
                Approve
            </button>

            <button class="btn btn-warning btn-sm action-btn"
                data-id="{{ $shop->id }}"
                data-action="suspend">
                Suspend
            </button>

        @endif


        {{-- SUSPENDED --}}
        @if($shop->approval_status == 'suspended')

            <button class="btn btn-success btn-sm action-btn"
                data-id="{{ $shop->id }}"
                data-action="approve">
                Approve
            </button>

        @endif

    </td>

</tr>

@empty
<tr>
    <td colspan="7" class="text-center">No Shops Found</td>
</tr>
@endforelse
