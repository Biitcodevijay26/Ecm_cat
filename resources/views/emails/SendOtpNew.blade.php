@component('mail::message')
# Hello {{$data->name}},

Welcome to {{ config('app.name') }}. <br>
We are happy to onboard you in our app and your varification code is <b> {{$data->otp}} </b>. <br>
Please enter this verification code which is valid for 10 minutes. <br> <br> <br>


Thanks & Regards<br>
{{ config('app.name') }} Team.<br>
@endcomponent
