@if(!empty($products))
    @foreach ($products as $key => $product)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
    @if($product->image)
        <img src="{{ $product->image }}"
             class="rounded-circle product-img"
             width="50" height="50">
    @else
        <span>No Image</span>
    @endif
</td>
            <td>{{ $product->category->name ?? '-' }}</td>

            <td>{{ $product->subCategory->name ?? '-' }}</td>


            <td>{{ $product->name ?? '-' }}</td>


            <td>{{ $product->sku ?? '-' }}</td>


            <td>{{ $product->price ?? '-' }}</td>


            <td>{{ $product->quantity ?? '-' }}</td>


            <td>
                <span class="badge text-bg-{{ $product->is_active ? 'success' : 'danger' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>


            <td>{{ $product->created_at ? $product->created_at->format('M, d, Y') : '-' }}</td>


            <td>{{ $product->updated_at ? $product->updated_at->format('M, d, Y') : '-' }}</td>




            <td>
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item edit-product" href="#" data-id="{{ $product->id }}">
                            <i class="icon-base ti tabler-pencil me-1"></i> Edit
                        </a>
                        <a class="dropdown-item delete-product" href="#" data-id="{{ $product->id }}">
                            <i class="icon-base ti tabler-trash me-1"></i> Delete
                        </a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@endif
