<?php

function verificarSenha($senha, $confirmar_senha)
{
    if ($senha != $confirmar_senha) {
        return false;
    }
    return true;
}

function regitrarUsuario($db, $nome, $email, $hash_senha)
{
    $registrar_usuario = 'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)';
    $stmt = mysqli_prepare($db, $registrar_usuario);
    mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $hash_senha);

    if (mysqli_stmt_execute($stmt)) {
        return true;
    }
    return false;
}

function verificarUsuarioExistente($db, $email)
{
    $verificar_usuario = 'SELECT id FROM usuarios WHERE email = ?';
    $stmt = mysqli_prepare($db, $verificar_usuario);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        return true;
    }
    return false;
}
