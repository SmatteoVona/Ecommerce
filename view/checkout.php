<!doctype html>
<html lang="it" data-bs-theme="auto">

<head>
  <script src="../assets/js/color-modes.js"></script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/checkout/">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="../css/checkout.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

  <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="check2" viewBox="0 0 16 16">
      <path
        d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
    </symbol>
    <symbol id="circle-half" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
    </symbol>
    <symbol id="moon-stars-fill" viewBox="0 0 16 16">
      <path
        d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
      <path
        d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
    </symbol>
    <symbol id="sun-fill" viewBox="0 0 16 16">
      <path
        d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
    </symbol>
  </svg>
  <?php
  session_start();
  include "../php/connessione.php";
  $connessione = new mysqli($hostname, $username, $password, "ecommerce");

  if ($connessione->connect_error) {
    die("Connessione fallita: " . $connessione->connect_error);
  }

  // Inizializza il totale e l'array degli articoli
  $total = 0;
  $cartItems = [];


  // Controlla se l'utente è autenticato
  if (isset($_SESSION['user_id'])) {
    $idCliente = $_SESSION['user_id'];

    // Ottieni l'ID del carrello dell'utente
    $sqlUtente = "SELECT nome, cognome, mail FROM cliente WHERE ID = ?";
    if ($stmt = $connessione->prepare($sqlUtente)) {
      $stmt->bind_param("i", $idCliente);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($user = $result->fetch_assoc()) {
        $nome = $user['nome'];
        $cognome = $user['cognome'];
        $email = $user['mail'];
      }
      $stmt->close();
    }
    $sqlCarrello = "SELECT ID FROM carrello WHERE ID_cliente = ? LIMIT 1";
    if ($stmt = $connessione->prepare($sqlCarrello)) {
      $stmt->bind_param("i", $idCliente);
      $stmt->execute();
      $result = $stmt->get_result();
      $idCarrello = $result->num_rows > 0 ? $result->fetch_assoc()['ID'] : null;
      $stmt->close();

      if ($idCarrello) {
        // Ottieni tutti i prodotti nel carrello
        // Ottieni tutti i prodotti nel carrello con gli accessori corrispondenti
        $sqlProdottiCarrello = "SELECT 
        p.ID as prodotto_id,
        p.nome AS nome_prodotto, 
        p.descrizione AS descrizione_prodotto, 
        p.prezzo AS prezzo_prodotto, 
        a.ID as accessorio_id,
        a.nome AS nome_accessorio, 
        a.prezzo AS prezzo_accessorio
      FROM 
        prodotti_carrello pc
      LEFT JOIN 
        prodotto p ON pc.ID_prodotto = p.ID
      LEFT JOIN 
        accessorio a ON pc.ID_accessorio = a.ID
      WHERE 
        pc.ID_carrello = ?;
      
      ";

        if ($stmt = $connessione->prepare($sqlProdottiCarrello)) {
          $stmt->bind_param("i", $idCarrello);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            $prezzoTotaleArticolo = $row['prezzo_prodotto'] + $row['prezzo_accessorio'];
            $total += $prezzoTotaleArticolo;

            $cartItems = [];
            $cartItems = [];
            $total = 0;

            while ($row = $result->fetch_assoc()) {
              $prodotto_id = $row['prodotto_id'];
              if (!isset($cartItems[$prodotto_id])) {
                $cartItems[$prodotto_id] = [
                  'nome_prodotto' => $row['nome_prodotto'],
                  'descrizione_prodotto' => $row['descrizione_prodotto'],
                  'prezzo_prodotto' => $row['prezzo_prodotto'],
                  'accessori' => []
                ];
                // Aggiungi il prezzo del prodotto al totale
                $total += $row['prezzo_prodotto'];
              }
              if ($row['accessorio_id']) {
                $cartItems[$prodotto_id]['accessori'][] = [
                  'nome_accessorio' => $row['nome_accessorio'],
                  'prezzo_accessorio' => $row['prezzo_accessorio']
                ];
                // Aggiungi il prezzo dell'accessorio al totale
                $total += $row['prezzo_accessorio'];
              }
            }

          }
        }

      }

      $stmt->close();
    }

  } else {
    echo "Utente non autenticato.";
    // Reindirizza l'utente alla pagina di login o gestisci diversamente
  }

  $connessione->close();
  ?>

  <div class="container">
    <main>
      <div class="py-5 text-center">
        <!-- ... Resto del contenuto ... -->
      </div>
      <form action="../php/gestione_checkout.php" method="POST">

        <div class="row g-5">
          <div class="col-md-5 col-lg-4 order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-primary">Your cart</span>
              <span class="badge bg-primary rounded-pill">
                <?php echo count($cartItems); ?>
              </span>
            </h4>
            <ul class="list-group mb-3">
            <?php foreach ($cartItems as $prodotto_id => $item): ?>
  <li class="list-group-item d-flex justify-content-between lh-sm">
    <div>
      <h6 class="my-0"><?php echo htmlspecialchars($item['nome_prodotto']); ?></h6>
      <small class="text-body-secondary"><?php echo htmlspecialchars($item['descrizione_prodotto']); ?></small>
      <?php foreach ($item['accessori'] as $accessorio): ?>
        <div><?php echo htmlspecialchars($accessorio['nome_accessorio']); ?> - $<?php echo htmlspecialchars(number_format($accessorio['prezzo_accessorio'], 2)); ?></div>
      <?php endforeach; ?>
    </div>
    <span class="text-body-secondary">$<?php echo number_format($item['prezzo_prodotto'], 2); ?></span>
  </li>
