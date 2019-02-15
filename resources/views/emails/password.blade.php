<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
     <h1>Call Center</h1>

        <h2>Reinicio de contraseña</h2>

        <div>
			Click aca para reiniciar la contraseña: {{ url('password/reset/'.$token) }}
		</div>

    </body>
</html>
