@component('mail::message')
# Welcome {{ $data['name'] }}

 <h3> Here is your login details </h3>
 <br>
 Thank you for registering with us your account is succesfully created.
 <br>
 Password : &nbsp; <strong> {{ $data['password'] }} </strong>
 

Thanks,<br>
{{ config('app.name') }}
@endcomponent
