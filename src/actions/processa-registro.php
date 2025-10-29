<?php
header('Content-Type: application/json; charset=utf-8');
require_once('../../config/database.php');
require_once('../lib/usuario.php');

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

if (!verificarSenhaRegistro($senha, $confirmar_senha)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'As senhas não coincidem!']);
}

if (verificarUsuarioExistente($db, $email)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail já cadastrado!']);
}

try {
    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);
    regitrarUsuario($db, $nome, $email, $hash_senha);
    echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso!']);
} catch (mysqli_sql_exception $erro) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Ocorreu um erro no servidor. Tente novamente.']);
}
