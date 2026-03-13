<!--begin::Sidebar-->
<div
    id="kt_app_sidebar"
    class="app-sidebar flex-column"
    data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="225px"
    data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle"
>
    <div class="app-sidebar-logo px-6 d-flex justify-content-center align-items-center" style="height: 100px" id="kt_app_sidebar_logo">
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Kashmir Grill House Logo" src="{{ asset('assets/images/logo.png') }}" class="kgh-admin-logo my-4 app-sidebar-logo-default" />
            <img alt="Kashmir Grill House Logo" src="{{ asset('assets/images/logo.png') }}" class="kgh-admin-logo--mini app-sidebar-logo-minimize" />
        </a>

        <div
            id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize"
        >
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div
                id="kt_app_sidebar_menu_scroll"
                class="scroll-y my-5 mx-3"
                data-kt-scroll="true"
                data-kt-scroll-activate="true"
                data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu"
                data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true"
            >
                <div id="kt_app_sidebar_menu" class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" data-kt-menu="true">
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-element-11 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.menu-categories.*') ? 'active' : '' }}" href="{{ route('admin.menu-categories.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-category fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title">Menu Categories</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}" href="{{ route('admin.menu-items.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-basket fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Menu Items</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.dine-in-slots.*') ? 'active' : '' }}" href="{{ route('admin.dine-in-slots.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-time fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dine-In Slots</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-user fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">My Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Sidebar-->
