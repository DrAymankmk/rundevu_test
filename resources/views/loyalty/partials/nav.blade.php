<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('loyalty.dashboard') ? 'active' : '' }}"
           href="{{ route('loyalty.dashboard') }}">@lang('admin.dashboard')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('loyalty.coupons') ? 'active' : '' }}"
           href="{{ route('loyalty.coupons') }}">@lang('main.loyalty_coupons')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('loyalty.redemptions') ? 'active' : '' }}"
           href="{{ route('loyalty.redemptions') }}">@lang('main.loyalty_redemptions')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('loyalty.transactions') ? 'active' : '' }}"
           href="{{ route('loyalty.transactions') }}">@lang('main.loyalty_transactions')</a>
    </li>
</ul>
