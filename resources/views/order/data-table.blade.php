
@if(!empty($orders))
@foreach ($orders as $key => $order)

@foreach ($order->orderItems as $item)
<tr>
<td>{{ $key + 1 }}</td>

<td>
{{ $order->customer ? $order->customer->first_name.' '.$order->customer->last_name : 'N/A' }}
</td>



<td>{{ $item->product->name ?? '-' }}</td>

<td>{{ $item->quantity ?? '-' }}</td>

<td>
<span class="badge text-bg-{{
$order->order_status == 'completed' ? 'success' :
($order->order_status == 'cancelled' ? 'danger' : 'warning')
}}">
{{ ucfirst($order->order_status) }}
</span>
</td>


<td>
<span class="badge text-bg-{{
$order->payment_status == 'paid' ? 'success' :
($order->payment_status == 'failed' ? 'danger' : 'warning')
}}">
{{ ucfirst($order->payment_status) }}
</span>
</td>
<td>
    <span class="badge text-bg-{{ $order->due_amount > 0 ? 'danger' : 'success' }}">
        {{ number_format($order->due_amount ?? 0, 2) }}
    </span>
</td>

<td>{{ $order->user->name ?? '-' }}</td>

<td>

<div class="dropdown">

<button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
   <i class="icon-base ti tabler-dots-vertical"></i>
</button>

<div class="dropdown-menu">

<a class="dropdown-item edit-order {{ $order->order_status == 'completed' ? 'disabled text-muted' : '' }}"
href="#"
data-id="{{ $order->id }}">
<i>

 <i class="icon-base ti tabler-pencil me-1"></i> Edit
</a>


<a class="dropdown-item delete-order {{ $order->order_status == 'completed' ? 'disabled text-muted' : '' }}"
href="#"
data-id="{{ $order->id }}">

  <i class="icon-base ti tabler-trash me-1"></i> Delete
</a>

</div>

</div>

</td>

</tr>
@endforeach

@endforeach
@endif
