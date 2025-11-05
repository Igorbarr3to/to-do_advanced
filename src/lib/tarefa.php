<?php
function criarTarefa($db, $titulo, $descricao, $data_limite, $id_usuario)
{
    $sql = 'INSERT INTO tarefas (titulo, descricao, data_limite, id_usuario) VALUES (?,?,?,?)';

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $titulo, $descricao, $data_limite, $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }
    return true;
}

function buscarTarefasPorUsuario($db, $id_usuario, $buscaTitulo = null, $filtro_status = null, $page = 1, $limit = 5)
{
    $sql = 'FROM tarefas WHERE id_usuario = ?';
    $params = [$id_usuario];
    $types = "i";

    if (!empty($buscaTitulo)) {
        $sql .= " AND titulo LIKE ?";
        $params[] = "%" . $buscaTitulo . "%";
        $types .= "s";
    }
    
    if (!empty($filtro_status) && ($filtro_status == 'PENDENTE' || $filtro_status == 'CONCLUIDA')) {
        $sql .= " AND status = ?";
        $params[] = $filtro_status;
        $types .= "s";
    }

    $sqlTotal = "SELECT COUNT(*) AS total " . $sql;
    $stmtTotal = mysqli_prepare($db, $sqlTotal);

    mysqli_stmt_bind_param($stmtTotal, $types, ...$params);
    mysqli_stmt_execute($stmtTotal);
    $total_tarefas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtTotal))['total'];

    $offset = ($page - 1) * $limit;

    $sqlTarefas = "SELECT * " . $sql . " ORDER BY data_de_criacao DESC LIMIT ? OFFSET ?";
    $types .= "ii";
    $params[] = $limit;
    $params[] = $offset;


    $stmtTarefas = mysqli_prepare($db, $sqlTarefas);
    mysqli_stmt_bind_param($stmtTarefas, $types, ...$params);
    mysqli_stmt_execute($stmtTarefas);
    $resultado = mysqli_stmt_get_result($stmtTarefas);
    $tarefas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    return ['tarefas' => $tarefas, 'total_tarefas' => $total_tarefas];
}

function concluirTarefa($db, $id_tarefa, $id_usuario)
{
    $sql = "UPDATE tarefas SET status = 'concluida' WHERE id = ? AND id_usuario = ?";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_tarefa, $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }

    return true;
}

function excluirTarefa($db, $id_tarefa, $id_usuario)
{
    $sql = "DELETE FROM tarefas WHERE id = ? AND id_usuario = ?";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_tarefa, $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }

    return true;
}

function editarTarefa($db, $id_tarefa, $titulo, $descricao, $status, $data_limite, $id_usuario)
{
    $sql = 'UPDATE tarefas SET titulo = ?, descricao = ?, status = ?, data_limite = ? WHERE id = ? AND id_usuario = ?';

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssssii", $titulo, $descricao, $status, $data_limite, $id_tarefa, $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }

    return true;
}