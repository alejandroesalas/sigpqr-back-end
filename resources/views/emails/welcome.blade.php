
Bienvenido {{$user->name}}

Has creado una cuenta en SIGPQR. Por favor Verificala usando el siguiente enlace:

{{route('verify',$user->verification_token)}}
