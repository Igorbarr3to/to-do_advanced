<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once('../../config/database.php');
require_once('../lib/usuario.php');

$email = $_POST['email'];
$senha = $_POST['senha'];

$usuario_encontrado = buscarUsuarioPorEmail($db, $email);

if($usuario_encontrado && password_verify($senha, $usuario_encontrado['senha'])){
    $_SESSION['id_usuario'] = $usuario_encontrado['id'];
    $_SESSION['usuario_nome'] = $usuario_encontrado['nome'];
    echo json_encode(['sucesso' => true, 'mensagem' => 'Login realizado com sucesso!']);
}
else {
    http_response_code(401);
   echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou senha inválidos, verifique e tente novamente!']);
}