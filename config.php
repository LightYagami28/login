<?php

// Configurazione della connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "test";

// Connessione al database con controllo degli errori
try {
    // Creazione della connessione
    $conn = new mysqli($servername, $username, $password, $db_name);

    // Verifica della connessione
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita: " . $conn->connect_error);
    }

    // Connessione riuscita
    echo "Connessione al database riuscita!";

} catch (Exception $e) {
    // Gestione degli errori
    echo "Errore di connessione: " . $e->getMessage();
}

// Chiusura della connessione
$conn->close();

?>
