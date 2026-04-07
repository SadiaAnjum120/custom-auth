<aside id="layout-menu" class="layout-menu menu-vertical menu">
<div class="app-brand demo ">
<a href="index.html" class="app-brand-link">
    <span class="app-brand-logo demo d-flex align-items-center justify-content-center flex-shrink-0">
    <span class="text-primary d-flex align-items-center justify-content-center">

        <svg class="w-100 h-100"
            style="max-width:39px;"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg">

            <!-- Cart Body -->
            <path
                d="M2 3H6L8 13H19.5L21.5 7H9"
                stroke="currentColor"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            <!-- Wheels -->
            <circle cx="10" cy="19" r="2.6" fill="currentColor"/>
            <circle cx="18" cy="19" r="2.6" fill="currentColor"/>

        </svg>

    </span>
</span>
    <span class="app-brand-text demo menu-text fw-bold ms-3">Shop Cart</span>
</a>


<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
    <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
    <i class="icon-base ti tabler-x d-block d-xl-none"></i>
</a>
</div>



<div class="menu-inner-shadow"></div>

       <ul class="menu-inner py-1">

    <!-- Dashboards (Optional, sabko dikhe) -->
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active open' : '' }}">
        <a href="#" class="menu-link">
            <i class="menu-icon icon-base ti tabler-smart-home"></i>
            <div>Dashboards</div>
        </a>
    </li>

    <!-- eCommerce Section -->
   <li class="menu-item
    <!-- eCommerce Section (Super Admin only) -->
@if(auth()->user()->role === 'super_admin')
<li class="menu-item {{ request()->routeIs('admin.shops.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ti tabler-shopping-cart"></i>
        <div>eCommerce</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.shops.*') ? 'active' : '' }}">
            <a href="{{ route('admin.shops.index') }}" class="menu-link">
                <div>Shops List</div>
            </a>
        </li>
    </ul>
</li>
@endif

        @if(auth()->user()->role === 'shop_admin')

            <!-- Catalog Section -->
<li class="menu-item {{ request()->routeIs('category.*') || request()->routeIs('subcategory.*') || request()->routeIs('product.*') ? 'active open' : '' }}">

    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ti tabler-box"></i>
        <div>Catalog</div>
    </a>

    <ul class="menu-sub"
        style="{{ request()->routeIs('category.*') || request()->routeIs('subcategory.*') || request()->routeIs('product.*') ? 'display:block;' : '' }}">

        <li class="menu-item {{ request()->routeIs('category.*') ? 'active' : '' }}">
            <a href="{{ route('category.index') }}" class="menu-link">
                <div>Category</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('subcategory.*') ? 'active' : '' }}">
            <a href="{{ route('subcategory.index') }}" class="menu-link">
                <div>Sub Category</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('product.*') ? 'active' : '' }}">
            <a href="{{ route('product.index') }}" class="menu-link">
                <div>Product</div>
            </a>
        </li>

    </ul>
</li>
<!-- Customer Management -->
<li class="menu-item {{ request()->routeIs('customer.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ti tabler-users"></i>
        <div>Customer Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <a href="{{ route('customer.index') }}" class="menu-link">
                <div>Customer</div>
            </a>
        </li>
    </ul>
</li>

<!-- Order Management -->
<li class="menu-item {{ request()->routeIs('orders.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ti tabler-receipt"></i>
        <div>Order Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <a href="{{ route('orders.index') }}" class="menu-link">
                <div>Orders</div>
            </a>
        </li>
    </ul>
</li>
    @endif
        </aside>


