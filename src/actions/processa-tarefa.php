<?php
header('Content-Type: application/json; charset=utf-8');
require_once('../../config/database.php');
require_once('../lib/tarefa.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você precisa estar logado para realizar esta ação.']);
    exit;
}
;

$id_usuario = $_SESSION['id_usuario'];

$acao = $_GET['acao'] ?? null;

try {
    switch ($acao) {
        case 'criar':
            $titulo = htmlspecialchars($_POST['titulo']);
            $data_limite = $_POST['data_limite'];
            $descricao = htmlspecialchars($_POST['descricao']);

            if (criarTarefa($db, $titulo, $descricao, $data_limite, $id_usuario)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Nova tarefa criada com sucesso!']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao criar nova tarefa!']);
            }
            break;

        case 'concluir':
            $id_tarefa = $_GET['id'] ?? null;

            if (concluirTarefa($db, $id_tarefa, $id_usuario)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Tarefa concluída!']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Você não tem permissão para concluir esta tarefa!']);
            }
            break;

        case 'excluir':
            $id_tarefa = $_GET['id'] ?? null;

            if (excluirTarefa($db, $id_tarefa, $id_usuario)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Tarefa excluída!']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Você não tem permissão para excluir esta tarefa!']);
            }
            break;

        case 'editar':
            $id_tarefa = $_POST['id_tarefa'];
            $titulo = htmlspecialchars($_POST['titulo']);
            $descricao = htmlspecialchars($_POST['descricao']);
            $status = htmlspecialchars($_POST['status']);
            $data_limite = $_POST['data_limite'];

            if (editarTarefa($db, $id_tarefa, $titulo, $descricao, $status, $data_limite, $id_usuario)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Tarefa atualizada com sucesso!']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Você não tem permissão para editar esta tarefa!']);
            }
            break;

        case 'listar':
            $buscaTitulo = $_GET['busca'] ?? null;
            $filtro_status = $_GET['filtro_status'] ?? null;
            $page = (int) ($_GET['page'] ?? 1);
            $limit = 5;

            $resultado = buscarTarefasPorUsuario($db, $id_usuario, $buscaTitulo, $filtro_status, $page, $limit);
            echo json_encode([
                'sucesso' => true, 
                'tarefas' => $resultado['tarefas'],
                'total_tarefas' => $resultado['total_tarefas'],
                'pagina_atual' => $page,
                'limit' => $limit
            ]);
            break;

        default:
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida.']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
}
exit;
