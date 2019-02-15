<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
     <h1>Call Center</h1>

        <h2>Verifica tu dirección de correo electronico</h2>

        <div>
            Gracias por crear una cuenta , por favor verificala en el link de abajo.
            {{ URL::to('register/verify/' . $confirmation_code) }}.<br/>
        </div>

    </body>
</html>