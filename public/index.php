<?php

require_once('../config/database.php');
require_once('../src/lib/tarefa.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php?erro=Você precisa fazer login para acessar esta página.');
    die();
}

$usuario_nome = $_SESSION['usuario_nome'];
$id_usuario = $_SESSION['id_usuario'];

$lista_de_tarefas = buscarTarefasPorUsuario($db, $id_usuario);

$mensagem_erro = null;
if (isset($_GET['erro'])) {
    $mensagem_erro = htmlspecialchars($_GET['erro']);
}

$mensagem_sucesso = null;
if (isset($_GET['sucesso'])) {
    $mensagem_sucesso = htmlspecialchars($_GET['sucesso']);
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Painel de Tarefas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person-circle"></i> Olá, <?php echo htmlspecialchars($usuario_nome); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../src/actions/processa-logout.php">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container mt-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Adicionar Nova Tarefa</h5>
            </div>
            <div class="card-body">
                <form action="../src/actions/processa-tarefa.php?acao=criar" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_limite" class="form-label">Data Limite</label>
                            <input type="date" class="form-control" id="data_limite" name="data_limite">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição (Opcional)</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Adicionar Tarefa
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Minhas Tarefas</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Título</th>
                            <th>Criada em</th>
                            <th>Data Limite</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_de_tarefas)) : ?>
                            <tr>
                                <td colspan="4" class="text-center">Nenhuma tarefa encontrada. Crie sua primeira tarefa acima!</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($lista_de_tarefas as $tarefa) : ?>
                                <tr>
                                    <td>
                                        <?php if ($tarefa['status'] == 'CONCLUIDA') : ?>
                                            <span class="badge bg-success">Concluída</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning">Pendente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($tarefa['status'] == 'CONCLUIDA') : ?>
                                            <s><?php echo htmlspecialchars($tarefa['titulo']); ?></s>
                                        <?php else : ?>
                                            <?php echo htmlspecialchars($tarefa['titulo']); ?>
                                        <?php endif; ?>
                                    </td>
                                      <td>
                                        <?php
                                        if (!empty($tarefa['data_de_criacao'])) {
                                            echo date('d/m/Y', strtotime($tarefa['data_de_criacao']));
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (!empty($tarefa['data_limite'])) {
                                            echo date('d/m/Y', strtotime($tarefa['data_limite']));
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($tarefa['status'] == 'PENDENTE') : ?>
                                            <a href="../src/actions/processa-tarefa.php?acao=concluir&id=<?php echo $tarefa['id']; ?>" 
                                                class="btn btn-success btn-sm" 
                                                title="Marcar como Concluída"
                                            >  
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                        <?php endif; ?>
                                       <button
                                            type="button"
                                            class="btn btn-warning btn-sm btn-editar"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarTarefa"
                                            data-id="<?php echo $tarefa['id']; ?>"
                                            data-titulo="<?php echo htmlspecialchars($tarefa['titulo']); ?>"
                                            data-descricao="<?php echo htmlspecialchars($tarefa['descricao']); ?>"
                                            data-data_limite="<?php echo !empty($tarefa['data_limite']) ? date('Y-m-d', strtotime($tarefa['data_limite'])) : ''; ?>"
                                            data-status = "<?php echo $tarefa['status']; ?>"
                                            title="Editar"
                                       >
                                            <i class="bi bi-pencil-fill"></i>
                                       </button>
                                        <a href="../src/actions/processa-tarefa.php?acao=excluir&id=<?php echo $tarefa['id']; ?>" class="btn btn-danger btn-sm" title="Excluir"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalEditarTarefa" tabindex="-1" aria-labelledby="modalEditarTarefaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditarTarefaLabel">Editar Tarefa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarTarefa" action="../src/actions/processa-tarefa.php?acao=editar&id=<?php echo $tarefa['id']; ?>" method="POST">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id_tarefa" id="edit_id_tarefa">

                <div class="mb-3">
                    <label for="edit_titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="edit_titulo" name="titulo" required>
                </div>
                <div class="d-flex w-100 gap-5">
                    <div class="mb-3 w-100 ">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="PENDENTE">Pendente</option>
                            <option value="CONCLUIDA">Concluída</option>
                        </select>
                    </div>
                    <div class="mb-3 w-100 ">
                        <label for="edit_data_limite" class="form-label">Data Limite</label>
                        <input type="date" class="form-control" id="edit_data_limite" name="data_limite">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="edit_descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" id="edit_descricao" name="descricao" rows="3"></textarea>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditarTarefa" class="btn btn-primary">Salvar Alterações</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $('#modalEditarTarefa').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget); 
     
        let id = button.data('id');
        let titulo = button.data('titulo');
        let descricao = button.data('descricao');
        let status = button.data('status');
        let data_limite = button.data('data_limite');

        let modal = $(this);
        modal.find('#edit_id_tarefa').val(id);
        modal.find('#edit_titulo').val(titulo);
        modal.find('#edit_descricao').val(descricao);
        modal.find('#edit_data_limite').val(data_limite);
        modal.find('#edit_status').val(status);
    });
    </script>
</body>
</html>