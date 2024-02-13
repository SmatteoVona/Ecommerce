<!doctype html>
<html lang="ita" data-bs-theme="auto">
<head>
  <script src="assets/js/color-modes.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prodotto · Bootstrap v5.3</title>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <!--<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">-->
  <link href="css/index.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
  <?php
  include "connessione.php";
  $connessione = new mysqli($hostname, $username, $password, "ecommerce");
  if ($connessione->connect_error) {
    die("Connessione fallita: " . $connessione->connect_error);
  }
  ?>
  
  <main>
    <section class="py-5 text-center container">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h1 class="fw-light">Catalogo Prodotti</h1>
          <p class="lead text-body-secondary">Scopri la nostra vasta gamma di prodotti, selezionati per te.</p>
        </div>
      </div>
    </section>

    <div class="album py-5 bg-light">
      <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
          <?php
          // Query per ottenere i dati dei prodotti e delle categorie corrispondenti
          $sql = "SELECT prodotto.nome, prodotto.descrizione, prodotto.prezzo, categoria.nome AS nome_categoria FROM prodotto INNER JOIN categoria ON prodotto.ID_categoria = categoria.ID";
          $result = $connessione->query($sql);

          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  echo '<div class="col">';
                  echo '<div class="card shadow-sm">';
                  // Qui potresti voler includere un'immagine per il prodotto, se ne hai una nel tuo database
                  echo '<div class="card-body">';
                  echo '<h5 class="card-title">'.$row["nome"].'</h5>';
                  echo '<p class="card-text">'.$row["descrizione"].'</p>';
                  echo '<p class="card-text"><small class="text-muted">Categoria: '.$row["nome_categoria"].'</small></p>';
                  echo '<div class="d-flex justify-content-between align-items-center">';
                  echo '<div class="btn-group">';
                  echo '<button type="button" class="btn btn-sm btn-outline-secondary">View</button>';
                  echo '</div>';
                  echo '<small class="text-muted">€ '.$row["prezzo"].'</small>';
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

  <footer class="text-muted py-5">
    <div class="container">
      <p class="float-end mb-1">
        <a href="#">Torna su</a>
      </p>
      <p class="mb-1">E-commerce esempio è &copy; Bootstrap, ma personalizzalo come desideri!</p>
    </div>
  </footer>
  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>