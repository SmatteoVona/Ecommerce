<!doctype html>
<html lang="it" data-bs-theme="auto">

<head>
  <script src="../assets/js/color-modes.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Registra account</title>
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

  <main class="form-signin w-100 m-auto">
    <form action="../php/registra_utente.php" method="POST">
      <h1 class="h3 mb-3 fw-normal">Crea un nuovo account</h1>

      <div class="form-floating">
        <input type="text" class="form-control" id="floatingNome" name="nome" placeholder="Nome" required>
        <label for="floatingNome">Nome</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="floatingCognome" name="cognome" placeholder="Cognome" required>
        <label for="floatingCognome">Cognome</label>
      </div>
      <div class="form-floating">
        <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="nome@esempio.com"
          required>
        <label for="floatingEmail">Indirizzo Email</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password"
          required>
        <label for="floatingPassword">Password</label>
      </div>

      <button class="btn btn-lg btn-primary w-100" type="submit">Registra</button>
      <a href="index.php" class="mt-3 d-block">Hai già un account? Accedi</a>
    </form>
  </main>

</body>

</html>