<?php endforeach; ?>

<li class="list-group-item d-flex justify-content-between">
  <span>Total (USD)</span>
  <strong>$<?php echo number_format($total, 2); ?></strong>
</li>

            </ul>
          </div>
          <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Billing address</h4>

            <div class="row g-3">
              <div class="col-sm-6">
                <label for="firstName" class="form-label">First name</label>
                <input type="text" class="form-control" id="firstName" placeholder=""
                  value="<?php echo htmlspecialchars($nome); ?>" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <div class="col-sm-6">
                <label for="lastName" class="form-label">Last name</label>
                <input type="text" class="form-control" id="lastName" placeholder=""
                  value="<?php echo htmlspecialchars($cognome); ?>" required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>

              <div class="col-12">
                <label for="email" class="form-label">Email <span class="text-body-secondary">(Optional)</span></label>
                <input type="email" class="form-control" id="email" placeholder="you@example.com"
                  value="<?php echo htmlspecialchars($email); ?>">
                <div class="invalid-feedback">
                  Please enter a valid email address for shipping updates.
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" id="address" placeholder="1234 Main St" required>
              <div class="invalid-feedback">
                Please enter your shipping address.
              </div>
            </div>

            <h4 class="mb-3">Payment</h4>

            <div class="my-3">
              <div class="form-check">
                <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
                <label class="form-check-label" for="credit">Credit card</label>
              </div>
              <div class="form-check">
                <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
                <label class="form-check-label" for="debit">Debit card</label>
              </div>
              <div class="form-check">
                <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required>
                <label class="form-check-label" for="paypal">PayPal</label>
              </div>
            </div>

            <div class="row gy-3">
              <div class="col-md-6">
                <label for="cc-name" class="form-label">Name on card</label>
                <input type="text" class="form-control" id="cc-name" placeholder="" required>
                <small class="text-body-secondary">Full name as displayed on card</small>
                <div class="invalid-feedback">
                  Name on card is required
                </div>
              </div>

              <div class="col-md-6">
                <label for="cc-number" class="form-label">Credit card number</label>
                <input type="text" class="form-control" id="cc-number" placeholder="" required>
                <div class="invalid-feedback">
                  Credit card number is required
                </div>
              </div>

              <div class="col-md-3">
                <label for="cc-expiration" class="form-label">Expiration</label>
                <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                <div class="invalid-feedback">
                  Expiration date required
                </div>
              </div>

              <div class="col-md-3">
                <label for="cc-cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                <div class="invalid-feedback">
                  Security code required
                </div>
              </div>
            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
      </form>
  </div>
  </div>
  </main>


  </div>
  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

  <script src="checkout.js"></script>
</body>

</html>