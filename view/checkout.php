<!doctype html>
<html lang="it" data-bs-theme="auto">

<head>
  <script src="../assets/js/color-modes.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carrello</title>

  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/checkout.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

  <?php
  session_start();
  include "../php/connessione.php";
  $connessione = new mysqli($hostname, $username, $password, "ecommerce");

  if ($connessione->connect_error) {
    die("Connessione fallita: " . $connessione->connect_error);
  }


  if (isset($_SESSION['user_id'])) {
    $idCliente = $_SESSION['user_id'];

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

      $cartItems = [];
      $total = 0;
      
      if ($idCarrello) {
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
          pc.ID_carrello = ?;";
      
        if ($stmt = $connessione->prepare($sqlProdottiCarrello)) {
          $stmt->bind_param("i", $idCarrello);
          $stmt->execute();
          $result = $stmt->get_result();
      
          while ($row = $result->fetch_assoc()) {
            $prodotto_id = $row['prodotto_id'];
            if (!isset($cartItems[$prodotto_id])) {
              $cartItems[$prodotto_id] = [
                'nome_prodotto' => $row['nome_prodotto'],
                'descrizione_prodotto' => $row['descrizione_prodotto'],
                'prezzo_prodotto' => $row['prezzo_prodotto'],
                'accessori' => []
              ];
              $total += $row['prezzo_prodotto'];
            }
            if ($row['accessorio_id']) {
              $cartItems[$prodotto_id]['accessori'][] = [
                'nome_accessorio' => $row['nome_accessorio'],
                'prezzo_accessorio' => $row['prezzo_accessorio']
              ];
              $total += $row['prezzo_accessorio'];
            }
          }
          $stmt->close();
        }
      }
    }

  } else {
    echo "Utente non autenticato.";
  }

  $connessione->close();
  ?>

  <div class="container">
    <main>
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
      <h2>Checkout form</h2>
      <p class="lead">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
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
  <span>Totale (€ EURO)</span>
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