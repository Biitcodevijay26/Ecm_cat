<!-- BEGIN: Main Menu-->

@if( isCompanyLogin() == 'true')
     @include('front.layout_admin.menu.company_admin')
@else
     @include('front.layout_admin.menu.admin')
@endif

