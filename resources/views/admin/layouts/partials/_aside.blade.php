@php
    use Illuminate\Support\Facades\Route as R;
    $r = function(string $name, string $fallback){
        return R::has($name) ? route($name) : url($fallback);
    };
    $authUser = auth()->user();
    $authProperty = $authUser?->property;
    $roleBadge = $authUser?->roles?->pluck('name')->take(2)->implode(', ');
    $propertyUnitCount = $authProperty ? $authProperty->units()->count() : null;
    $propertyFloorCount = $authProperty ? $authProperty->floors()->count() : null;
@endphp

<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
    <div class="px-6 pt-6">
        <div class="rounded border border-dashed border-primary bg-light-primary bg-opacity-25 p-5 position-relative overflow-hidden">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px symbol-light-primary me-4">
                    <span class="symbol-label">
                        <i class="la la-user-tie text-primary fs-3"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold text-dark fs-6 mb-1">{{ $authUser?->name }}</div>
                    <div class="text-muted fs-8">
                        {{ $roleBadge ?: __('users.scope_system_short') }}
                    </div>
                </div>
            </div>
            <div class="separator separator-dashed my-4"></div>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="svg-icon svg-icon-2 text-primary me-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M12 2L3 7V9H21V7L12 2Z" fill="currentColor"/>
                                <path d="M19 10H5V21H19V10Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <div>
                            <div class="fw-semibold text-dark">{{ $authProperty?->name ?? __('users.scope_system_short') }}</div>
                            <div class="text-muted fs-9">{{ $authProperty?->full_address ?? __('users.scope_system') }}</div>
                        </div>
                    </div>
                    @if($authProperty && R::has('admin.properties.show'))
                        <a href="{{ route('admin.properties.show', $authProperty) }}" class="btn btn-sm btn-light-primary">
                            {{ __('messages.show') }}
                        </a>
                    @endif
                </div>
                @if($authProperty)
                    <div class="d-flex gap-4">
                        <div class="bg-white bg-opacity-75 rounded px-3 py-2 text-center flex-grow-1">
                            <div class="fw-bold fs-5 text-primary">{{ number_format((int) $propertyUnitCount) }}</div>
                            <div class="text-muted fs-9">{{ __('units.title') }}</div>
                        </div>
                        <div class="bg-white bg-opacity-75 rounded px-3 py-2 text-center flex-grow-1">
                            <div class="fw-bold fs-5 text-primary">{{ number_format((int) $propertyFloorCount) }}</div>
                            <div class="text-muted fs-9">{{ __('floors.title') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="kt_aside_menu" class="aside-menu my-6" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
        <ul class="menu-nav">

            @can('dashboard.view')
            <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                <a href="{{ $r('admin.dashboard', 'admin') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M3 9.5l9-6 9 6V20a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z" fill="#000" opacity=".3"/><path d="M21 10l-9-6-9 6" stroke="#000" stroke-width="2" stroke-linecap="round"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.dashboard') }}</span>
                </a>
            </li>
            @endcan

            <li class="menu-section">
                <h4 class="menu-text">{{ __('menu.basic_data') }}</h4>
                <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
            </li>

            @canany(['properties.view','properties.create','properties.update','properties.delete','properties.show'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/properties*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M3 10l9-6 9 6v8a2 2 0 0 1-2 2h-2v-6H7v6H5a2 2 0 0 1-2-2v-8z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.properties') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        @can('properties.view')
                        <li class="menu-item {{ request()->routeIs('admin.properties.index') ? 'menu-item-active' : '' }}"><a href="{{ $r('admin.properties.index','admin/properties') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.properties_list') }}</span></a></li>
                        @endcan
                        @can('properties.create')
                        <li class="menu-item {{ request()->routeIs('admin.properties.create') ? 'menu-item-active' : '' }}"><a href="{{ $r('admin.properties.create','admin/properties/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.properties_create') }}</span></a></li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany

            @canany(['countries.view','countries.create','governorates.view','governorates.create','states.view','states.create'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/countries*') || request()->is('admin/governorates*') || request()->is('admin/states*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M12 3a9 9 0 1 1 0 18 9 9 0 0 1 0-18zm0 2a7 7 0 1 0 0 14 7 7 0 0 0 0-14z" fill="#000" opacity=".3"/><path d="M12 5a1 1 0 0 1 1 1v12a1 1 0 0 1-2 0V6a1 1 0 0 1 1-1z" fill="#000"/><path d="M18 12a1 1 0 0 1-1 1H7a1 1 0 1 1 0-2h10a1 1 0 0 1 1 1z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.locations') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        @can('countries.view')
                        <li class="menu-item {{ request()->routeIs('admin.countries.index') ? 'menu-item-active' : '' }}">
                            <a href="{{ $r('admin.countries.index','admin/countries') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.countries_list') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('countries.create')
                        <li class="menu-item {{ request()->routeIs('admin.countries.create') ? 'menu-item-active' : '' }}">
                            <a href="{{ $r('admin.countries.create','admin/countries/create') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.countries_create') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('governorates.view')
                        <li class="menu-item {{ request()->routeIs('admin.governorates.index') ? 'menu-item-active' : '' }}">
                            <a href="{{ $r('admin.governorates.index','admin/governorates') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.governorates_list') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('governorates.create')
                        <li class="menu-item {{ request()->routeIs('admin.governorates.create') ? 'menu-item-active' : '' }}">
                            <a href="{{ $r('admin.governorates.create','admin/governorates/create') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.governorates_create') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('states.view')
                        <li class="menu-item {{ request()->routeIs('admin.states.index') ? 'menu-item-active' : '' }}">
                            <a href="{{ $r('admin.states.index','admin/states') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.states_list') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('states.create')
                        <li class="menu-item {{ request()->routeIs('admin.states.create') ? 'menu-item-active' : '' }}">
                            <a href="{{ $r('admin.states.create','admin/states/create') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.states_create') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany
            @canany(['owners.view','owners.create','owners.update','owners.delete'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/owners*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M9 11A4 4 0 1 1 9 3a4 4 0 0 1 0 8z" fill="#000" opacity=".3"/><path d="M2 20c.39-4.77 4.26-7 8.98-7 4.79 0 8.72 2.29 9.01 7H2z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.owners') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        <li class="menu-item"><a href="{{ $r('admin.owners.index','admin/owners') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.owners_list') }}</span></a></li>
                        <li class="menu-item"><a href="{{ $r('admin.owners.create','admin/owners/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.owners_create') }}</span></a></li>
                    </ul>
                </div>
            </li>
            @endcanany

            @canany(['facilities.view','facilities.create','facilities.update','facilities.delete','facilities.show'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/facilities*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><circle cx="5" cy="12" r="2" fill="#000"/><circle cx="12" cy="12" r="2" fill="#000"/><circle cx="19" cy="12" r="2" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.facilities') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        @can('facilities.view')
                        <li class="menu-item {{ request()->routeIs('admin.facilities.index') ? 'menu-item-active' : '' }}"><a href="{{ $r('admin.facilities.index','admin/facilities') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.facilities_list') }}</span></a></li>
                        @endcan
                        @can('facilities.create')
                        <li class="menu-item {{ request()->routeIs('admin.facilities.create') ? 'menu-item-active' : '' }}"><a href="{{ $r('admin.facilities.create','admin/facilities/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.facilities_create') }}</span></a></li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany
            
            @canany(['units.view','units.create','units.update','units.delete','units.show'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/units*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><rect width="7" height="7" rx="1.5" x="4" y="4" fill="#000"/><path d="M5.5 13h4A1.5 1.5 0 0 1 11 14.5v4A1.5 1.5 0 0 1 9.5 20h-4A1.5 1.5 0 0 1 4 18.5v-4A1.5 1.5 0 0 1 5.5 13zM14.5 4h4A1.5 1.5 0 0 1 20 5.5v4A1.5 1.5 0 0 1 18.5 11h-4A1.5 1.5 0 0 1 13 9.5v-4A1.5 1.5 0 0 1 14.5 4zM14.5 13h4A1.5 1.5 0 0 1 20 14.5v4A1.5 1.5 0 0 1 18.5 20h-4A1.5 1.5 0 0 1 13 18.5v-4A1.5 1.5 0 0 1 14.5 13z" fill="#000" opacity=".3"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.units') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <i class="menu-arrow"></i>
                    <ul class="menu-subnav">
                        @can('units.view')
                        <li class="menu-item {{ request()->routeIs('admin.units.index') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ $r('admin.units.index', 'admin/units') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.units_list') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('units.create')
                        <li class="menu-item {{ request()->routeIs('admin.units.create') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ $r('admin.units.create', 'admin/units/create') }}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                <span class="menu-text">{{ __('menu.units_create') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany

            
            @canany(['tenants.view','tenants.create','tenants.update','tenants.delete'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/tenants*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M9 11A4 4 0 1 1 9 3a4 4 0 0 1 0 8z" fill="#000" opacity=".3"/><path d="M2 20c.39-4.77 4.26-7 8.98-7 4.79 0 8.72 2.29 9.01 7H2z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.tenants') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        @can('tenants.view')
                        <li class="menu-item"><a href="{{ $r('admin.tenants.index','admin/tenants') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.tenants_list') }}</span></a></li>
                        @endcan
                        @can('tenants.create')
                        <li class="menu-item"><a href="{{ $r('admin.tenants.create','admin/tenants/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.tenants_create') }}</span></a></li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany

            @canany(['leases.view','leases.create','leases.update','leases.delete'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/contracts*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a1 1 0 0 0 1-1V8l-5-5z" fill="#000" opacity=".3"/><path d="M9 14l2 2 4-4" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.contracts') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        <li class="menu-item"><a href="{{ $r('admin.contracts.index','admin/contracts') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.contracts_list') }}</span></a></li>
                        <li class="menu-item"><a href="{{ $r('admin.contracts.create','admin/contracts/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.contracts_create') }}</span></a></li>
                    </ul>
                </div>
            </li>
            @endcanany

            @canany(['payments.view','payments.create'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/contract-payments*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v3H2V7z" fill="#000" opacity=".25"/><path d="M2 11h20v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-6zm5 4h6a1 1 0 1 1 0 2H7a1 1 0 0 1 0-2z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.contract_payments') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        <li class="menu-item"><a href="{{ $r('admin.contract-payments.index','admin/contract-payments') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('contract_payments.list') }}</span></a></li>
                        <li class="menu-item"><a href="{{ $r('admin.contract-payments.create','admin/contract-payments/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('contract_payments.create') }}</span></a></li>
                    </ul>
                </div>
            </li>
            @endcanany

            @canany(['payments.view','payments.create'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/expenses*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M3 7h18v2H3zM3 11h18v2H3zM3 15h18v2H3z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.expenses') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        <li class="menu-item"><a href="{{ $r('admin.expenses.index','admin/expenses') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.expenses_list') }}</span></a></li>
                        <li class="menu-item"><a href="{{ $r('admin.expenses.create','admin/expenses/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.expenses_create') }}</span></a></li>
                    </ul>
                </div>
            </li>
            @endcanany

            <li class="menu-section">
                <h4 class="menu-text">{{ __('menu.settings') }}</h4>
                <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
            </li>

            
            @canany(['users.view','users.create','users.update','users.delete','users.show'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/users*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" fill="#000" opacity=".3"/><path d="M3 20c.45-4.3 4.1-6.5 9-6.5s8.55 2.2 9 6.5H3z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.users') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        @can('users.view')
                        <li class="menu-item"><a href="{{ $r('admin.users.index','admin/users') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.users_list') }}</span></a></li>
                        @endcan
                        @can('users.create')
                        <li class="menu-item"><a href="{{ $r('admin.users.create','admin/users/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.users_create') }}</span></a></li>
                        @endcan
                        
                    </ul>
                </div>
            </li>
            @endcanany

                        @canany(['roles.view','roles.create','roles.update','roles.delete'])
            <li class="menu-item menu-item-submenu {{ request()->is('admin/roles*') ? 'menu-item-open menu-item-here' : '' }}" data-menu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" fill="#000" opacity=".3"/><path d="M2 20c.4-4.7 4.2-7 9-7s8.6 2.3 9 7H2z" fill="#000"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.roles') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <ul class="menu-subnav">
                        @can('roles.view')
                        <li class="menu-item {{ request()->routeIs('admin.roles.index') ? 'menu-item-active' : '' }}"><a href="{{ $r('admin.roles.index','admin/roles') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.roles_list') }}</span></a></li>
                        @endcan
                        @can('roles.create')
                        <li class="menu-item {{ request()->routeIs('admin.roles.create') ? 'menu-item-active' : '' }}"><a href="{{ $r('admin.roles.create','admin/roles/create') }}" class="menu-link"><i class="menu-bullet menu-bullet-dot"><span></span></i><span class="menu-text">{{ __('menu.roles_create') }}</span></a></li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany
            
            @can('settings.view')
            <li class="menu-item {{ request()->is('admin/settings*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                <a href="{{ $r('admin.settings.index','admin/settings') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M7 3h10a4 4 0 0 1 0 8H7a4 4 0 1 1 0-8z" fill="#000"/><path d="M7 13h10a4 4 0 1 1 0 8H7a4 4 0 1 1 0-8z" fill="#000" opacity=".3"/></g></svg>
                    </span>
                    <span class="menu-text">{{ __('menu.system_settings') }}</span>
                </a>
            </li>
            @endcan

        </ul>
    </div>
</div>

