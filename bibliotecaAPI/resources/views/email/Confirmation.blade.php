<!doctype html>
<html lang="en">
<head>
    <title>Verificação de E-mail</title>
</head>
<body>
        <p>Olá, {{ $user->name }}</p>
        <p>Clique no botão para verificar seu e-mail:</p>
        <form method="POST" action={{ route('confirm.email', ['id' => $user->id, 'token' => $user->verification_token]) }}>
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-primary">confirmar cadastro</button>
        </form>
</body>
</html>
