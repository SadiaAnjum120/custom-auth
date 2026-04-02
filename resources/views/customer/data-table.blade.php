@if(!empty($customers) && count($customers) > 0)
    @foreach ($customers as $key => $customer)
    <tr>
        <td>{{ $key + 1 }}</td>

        <td>{{ $customer->first_name ?? '-' }}</td>

        <td>{{ $customer->last_name ?? '-' }}</td>

        <td>{{ $customer->phone ?? '-' }}</td>

        <td>{{ $customer->email ?? '-' }}</td>
        <td>
        <span class="badge text-bg-{{ $customer->is_active ? 'success' : 'danger' }}">
            {{ $customer->is_active ? 'Active' : 'Inactive' }}
        </span>
    </td>
        <td>
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                </button>

                <div class="dropdown-menu">

                    <!-- Edit -->
                    <a class="dropdown-item edit-customer" href="#" data-id="{{ $customer->id }}">
                        <i class="icon-base ti tabler-pencil me-1"></i> Edit
                    </a>

                    <!-- Delete -->
                    <a class="dropdown-item delete-customer" href="#" data-id="{{ $customer->id }}">
                        <i class="icon-base ti tabler-trash me-1"></i> Delete
                    </a>

                </div>
            </div>
        </td>
    </tr>
    @endforeach
@else


    </tr>
@endif
