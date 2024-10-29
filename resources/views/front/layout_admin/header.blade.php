<!doctype html>
<html lang="en" dir="ltr">

    <head>

        <!-- META DATA -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="{{ config('app.name') }}">
        <meta name="author" content="{{ config('app.name') }}">
        <meta name="keywords" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta property="og:title" content="My saving" />
        <!-- FAVICON -->
        <!-- <link rel="shortcut icon" type="image/x-icon" href="{{ url('theme-asset/images/brand/favicon.ico') }}" /> -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ url('theme-asset/images/logo_dark.ico') }}" />
        <!-- TITLE -->
        <title>{{ config('app.name') }}</title>

        <!-- BOOTSTRAP CSS -->
        <link id="style" href="{{ url('theme-asset/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

        <!-- STYLE CSS -->
        <link href="{{ url('theme-asset/css/style.css') }}" rel="stylesheet"/>
		<link href="{{ url('theme-asset/css/plugins.css') }}" rel="stylesheet"/>

        <!--- FONT-ICONS CSS -->
        <link href="{{ url('theme-asset/css/icons.css') }}" rel="stylesheet"/>
        <style>
            .head-alert {
                padding: 0.1rem 1.25rem !important;
            }
            .swal2-container{
                z-index:9999 !important;
            }
        </style>
        @yield('page_level_css')

    </head>
    <?php
        isCompanyLogin();
        $company_login_id    = session()->get('company_login_id');
        $company_login_name  = session()->get('company_login_name');
        $unreadNotifications = getNotification();
        $unreadCounts        = getNotificationCounts();

    ?>
    <body class="app sidebar-mini ltr light-mode">

        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            <img src="{{ url('theme-asset/images/loader.svg') }}" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOBAL-LOADER -->

         <!-- PAGE -->
         <div class="page">
            <div class="page-main">
                <!-- app-Header -->
                <div class="app-header header sticky">
                    <div class="container-fluid main-container">
                        <div class="d-flex align-items-center">
                            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0);"></a>
                            <div class="responsive-logo">
                                <a href="dashboard" class="header-logo">
                                    <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="mobile-logo logo-1" alt="logo">
                                    <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="mobile-logo dark-logo-1" alt="logo">
                                </a>
                            </div>
                            <!-- sidebar-toggle-->
                            <a class="logo-horizontal " href="dashboard">
                                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img desktop-logo" alt="logo">
                                <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img light-logo1"
                                    alt="logo">
                            </a>
                            <!-- LOGO -->
                            <!--<div class="main-header-center ms-3 d-none d-lg-block">
                                <input class="form-control" placeholder="Search for anything..." type="search"> <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>-->
                            <div class="d-flex order-lg-2 ms-auto header-right-icons">
                                <!-- SEARCH -->
                                <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon fe fe-more-vertical text-dark"></span>
                                    </button>
                                <div class="navbar navbar-collapse responsive-navbar p-0">
                                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                        <div class="d-flex order-lg-2">
                                            <div class="dropdown d-block d-lg-none">
                                                <a href="javascript:void(0);" class="nav-link icon" data-bs-toggle="dropdown">
                                                    <i class="fe fe-search"></i>
                                                </a>
                                                <div class="dropdown-menu header-search dropdown-menu-start">
                                                    <div class="input-group w-100 p-2">
                                                        <input type="text" class="form-control" placeholder="Search....">
                                                        <div class="input-group-text btn btn-primary">
                                                            <i class="fa fa-search" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
												<a href="dashboard" class="header-logo">
                                                    <img src="{{ url('theme-asset/images/brand/logo_dark1.png') }}" class="mobile-logo logo-1" style="height:40px;width:170px" alt="logo">
                                                </a>													 
                                            <?php if ($company_login_id) : ?>
                                            <div class="dropdown text-center selector profile-1">
                                                <a href="{{ url('/company') }}" class="nav-link leading-none d-flex" title="Click to Switch Company">
                                                    <span class="d-none d-xl-block alert alert-success rounded-pill head-alert">
                                                        <h5 class="text-dark pt-2">Logged in as &nbsp <strong class="text-capitalize me-1"> <?php echo $company_login_name ?> </strong> <i class="fe fe-repeat" id="myHome"></i> </h5>
                                                    </span>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            {{-- <div class="dropdown d-md-flex">
                                                <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                                    <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                                    <span class="light-layout"><i class="fe fe-sun"></i></span>
                                                </a>
                                            </div> --}}
                                            <!-- Theme-Layout -->
                                            <div class="dropdown d-md-flex">
                                                <a class="nav-link icon full-screen-link nav-link-bg">
                                                    <i class="fe fe-minimize fullscreen-button" id="myvideo"></i>
                                                </a>
                                            </div>
                                            <!-- FULL-SCREEN -->
                                            @can('isAdmin')
                                            <?php if(!$company_login_id): ?>
                                            <div class="dropdown d-md-flex notifications">
                                                <a class="nav-link icon" data-bs-toggle="dropdown"><i class="fe fe-bell"></i>
                                                    <?php if($unreadNotifications && count($unreadNotifications) > 0): ?>
                                                        <span class="pulse"></span>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ">
                                                    <div class="drop-heading border-bottom">
                                                        <div class="d-flex">
                                                            <h6 class="mt-1 mb-0 fs-16 fw-semibold">You have Notification</h6>
                                                            <div class="ms-auto">

                                                                <span class="badge bg-success rounded-pill unreadNotifCount">{{$unreadCounts ?? 0}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="notifications-menu">
                                                        <?php
                                                            if($unreadNotifications && count($unreadNotifications) > 0) :
                                                            foreach ($unreadNotifications as $key => $notification) :
                                                        ?>

                                                        <a class="dropdown-item d-flex single_read_mark" data-id="{{$notification->id}}" href="{{ url('/user-edit/'.$notification->user_id)}}">
                                                            <div class="me-3 notifyimg  bg-primary-gradient brround box-shadow-primary">
                                                                <i class="fe fe-user"></i>
                                                            </div>
                                                            <div class="mt-1 wd-80p">
                                                                <h5 class="notification-label mb-1">New User Register</h5>
                                                                <p class="small mb-0">{{$notification->user->full_name ?? ''}}</p>
                                                                <span class="notification-subtext">{{ $notification->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </a>

                                                        <?php endforeach; endif; ?>
                                                    </div>
                                                    <div class="dropdown-divider m-0"></div>
                                                    <a href="{{url('/notification-list')}}" class="dropdown-item text-center p-3 text-muted">View all Notification</a>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            @endcan
                                            <!-- NOTIFICATIONS -->
                                            {{-- <div class="dropdown d-md-flex message">
                                                <a class="nav-link icon text-center" data-bs-toggle="dropdown">
                                                    <i class="fe fe-message-square"></i><span class=" pulse-danger"></span>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                    <div class="drop-heading border-bottom">
                                                        <div class="d-flex">
                                                            <h6 class="mt-1 mb-0 fs-16 fw-semibold">You have Messages</h6>
                                                            <div class="ms-auto">
                                                                <span class="badge bg-danger rounded-pill">4</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="message-menu">
                                                        <a class="dropdown-item d-flex" href="chat.html">
                                                            <span class="avatar avatar-md brround me-3 align-self-center cover-image" data-bs-image-src="{{ url('theme-asset/images/users/1.jpg') }}"></span>
                                                            <div class="wd-90p">
                                                                <div class="d-flex">
                                                                    <h5 class="mb-1">Madeleine</h5>
                                                                    <small class="text-muted ms-auto text-end">
                                                                            3 hours ago
                                                                        </small>
                                                                </div>
                                                                <span>Hey! there I' am available....</span>
                                                            </div>
                                                        </a>
                                                        <a class="dropdown-item d-flex" href="chat.html">
                                                            <span class="avatar avatar-md brround me-3 align-self-center cover-image" data-bs-image-src="{{ url('theme-asset/images/users/12.jpg') }}"></span>
                                                            <div class="wd-90p">
                                                                <div class="d-flex">
                                                                    <h5 class="mb-1">Anthony</h5>
                                                                    <small class="text-muted ms-auto text-end">
                                                                            5 hour ago
                                                                        </small>
                                                                </div>
                                                                <span>New product Launching...</span>
                                                            </div>
                                                        </a>
                                                        <a class="dropdown-item d-flex" href="chat.html">
                                                            <span class="avatar avatar-md brround me-3 align-self-center cover-image" data-bs-image-src="{{ url('theme-asset/images/users/4.jpg') }}"></span>
                                                            <div class="wd-90p">
                                                                <div class="d-flex">
                                                                    <h5 class="mb-1">Olivia</h5>
                                                                    <small class="text-muted ms-auto text-end">
                                                                            45 mintues ago
                                                                        </small>
                                                                </div>
                                                                <span>New Schedule Realease......</span>
                                                            </div>
                                                        </a>
                                                        <a class="dropdown-item d-flex" href="chat.html">
                                                            <span class="avatar avatar-md brround me-3 align-self-center cover-image" data-bs-image-src="{{ url('theme-asset/images/users/15.jpg') }}"></span>
                                                            <div class="wd-90p">
                                                                <div class="d-flex">
                                                                    <h5 class="mb-1">Sanderson</h5>
                                                                    <small class="text-muted ms-auto text-end">
                                                                            2 days ago
                                                                        </small>
                                                                </div>
                                                                <span>New Schedule Realease......</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="dropdown-divider m-0"></div>
                                                    <a href="javascript:void(0);" class="dropdown-item text-center p-3 text-muted">See all Messages</a>
                                                </div>
                                            </div> --}}
                                            <!-- MESSAGE-BOX -->
                                            <div class="dropdown d-md-flex profile-1">
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown" class="nav-link leading-none d-flex px-1">
                                                    <span>
                                                            <img src="{{ url('theme-asset/images/user.png') }}" alt="profile-user" class="avatar  profile-user brround cover-image">
                                                        </span>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                    <div class="drop-heading">
                                                        <div class="text-center">
                                                            <h5 class="text-dark mb-0">
                                                                @if (Auth::guard('admin')->check() == 1)
                                                                @isset(Auth::guard('admin')->user()->first_name)
                                                                    {{ Auth::guard('admin')->user()->first_name .' '. Auth::guard('admin')->user()->last_name }}
                                                                @endisset
                                                                @endif
                                                             </h5>
                                                            {{-- <small class="text-muted">Administrator</small> --}}
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-divider m-0"></div>
                                                    {{-- <a class="dropdown-item" href="profile.html">
                                                        <i class="dropdown-icon fe fe-user"></i> Profile
                                                    </a>
                                                    <a class="dropdown-item" href="email.html">
                                                        <i class="dropdown-icon fe fe-mail"></i> Inbox
                                                        <span class="badge bg-secondary float-end">3</span>
                                                    </a>
                                                    <a class="dropdown-item" href="emailservices.html">
                                                        <i class="dropdown-icon fe fe-settings"></i> Settings
                                                    </a>
                                                    <a class="dropdown-item" href="faq.html">
                                                        <i class="dropdown-icon fe fe-alert-triangle"></i> Need help?
                                                    </a> --}}
                                                    <a class="dropdown-item" href="{{ url('logout') }}">
                                                        <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                                                    </a>
                                                </div>
                                            </div>
                                            {{-- <div class="dropdown d-md-flex header-settings">
                                                <a href="javascript:void(0);" class="nav-link icon " data-bs-toggle="sidebar-right" data-target=".sidebar-right">
                                                    <i class="fe fe-menu"></i>
                                                </a>
                                            </div> --}}
                                            <!-- SIDE-MENU -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /app-Header -->




