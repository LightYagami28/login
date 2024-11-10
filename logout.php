<?php

// Avvia la sessione
session_start();

// Rimuovi tutte le variabili di sessione
$_SESSION = array();

// Se si desidera distruggere completamente la sessione, anche il cookie di sessione deve essere cancellato.
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Distruggi la sessione
session_destroy();

// Reindirizza l'utente alla pagina di login
header('Location: login.php');
exit();

?>
