<?php
function criarTarefa($db, $titulo, $descricao, $data_limite, $id_usuario){
    $sql = 'INSERT INTO tarefas (titulo, descricao, data_limite, id_usuario) VALUES (?,?,?,?)';

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $titulo, $descricao, $data_limite, $id_usuario);
    
    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }
    return true;
}

function buscarTarefasPorUsuario($db, $id_usuario){
    $sql = 'SELECT * FROM tarefas WHERE id_usuario = ? ORDER BY data_de_criacao DESC';

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    $tarefas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    return $tarefas;
}

function concluirTarefa($db, $id_tarefa, $id_usuario){
    $sql = "UPDATE tarefas SET status = 'concluida' WHERE id = ? AND id_usuario = ?";
    
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_tarefa, $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }

    return true;
}

function excluirTarefa($db, $id_tarefa, $id_usuario){
    $sql = "DELETE FROM tarefas WHERE id = ? AND id_usuario = ?";
    
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_tarefa, $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }

    return true;
}

function editarTarefa($db, $id_tarefa, $titulo, $descricao, $status, $data_limite, $id_usuario){
    $sql = 'UPDATE tarefas SET titulo = ?, descricao = ?, status = ?, data_limite = ? WHERE id = ? AND id_usuario = ?';

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssssii", $titulo, $descricao, $status,  $data_limite, $id_tarefa, $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }

    return true;
}