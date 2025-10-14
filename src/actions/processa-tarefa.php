<?php
require_once('../../config/database.php');
require_once('../lib/tarefa.php');

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../public/login.php?erro=Você precisa fazer login para acessar esta página.');
    die();
}
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