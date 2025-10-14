<?php
require_once('../../config/database.php');
require_once('../lib/usuario.php');

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

if (!verificarSenhaRegistro($senha, $confirmar_senha)) {
    header('Location: ../../public/registro.php?erro=As senhas não coincidem!');
    die();
}

if (verificarUsuarioExistente($db, $email)) {
    header('Location: ../../public/registro.php?erro=O e-mail informado já está cadastrado!');
    die();
}

try {
    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);
    regitrarUsuario($db, $nome, $email, $hash_senha);
    header('Location: ../public/login.php?sucesso=Cadastro realizado com sucesso!');
    die();
} catch (mysqli_sql_exception $erro) {
    header('Location:../../public/registro.php?erro=Ocorreu um erro no servidor. Tente novamente.');
    die();
}
