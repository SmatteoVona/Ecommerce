<!doctype html>
<html lang="it" data-bs-theme="auto">

<head>
  <script src="../assets/js/color-modes.js"></script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

 
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">


  <main class="form-signin w-100 m-auto">
    <form action="autenticazione.php" method="POST">
      <h1 class="h3 mb-3 fw-normal">Accedi con le tue credenziali</h1>

      <div class="form-floating">
        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="nome@esempio.com">
        <label for="floatingInput">Indirizzo email</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>

      <button class="btn btn-primary w-100 py-2" type="submit">Login</button>
    </form>
    <a href="registrazione.php" class="mt-3 d-block">Crea account</a>

  </main>

</body>

</html>