<main>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
      <?php
      include "connessione.php";
      $connessione = new mysqli($hostname, $username, $password, "ecommerce");
      if ($connessione->connect_error) {
        die("Connessione fallita: " . $connessione->connect_error);
      }

      $id_prodotto = isset($_GET['id']) ? $_GET['id'] : die('ID Prodotto non specificato.');
      $sql = "SELECT prodotto.ID, prodotto.nome AS nome_prodotto, prodotto.descrizione, prodotto.prezzo, categoria.nome AS nome_categoria, prodotto.percorso_immagine FROM prodotto INNER JOIN categoria ON prodotto.ID_categoria = categoria.ID WHERE prodotto.ID = ?";
      
      if ($stmt = $connessione->prepare($sql)) {
        $stmt->bind_param("i", $id_prodotto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
          echo '<div class="col-md-6 p-lg-5 mx-auto my-5">';
          echo '<img src="../' . $row["percorso_immagine"] . '" class="img-fluid" alt="Immagine Prodotto">';
          echo '<h1 class="display-4 fw-bold">' . $row["nome_prodotto"] . '</h1>';
          echo '<p class="lead">' . $row["descrizione"] . '</p>';
          echo '<p><strong>Categoria:</strong> ' . $row["nome_categoria"] . '</p>';
          echo '<p><strong>Prezzo:</strong> €' . $row["prezzo"] . '</p>';
          // Questo pulsante viene rimosso per avere un'unica azione di aggiunta al carrello
          // echo '<a href="acquisto.php?id=' . $row["ID"] . '" class="btn btn-primary">Acquista ora</a>';
          echo '</div>';
        } else {
          echo "Prodotto non trovato.";
        }

        // Form per aggiungere prodotto e accessori al carrello
        echo '<form action="aggiungi_al_carrello.php" method="POST">';
        echo '<h3>Seleziona accessori aggiuntivi:</h3>';

        // Campo nascosto per passare l'ID del prodotto
        echo '<input type="hidden" name="id_prodotto" value="' . $id_prodotto . '">';

        $sqlAccessori = "SELECT ID, nome, prezzo FROM accessorio";
        $resultAccessori = $connessione->query($sqlAccessori);

        if ($resultAccessori->num_rows > 0) {
          while ($rowAccessori = $resultAccessori->fetch_assoc()) {
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="accessori[]" value="' . $rowAccessori["ID"] . '">';
            echo '<label class="form-check-label">' . $rowAccessori["nome"] . ' - €' . $rowAccessori["prezzo"] . '</label>';
            echo '</div>';
          }
        } else {
          echo "Nessun accessorio disponibile.";
        }

        echo '<button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>';
        echo '</form>';

        $stmt->close();
      } else {
        echo "Errore nella preparazione della query.";
      }
      $connessione->close();
      ?>
    </div>
</main>
 