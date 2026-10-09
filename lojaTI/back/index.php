
<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

require "config.php";

$rota = $_GET["rota"] ?? "teste";

function listarClientes($con) {
    $stmt = $con->query("SELECT * FROM clientes");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function adicionarClientes($con) {

    $cliente = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";
    $telefone = $_POST["telefone"] ?? "";
    $endereco = $_POST["endereco"] ?? "";

    try {
        $stmt = $con->prepare(
            "INSERT INTO clientes (Nome, Email, Telefone, Endereco)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $cliente,
            $email,
            $telefone,
            $endereco
        ]);

        header("Location: ../front/index.html");
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "erro" => "Erro ao cadastrar: " . $e->getMessage()
        ]);
        exit;
    }
}

switch ($rota) {

    case "clientes":
        listarClientes($con);
        break;

    case "adicionarclientes":
        adicionarClientes($con);
        break;

    default:
        echo json_encode([
            "mensagem" => "Back-end funcionando"
        ]);
        break;
}