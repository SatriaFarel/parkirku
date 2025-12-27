@component('mail::message')
# Reset Password

Klik tombol berikut untuk mereset password Anda:

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

Jika Anda tidak meminta reset password, abaikan email ini.

@endcomponent
