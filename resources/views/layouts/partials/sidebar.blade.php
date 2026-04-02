<aside id="layout-menu" class="layout-menu menu-vertical menu">
<div class="app-brand demo ">
<a href="index.html" class="app-brand-link">
    <span class="app-brand-logo demo">
    <span class="text-primary">
        <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
            fill="currentColor" />
        <path
            opacity="0.06"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
            fill="#161616" />
        <path
            opacity="0.06"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
            fill="#161616" />
        <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
            fill="currentColor" />
        </svg>
    </span>
    </span>
    <span class="app-brand-text demo menu-text fw-bold ms-3">Vuexy</span>
</a>

<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
    <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
    <i class="icon-base ti tabler-x d-block d-xl-none"></i>
</a>
</div>

<div class="menu-inner-shadow"></div>

<<<<<<< HEAD
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
=======
<ul class="menu-inner py-1">
<!-- Dashboards -->
<li class="menu-item active open">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon icon-base ti tabler-smart-home"></i>
    <div data-i18n="Dashboards">Dashboards</div>
    <div class="badge text-bg-danger rounded-pill ms-auto">5</div>
    </a>
    <ul class="menu-sub">
    <li class="menu-item active">
        <a href="index.html" class="menu-link">
        <div data-i18n="Analytics">Analytics</div>
        </a>
    </li>
    <li class="menu-item">
        <a href="dashboards-crm.html" class="menu-link">
        <div data-i18n="CRM">CRM</div>
        </a>
    </li>
    <li class="menu-item">
        <a href="app-ecommerce-dashboard.html" class="menu-link">
        <div data-i18n="eCommerce">eCommerce</div>
        </a>
    </li>
    <li class="menu-item">
        <a href="app-logistics-dashboard.html" class="menu-link">
        <div data-i18n="Logistics">Logistics</div>
        </a>
    </li>
    <li class="menu-item">
        <a href="app-academy-dashboard.html" class="menu-link">
        <div data-i18n="Academy">Academy</div>
        </a>
    </li>
    </ul>
</li>
</aside>
>>>>>>> bc76bce9744c589c4d8f982174347feeaaf5da19
