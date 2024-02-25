<!doctype html>
<html lang="it" data-bs-theme="auto">

<head>
  <script src="../assets/js/color-modes.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ecommerce</title>

  <link href="../css/index.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
  <?php
  include "../php/connessione.php";
  $connessione = new mysqli($hostname, $username, $password, "ecommerce");
  if ($connessione->connect_error) {
    die("Connessione fallita: " . $connessione->connect_error);
  }
  ?>

  <main>
    <header class="p-3 text-bg-dark">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

          <!-- ? indica l'inizio della query e a seguire ci sono i valori inviati -->
          <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="?" class="nav-link px-2 text-secondary">Tutti i Prodotti</a></li>
            <li><a href="?categoria=Soggiorno" class="nav-link px-2 text-white">Soggiorno</a></li>
            <li><a href="?categoria=Camera" class="nav-link px-2 text-white">Camera</a></li>
            <li><a href="?categoria=Ingresso" class="nav-link px-2 text-white">Ingresso</a></li>
            <li><a href="?categoria=Terrazza" class="nav-link px-2 text-white">Terrazza</a></li>
          </ul>


          <?php
          session_start();

          if (isset($_SESSION['user_id'])) {
            echo '<a href="logout.php" class="btn btn-outline-danger me-2">Logout</a>';
            echo '<a href="checkout.php" class="btn btn-outline-light me-2">Carrello</a>';

          } else {
            echo '<a href="login.php" class="btn btn-outline-light me-2">Login</a>';
            echo '<a href="registrazione.php" class="btn btn-warning">Sign-up</a>';
          }
          ?>
        </div>
      </div>
      <section class="py-5 text-center container">
        <div class="row py-lg-5">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Catalogo Prodotti</h1>
            <p class="fw-light">Scopri la nostra vasta gamma di prodotti, selezionati per te.</p>
          </div>
        </div>
      </section>

      <div class="py-5 bg-light">
        <div class="container">
          <div class="row row-cols-3 g-3">
            <?php
            include "../php/connessione.php";
            $connessione = new mysqli($hostname, $username, $password, "ecommerce");
            if ($connessione->connect_error) {
              die("Connessione fallita: " . $connessione->connect_error);
            }

            //questo è un if dove $categoria = $connessione->real_escape_string($_GET['categoria']) se è vero e $categoria = null se falso
           //real_escape_string è un oggetto di connessione del database che sanifica il valore di categoria per evitare injection sql rimuovendo i caratteri speciali
            $categoria = isset($_GET['categoria']) ? $connessione->real_escape_string($_GET['categoria']) : null;

            $sql = "SELECT prodotto.ID, prodotto.nome AS nome_prodotto, prodotto.descrizione, prodotto.prezzo, categoria.nome AS nome_categoria FROM prodotto INNER JOIN categoria ON prodotto.ID_categoria = categoria.ID";

            if ($categoria) {
              // il ? servirà al bind_param per definire il tipo di valore accettabile
              $sql .= " WHERE categoria.nome = ?";
            }

            //preprare la query aumenta la sicurezza contro le injection e l'efficenza in caso vengano ripetute frequentemente
            $stmt = $connessione->prepare($sql);

            if ($categoria) {
              //bind_param serve ad assegnare una variabile di un tipo definito ad stmt, s sta per stringa (i per intero, b per binario d per decimale...)
              //aiuta ad aumentare la sicurezza rendendo più complesso fare sql injection
              $stmt->bind_param("s", $categoria);
            }
            //execute esegue la query
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              //fetch_assoc trasforma ogni risultato in un array associativo
              while ($row = $result->fetch_assoc()) {
                echo '<div class="col">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row["nome_prodotto"] . '</h5>';
                echo '<p class="card-text">' . $row["descrizione"] . '</p>';
                echo '<p class="card-text"><small class="text-muted">Categoria: ' . $row["nome_categoria"] . '</small></p>';
                echo '<div class="d-flex justify-content-between align-items-center">';
                echo '<div class="btn-group">';
                echo '<a href="dettaglio_prodotto.php?id=' . $row["ID"] . '" class="btn btn-sm btn-outline-secondary">Dettagli  </a>';
                echo '</div>';
                echo '<small class="text-muted">€ ' . $row["prezzo"] . '</small>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
              }
            } else {
              echo "Nessun prodotto trovato.";
            }
            $connessione->close();
            ?>
          </div>
        </div>
      </div>
  </main>

</body>

</html>