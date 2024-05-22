<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email Verification</title>
</head>

<body>
<h2>Welcome to the site {{@$user->name}}</h2>
<br/>
Your registered email-id is {{@$user->email}} , Please click on the below link to verify your email account
<br/>
@php
    $verify_user=DB::table('verify_users')->where('user_id',$user->id)->first();
@endphp
<a href="{{url('user/verify', $verify_user->token)}}">Verify Email</a>
</body>

</html>