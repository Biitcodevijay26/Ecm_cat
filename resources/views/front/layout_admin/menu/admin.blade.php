 <!--APP-SIDEBAR-->
<?php
    $company_login_id = session()->get('company_login_id');
?>
 <div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{url('/dashboard')}}">
                @if (auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ url('theme-asset/images/admin-right-icon.svg') }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img light-logo1" alt="logo">
                @else
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ url('theme-asset/images/admin-right-icon.svg') }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img light-logo1" alt="logo">
                @endif
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
                @can('isAdmin')
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('dashboard')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/dashboard')}}"><i class="side-menu__icon fe fe-airplay"></i><span class="side-menu__label">Main Dashboard</span></a>
                </li>
                <?php if ($company_login_id) : ?>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{url('/company')}}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Company Dashboard</span></a>
                </li>
                <?php endif; ?>
                @endcan
                @can('isUser')
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('dashboard')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/dashboard')}}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Company Dashboard</span></a>
                </li>
                @endcan

                @if(auth()->guard('admin')->user()->is_active == 1)
                @can('NotificationMessageList')
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('notification-message')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/notification-message')}}"><i class="side-menu__icon fe fe-bell"></i><span class="side-menu__label">Notification Message</span></a>
                </li>
                @endcan
                @endif

                {{-- <li class="slide {{ ( request()->is('warning') || request()->is('error')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item {{ ( request()->is('warning') || request()->is('error')) ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-aperture"></i><span class="side-menu__label"> Master </span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Master</a></li>
                        <li><a href="{{url('/warning')}}" class="slide-item {{ ( request()->is('warning')) ? 'active' : '' }}"> Warning </a></li>
                        <li><a href="{{url('/error')}}" class="slide-item {{ ( request()->is('error')) ? 'active' : '' }}"> Error </a></li>
                    </ul>
                </li> --}}
                @if (auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))


                <li class="sub-category">
                    <h3>General </h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('company') || request()->is('device_details/*') || request()->is('system-overview/*')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/company')}}"><i class="side-menu__icon fa fa-hospital-o"></i><span class="side-menu__label">Manage Companies</span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('countries')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/countries')}}"><i class="side-menu__icon fe fe-globe"></i><span class="side-menu__label">Manage Countries</span></a>
                    {{-- <a class="side-menu__item module_access_btn {{ ( request()->is('countries')) ? 'active' : '' }}" data-bs-toggle="slide" data-name="countries" data-page="countries"  href="javascript:void(0);"><i class="side-menu__icon fe fe-globe"></i><span class="side-menu__label">Manage Countries</span></a> --}}
                </li>
				
				
				
                {{-- <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('icon-settings')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/icon-settings')}}"><i class="side-menu__icon fe fe-command"></i><span class="side-menu__label">Icon Settings</span></a>
                </li> --}}
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('agent')) || (request()->is('agent-add')) || ( request()->is('agent-detail-view/*'))  ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/agent')}}"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label"> Agents</span></a>
                    {{-- <a class="side-menu__item module_access_btn {{ ( request()->is('agent')) || (request()->is('agent-add')) || ( request()->is('agent-detail-view/*'))  ? 'active' : '' }}" data-bs-toggle="slide" data-name="agent" data-page="agent" href="javascript:void(0);"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label"> Agents</span></a> --}}
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('channel')) || (request()->is('channel-add')) || ( request()->is('channel-assign/*'))  ? 'active' : '' }}" data-bs-toggle="slide"  href="{{url('/channel')}}"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label"> Channel</span></a>
                    {{-- <a class="side-menu__item module_access_btn {{ ( request()->is('channel')) || (request()->is('channel-add')) || ( request()->is('channel-assign/*'))  ? 'active' : '' }}" data-bs-toggle="slide" data-name="channel" data-page="channel" href="javascript:void(0);"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label"> Channel</span></a> --}}
                </li>
				<li class="slide">
                    <a class="side-menu__item {{ ( request()->is('reporting')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/reporting')}}"><i class="side-menu__icon fe fe-grid"></i><span class="side-menu__label">Reporting</span></a>
																																																																																											   
                </li>
	
                @endif

                @if(auth()->guard('admin')->user()->is_active == 1 || auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
                @can('UserManagementList')
                <li class="sub-category">
                    <h3>Manage Users</h3>
                </li>
                <li class="slide {{ ( request()->is('new-users-list') || request()->is('users-list') || request()->is('new-user-*') || request()->is('user-*')) ? 'is-expanded' : '' }}">
                    <a href="{{url('/users-list')}}" class="side-menu__item {{ ( request()->is('users-list') || request()->is('users-list') || request()->is('new-user-*') ||  request()->is('user-*')) ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label"> Manage Users </span><!--<i class="angle fa fa-angle-right"></i>--></a>
                    <!--<ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Users</a></li>
                        <li><a href="{{url('/new-users-list')}}" class="slide-item {{ ( request()->is('new-users-list') || request()->is('new-user-*')) ? 'active' : '' }}"> New Users </a></li>
                        <li><a href="{{url('/users-list')}}" class="slide-item {{ (request()->is('users-list') || request()->is('user-*')) ? 'active' : '' }}"> Users </a></li>
                    </ul>-->
                </li>
                @endcan
                @endif

                @if(auth()->guard('admin')->user()->is_active == 1)
                @can('isUser')
                @can('DeviceManagementList')
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('system-overview') || request()->is('add-cluster') || request()->is('add-device') || request()->is('edit-device/*') || request()->is('device_details/*') || request()->is('battery_details/*') || request()->is('device-alarms-list/*') || request()->is('remote-access-view/*')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/system-overview')}}"><i class="side-menu__icon fe fe-aperture"></i><span class="side-menu__label">System Overview</span></a>
                </li>
                @endcan
                @can('ChartList')
                <li class="slide">
                    <a class="side-menu__item {{ ( request()->is('charts-list') || request()->is('view-chart/*') || request()->is('add-chart') || request()->is('edit-chart/*')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/charts-list')}}"><i class="side-menu__icon fe fe-pie-chart"></i><span class="side-menu__label">Charts</span></a>
                </li>
				<li class="slide">
                    <a class="side-menu__item {{ ( request()->is('reporting')) ? 'active' : '' }}" data-bs-toggle="slide" href="{{url('/reporting')}}"><i class="side-menu__icon fe fe-grid"></i><span class="side-menu__label">Reporting</span></a>
                </li>	 																																																								
                @endcan

                @endcan
                @endif
                @if (auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
                <li class="sub-category">
                    <h3>Logs </h3>
                </li>
                 <li class="slide">
                   <a class="side-menu__item " data-bs-toggle="slide" href="{{url('/logging-monitoring')}}"><i class="side-menu__icon fe fe-grid"></i> <span class="side-menu__label">Loggin &  Monitoring</span></a>

                </li>
                   @endif

                <?php /**  ?>
                <li class="sub-category">
                    <h3>Widgets</h3>
                </li>
                <li>
                    <a class="side-menu__item" href="widgets.html"><i class="side-menu__icon fe fe-grid"></i><span class="side-menu__label">Widgets</span></a>
                </li>

                <li class="sub-category">
                    <h3>Elements</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-database"></i><span class="side-menu__label">Components</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Components</a></li>
                        <li><a href="cards.html" class="slide-item"> Cards design</a></li>
                        <li><a href="calendar.html" class="slide-item"> Default calendar</a></li>
                        <li><a href="calendar2.html" class="slide-item"> Full calendar</a></li>
                        <li><a href="chat.html" class="slide-item"> Default Chat</a></li>
                        <li><a href="notify.html" class="slide-item"> Notifications</a></li>
                        <li><a href="sweetalert.html" class="slide-item"> Sweet alerts</a></li>
                        <li><a href="rangeslider.html" class="slide-item"> Range slider</a></li>
                        <li><a href="scroll.html" class="slide-item"> Content Scroll bar</a></li>
                        <li><a href="loaders.html" class="slide-item"> Loaders</a></li>
                        <li><a href="counters.html" class="slide-item"> Counters</a></li>
                        <li><a href="rating.html" class="slide-item"> Rating</a></li>
                        <li><a href="timeline.html" class="slide-item"> Timeline</a></li>
                        <li><a href="treeview.html" class="slide-item"> Treeview</a></li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-package"></i><span class="side-menu__label">Elements</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Elements</a></li>
                        <li><a href="alerts.html" class="slide-item"> Alerts</a></li>
                        <li><a href="buttons.html" class="slide-item"> Buttons</a></li>
                        <li><a href="colors.html" class="slide-item"> Colors</a></li>
                        <li><a href="avatarsquare.html" class="slide-item"> Avatar-Square</a></li>
                        <li><a href="avatar-round.html" class="slide-item"> Avatar-Rounded</a></li>
                        <li><a href="avatar-radius.html" class="slide-item"> Avatar-Radius</a></li>
                        <li><a href="dropdown.html" class="slide-item"> Drop downs</a></li>
                        <li><a href="list.html" class="slide-item"> List</a></li>
                        <li><a href="tags.html" class="slide-item"> Tags</a></li>
                        <li><a href="pagination.html" class="slide-item"> Pagination</a></li>
                        <li><a href="navigation.html" class="slide-item"> Navigation</a></li>
                        <li><a href="typography.html" class="slide-item"> Typography</a></li>
                        <li><a href="breadcrumbs.html" class="slide-item"> Breadcrumbs</a></li>
                        <li><a href="badge.html" class="slide-item"> Badges</a></li>
                        <li><a href="panels.html" class="slide-item"> Panels</a></li>
                        <li><a href="thumbnails.html" class="slide-item"> Thumbnails</a></li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-file"></i><span class="side-menu__label">Advanced Elements</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Advanced Elements</a></li>
                        <li><a href="mediaobject.html" class="slide-item"> Media Object</a></li>
                        <li><a href="accordion.html" class="slide-item"> Accordions</a></li>
                        <li><a href="tabs.html" class="slide-item"> Tabs</a></li>
                        <li><a href="chart.html" class="slide-item"> Charts</a></li>
                        <li><a href="modal.html" class="slide-item"> Modal</a></li>
                        <li><a href="tooltipandpopover.html" class="slide-item"> Tooltip and popover</a></li>
                        <li><a href="progress.html" class="slide-item"> Progress</a></li>
                        <li><a href="carousel.html" class="slide-item"> Carousels</a></li>
                        <li><a href="headers.html" class="slide-item"> Headers</a></li>
                        <li><a href="footers.html" class="slide-item"> Footers</a></li>
                        <li><a href="users-list.html" class="slide-item"> User List</a></li>
                        <li><a href="search.html" class="slide-item">Search</a></li>
                        <li><a href="crypto-currencies.html" class="slide-item"> Crypto-currencies</a></li>
                    </ul>
                </li>
                <li class="sub-category">
                    <h3>Charts & Tables</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-pie-chart"></i><span class="side-menu__label">Charts</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Charts</a></li>
                        <li><a href="chart-chartist.html" class="slide-item">Chart Js</a></li>
                        <li><a href="chart-flot.html" class="slide-item"> Flot Charts</a></li>
                        <li><a href="chart-echart.html" class="slide-item"> ECharts</a></li>
                        <li><a href="chart-morris.html" class="slide-item"> Morris Charts</a></li>
                        <li><a href="chart-nvd3.html" class="slide-item"> Nvd3 Charts</a></li>
                        <li><a href="charts.html" class="slide-item"> C3 Bar Charts</a></li>
                        <li><a href="chart-line.html" class="slide-item"> C3 Line Charts</a></li>
                        <li><a href="chart-donut.html" class="slide-item"> C3 Donut Charts</a></li>
                        <li><a href="chart-pie.html" class="slide-item"> C3 Pie charts</a></li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-clipboard"></i><span class="side-menu__label">Tables</span><span class="badge bg-secondary side-badge">2</span><i class="angle fa fa-angle-right hor-rightangle"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Tables</a></li>
                        <li><a href="tables.html" class="slide-item">Default table</a></li>
                        <li><a href="datatable.html" class="slide-item"> Data Tables</a></li>
                    </ul>
                </li>
                <li class="sub-category">
                    <h3>Pages</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-layers"></i><span class="side-menu__label">Pages</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Pages</a></li>
                        <li><a href="profile.html" class="slide-item"> Profile</a></li>
                        <li><a href="editprofile.html" class="slide-item"> Edit Profile</a></li>
                        <li><a href="email.html" class="slide-item"> Mail-Inbox</a></li>
                        <li><a href="emailservices.html" class="slide-item"> Mail-Compose</a></li>
                        <li><a href="gallery.html" class="slide-item"> Gallery</a></li>
                        <li><a href="about.html" class="slide-item"> About Company</a></li>
                        <li><a href="services.html" class="slide-item"> Services</a></li>
                        <li><a href="faq.html" class="slide-item"> FAQS</a></li>
                        <li><a href="terms.html" class="slide-item"> Terms</a></li>
                        <li><a href="invoice.html" class="slide-item"> Invoice</a></li>
                        <li><a href="pricing.html" class="slide-item"> Pricing Tables</a></li>
                        <li><a href="empty.html" class="slide-item"> Empty Page</a></li>
                        <li><a href="construction.html" class="slide-item"> Under Construction</a></li>
                        <li><a href="switcher.html" class="slide-item"> Theme Style</a></li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Blog</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a href="blog.html" class="sub-slide-item">Blog</a></li>
                                <li><a href="blog-details.html" class="sub-slide-item">Blog Details</a></li>
                                <li><a href="blog-post.html" class="sub-slide-item">Blog Post</a></li>
                            </ul>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Maps</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a href="maps1.html" class="sub-slide-item">Leaflet Maps</a></li>
                                <li><a href="maps2.html" class="sub-slide-item">Mapel Maps</a></li>
                                <li><a href="maps.html" class="sub-slide-item">Vector Maps</a></li>
                            </ul>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">E-Commerce</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a href="shop.html" class="sub-slide-item">Shop</a></li>
                                <li><a href="shop-description.html" class="sub-slide-item">Shopping Details</a></li>
                                <li><a href="cart.html" class="sub-slide-item">Shopping Cart</a></li>
                                <li><a href="wishlist.html" class="sub-slide-item">Wishlist</a></li>
                                <li><a href="checkout.html" class="sub-slide-item">Checkout</a></li>
                            </ul>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">File Manager</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a href="file-manager.html" class="sub-slide-item">File Manager</a></li>
                                <li><a href="filemanager-list.html" class="sub-slide-item">File Manager List</a></li>
                                <li><a href="filemanager-details.html" class="sub-slide-item">File Details</a></li>
                                <li><a href="file-attachments.html" class="sub-slide-item">File Attachments</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sub-category">
                    <h3>Custom & Error Pages</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-settings"></i><span class="side-menu__label">Custom Pages</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Custom Pages</a></li>
                        <li><a href="login.html" class="slide-item"> Login</a></li>
                        <li><a href="register.html" class="slide-item"> Register</a></li>
                        <li><a href="forgot-password.html" class="slide-item"> Forgot Password</a></li>
                        <li><a href="lockscreen.html" class="slide-item"> Lock screen</a></li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Error Pages</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="400.html">400</a></li>
                                <li><a class="sub-slide-item" href="401.html">401</a></li>
                                <li><a class="sub-slide-item" href="403.html">403</a></li>
                                <li><a class="sub-slide-item" href="404.html">404</a></li>
                                <li><a class="sub-slide-item" href="500.html">500</a></li>
                                <li><a class="sub-slide-item" href="503.html">503</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fe fe-sliders"></i>
                        <span class="side-menu__label">Submenus</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Submenus</a></li>
                        <li><a href="javascript:void(0);" class="slide-item">Level-1</a></li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Level-2</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="javascript:void(0);">Level-2.1</a></li>
                                <li><a class="sub-slide-item" href="javascript:void(0);">Level-2.2</a></li>
                                <li class="sub-slide2">
                                    <a class="sub-side-menu__item2" href="javascript:void(0);" data-bs-toggle="sub-slide2"><span class="sub-side-menu__label2">Level-2.3</span><i class="sub-angle2 fa fa-angle-right"></i></a>
                                    <ul class="sub-slide-menu2">
                                        <li><a href="javascript:void(0);" class="sub-slide-item2">Level-2.3.1</a></li>
                                        <li><a href="javascript:void(0);" class="sub-slide-item2">Level-2.3.2</a></li>
                                        <li><a href="javascript:void(0);" class="sub-slide-item2">Level-2.3.3</a></li>
                                    </ul>
                                </li>
                                <li><a class="sub-slide-item" href="javascript:void(0);">Level-2.4</a></li>
                                <li><a class="sub-slide-item" href="javascript:void(0);">Level-2.5</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sub-category">
                    <h3>Forms & Icons</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-file-text"></i><span class="side-menu__label">Forms</span><span class="badge bg-success side-badge">5</span><i class="angle fa fa-angle-right hor-rightangle"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Forms</a></li>
                        <li><a href="form-elements.html" class="slide-item"> Form Elements</a></li>
                        <li><a href="form-advanced.html" class="slide-item"> Form Advanced</a></li>
                        <li><a href="wysiwyag.html" class="slide-item"> Form Editor</a></li>
                        <li><a href="form-wizard.html" class="slide-item"> Form Wizard</a></li>
                        <li><a href="form-validation.html" class="slide-item"> Form Validation</a></li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-command"></i><span class="side-menu__label">Icons</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Icons</a></li>
                        <li><a href="icons.html" class="slide-item"> Font Awesome</a></li>
                        <li><a href="icons2.html" class="slide-item"> Material Design Icons</a></li>
                        <li><a href="icons3.html" class="slide-item"> Simple Line Icons</a></li>
                        <li><a href="icons4.html" class="slide-item"> Feather Icons</a></li>
                        <li><a href="icons5.html" class="slide-item"> Ionic Icons</a></li>
                        <li><a href="icons6.html" class="slide-item"> Flag Icons</a></li>
                        <li><a href="icons7.html" class="slide-item"> pe7 Icons</a></li>
                        <li><a href="icons8.html" class="slide-item"> Themify Icons</a></li>
                        <li><a href="icons9.html" class="slide-item">Typicons Icons</a></li>
                        <li><a href="icons10.html" class="slide-item">Weather Icons</a></li>
                        <li><a href="icons11.html" class="slide-item">Bootstrap Icons</a></li>
                    </ul>
                </li>
                <?php */ ?>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </aside>
</div>
<!--/APP-SIDEBAR-->
