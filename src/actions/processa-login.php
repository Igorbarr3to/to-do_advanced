<?php
require_once('../../config/database.php');
require_once('../lib/usuario.php');

$email = $_POST['email'];
$senha = $_POST['senha'];

$usuario_encontrado = buscarUsuarioPorEmail($db, $email);

if($usuario_encontrado && password_verify($senha, $usuario_encontrado['senha'])){
    session_start();
    $_SESSION['id_usuario'] = $usuario_encontrado['id'];
    $_SESSION['usuario_nome'] = $usuario_encontrado['nome'];
    header("Location: ../../public/index.php");
    die();
}
else {
    header("Location: ../../public/login.php?erro=E-mail ou senha inválidos, verifique e tente novamente!");
    die();
}