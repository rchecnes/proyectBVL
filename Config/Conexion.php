<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta
use Dotenv\Dotenv;

function getConexion(){

	$dotenvPath = __DIR__ . '/.env';
    if (!file_exists($dotenvPath)) {
        die("El archivo .env no se encuentra en la ruta especificada: " . $dotenvPath);
    }

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

	$name_ht = $_ENV['DB_SERVER'];
    $user_db = $_ENV['DB_USERNAME'];
    $pass_db = $_ENV['DB_PASSWORD'];
    $name_db = $_ENV['DB_DATABASE'];

    $link = mysqli_connect($name_ht, $user_db, $pass_db, $name_db);

	if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $link;
}
?>