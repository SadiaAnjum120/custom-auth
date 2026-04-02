@forelse($shops as $index => $shop)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $shop->name }}</td>
                            <td>{{ $shop->email }}</td>
                            <td>{{ $shop->shop_name }}</td>

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

                            <td>
                                <form >
                                    @csrf


    <div class="d-flex gap-1">
        <button class="btn btn-success btn-sm action-btn" data-id="{{ $shop->id }}" data-action="approve">Approve</button>
        <button class="btn btn-danger btn-sm action-btn" data-id="{{ $shop->id }}" data-action="reject">Reject</button>
        <button class="btn btn-secondary btn-sm action-btn" data-id="{{ $shop->id }}" data-action="suspend">Suspend</button>
    </div>
    </form>
</td>

                    @empty

                    @endforelse
