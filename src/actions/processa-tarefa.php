<?php
require_once('../../config/database.php');
require_once('../lib/tarefa.php');

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../public/login.php?erro=Você precisa fazer login para acessar esta página.');
    die();
};

$id_usuario = $_SESSION['id_usuario'];

$acao = $_GET['acao'] ?? null;

if ($acao == 'criar') {
    $titulo = htmlspecialchars($_POST['titulo']);
    $data_limite = $_POST['data_limite'];
    $descricao = htmlspecialchars($_POST['descricao']);

    if (criarTarefa($db, $titulo, $descricao, $data_limite, $id_usuario)) {
        header("Location: ../../public/index.php?sucesso=Nova tarefa criada com sucesso!");
        die();
    } else {
        header("Location: ../../public/index.php?erro=Erro ao criar nova tarefa!");
        die();
    }
} 
else if ($acao == 'concluir') {
    $id_tarefa = $_GET['id'] ?? null;

    if(concluirTarefa($db, $id_tarefa, $id_usuario)){
        header("Location: ../../public/index.php?sucesso=Tarefa concluída!");
        die();
    }else {
        header("Location: ../../public/index.php?erro=Você não tem permissão para concluir esta tarefa!");
        die();
    }
}
else if($acao == 'excluir'){
    $id_tarefa = $_GET['id'] ?? null;

    if(excluirTarefa($db, $id_tarefa, $id_usuario)){
        header("Location: ../../public/index.php?sucesso=Tarefa excluida!");
        die();
    } else {
        header("Location: ../../public/index.php?erro=Você não tem permissão para excluir esta tarefa!");
        die();
    }
}
else if($acao == 'editar'){
    $id_tarefa = $_POST['id_tarefa'];
    $titulo = htmlspecialchars($_POST['titulo']);
    $descricao = htmlspecialchars($_POST['descricao']);
    $status = htmlspecialchars($_POST['status']);
    $data_limite = $_POST['data_limite'];

    if(editarTarefa($db, $id_tarefa, $titulo, $descricao, $status, $data_limite, $id_usuario)){
        header("Location: ../../public/index.php?sucesso=Tarefa atualizada com sucesso!");
        die();
    } else {
        header("Location: ../../public/index.php?erro=Você não tem permissão para editar esta tarefa!");
        die();
    }
}