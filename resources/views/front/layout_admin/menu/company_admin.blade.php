 <!--APP-SIDEBAR-->
<?php
    $company_login_id = session()->get('company_login_id');
?>
 <div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{url('/company/'.$company_login_id.'/dashboard')}}">
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ url('theme-asset/images/admin-right-icon.svg') }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img light-logo1" alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>Main</h3>
                </li>
                <?php if ($company_login_id) : ?>
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('dashboard')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/dashboard')}}"><i class="side-menu__icon fe fe-airplay"></i><span class="side-menu__label">Main Dashboard</span></a>
                </li>
                <?php endif; ?>
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('dashboard') || request()->is('company/*/dashboard')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/company/'.$company_login_id.'/dashboard')}}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Company Dashboard</span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('company/*/notification-message')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('company/'.$company_login_id.'/notification-message')}}"><i class="side-menu__icon fe fe-bell"></i><span class="side-menu__label">Notification Message</span></a>
                </li>
                {{-- <li class="slide {{ ( request()->is('company/*/warning') || request()->is('company/*/error')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item {{ ( request()->is('warning') || request()->is('error')) ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-aperture"></i><span class="side-menu__label"> Master </span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Master</a></li>
                        <li><a href="{{url('company/'.$company_login_id.'/warning')}}" class="slide-item {{ ( request()->is('company/*/warning')) ? 'active' : '' }}"> Warning </a></li>
                        <li><a href="{{url('company/'.$company_login_id.'/error')}}" class="slide-item {{ ( request()->is('company/*/error')) ? 'active' : '' }}"> Error </a></li>
                    </ul>
                </li> --}}

                <li class="sub-category">
                    <h3>Manage Users</h3>
                </li>
                <li class="slide {{ ( request()->is('company/*/new-users-list') || request()->is('company/*/new-user-edit/*') || request()->is('company/*/new-user-add') || request()->is('company/*/users-list') || request()->is('company/*/user-add') || request()->is('company/*/user-edit/*')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item {{ ( request()->is('company/*/new-users-list') || request()->is('company/*/new-user-edit/*') || request()->is('company/*/new-user-add') || request()->is('company/*/users-list') || request()->is('company/*/user-add') || request()->is('company/*/user-edit/*')) ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label"> Manage Users </span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Users</a></li>
                        <!-- <li><a href="{{url('/company/'.$company_login_id.'/new-users-list')}}" class="slide-item {{ ( request()->is('company/*/new-users-list') || request()->is('company/*/new-user-edit/*') || request()->is('company/*/new-user-add')) ? 'active' : '' }}"> New Users </a></li> -->
                        <li><a href="{{url('/company/'.$company_login_id.'/users-list')}}" class="slide-item {{ (request()->is('company/*/users-list') || request()->is('company/*/user-add') || request()->is('company/*/user-edit/*')) ? 'active' : '' }}"> Users </a></li>
                    </ul>
                </li>


                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('company/'.$company_login_id.'/system-overview') || request()->is('company/'.$company_login_id.'/device_details/*') || request()->is('company/'.$company_login_id.'/battery_details/*') || request()->is('company/'.$company_login_id.'/edit-device/*') || request()->is('company/'.$company_login_id.'/add-cluster')  || request()->is('company/'.$company_login_id.'/add-device') || request()->is('company/*/charts/*') || request()->is('company/*/remote-access-view/*')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/company/'.$company_login_id.'/system-overview')}}"><i class="side-menu__icon fe fe-aperture"></i><span class="side-menu__label">System Overview</span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('company/'.$company_login_id.'/charts-list') || request()->is('company/*/edit-chart/*') || request()->is('company/*/add-chart') || request()->is('company/*/view-chart/*')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/company/'.$company_login_id.'/charts-list')}}"><i class="side-menu__icon fe fe-pie-chart"></i><span class="side-menu__label">Charts</span></a>
                </li>


            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </aside>
</div>
<!--/APP-SIDEBAR-->
