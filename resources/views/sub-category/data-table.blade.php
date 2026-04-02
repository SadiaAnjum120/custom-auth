@if(!empty($subCategories))
@foreach ($subCategories as $key => $subCategory)
<tr>
    <td>{{ $key + 1 }}</td>


    <td>{{ $subCategory->category->name ?? '-' }}</td>


    <td>{{ $subCategory->name ?? '-' }}</td>

    <td>{{ $subCategory->slug ?? '-' }}</td>

    <td>
        <span class="badge text-bg-{{ $subCategory->is_active ? 'success' : 'danger' }}">
            {{ $subCategory->is_active ? 'Active' : 'Inactive' }}
        </span>
    </td>

    <td>{{ $subCategory->created_at ? $subCategory->created_at->format('M, d, Y') : '-' }}</td>

    <td>{{ $subCategory->updated_at ? $subCategory->updated_at->format('M, d, Y') : '-' }}</td>

   <td>
    <div class="dropdown">
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="icon-base ti tabler-dots-vertical"></i>
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item edit-subcategory" href="#" data-id="{{ $subCategory->id }}">
                <i class="icon-base ti tabler-pencil me-1"></i> Edit
            </a>
            <a class="dropdown-item delete-subcategory" href="#" data-id="{{ $subCategory->id }}">
                <i class="icon-base ti tabler-trash me-1"></i> Delete
            </a>
        </div>
    </div>
</td>

</tr>
@endforeach
@endif
