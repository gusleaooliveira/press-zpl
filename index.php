<?php
define('CONNECTION_PATH', './functions/connection.php');
define('LIST_ITEMS_PATH', './functions/list_items.php');

if (file_exists(CONNECTION_PATH)) {
    require_once(CONNECTION_PATH);
} else {
    die("Erro: O arquivo de conexão não foi encontrado.");
}

if (file_exists(LIST_ITEMS_PATH)) {
    require_once(LIST_ITEMS_PATH);
} else {
    die("Erro: O arquivo de listagem de itens não foi encontrado.");
}

?>