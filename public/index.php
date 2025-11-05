<?php
session_start();
require_once('../config/database.php');
require_once('../src/lib/tarefa.php');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario_nome = $_SESSION['usuario_nome'];
$id_usuario = $_SESSION['id_usuario'];

$flash_message = null;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a id="btn-logout" class="nav-link" style="cursor: pointer;">
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
                <h5 class="mb-0 text-center">Adicionar Nova Tarefa</h5>
            </div>
            <div class="card-body">
                <form id="formNovaTarefa" method="POST">
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_limite" class="form-label">Data Limite</label>
                                <input type="date" class="form-control" id="data_limite" name="data_limite" required>
                            </div>
                        </div>

                        <div class="col-8">
                            <div class="mb-4">
                                <label for="descricao" class="form-label">Descrição (Opcional)</label>
                                <textarea class="form-control h-100" id="descricao" name="descricao"></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-lg"></i> Adicionar Tarefa
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                <h5 class="mb-2 mb-md-0">Minhas Tarefas</h5>

                <form id="formFiltroTarefas" class="d-flex flex-column flex-md-row gap-2">
                    <input type="text" id="filtroBusca" name="busca" class="form-control"
                        placeholder="Buscar por título...">

                    <select id="filtroStatus" name="filtro_status" class="form-select">
                        <option value="">Todos os status</option>
                        <option value="PENDENTE">Pendente</option>
                        <option value="CONCLUIDA">Concluída</option>
                    </select>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Título</th>
                                <th>Descrição</th>
                                <th>Criada em</th>
                                <th>Data Limite</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="lista-tarefas-body">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center" id="paginacao-container">
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalEditarTarefa" tabindex="-1" aria-labelledby="modalEditarTarefaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarTarefaLabel">Editar Tarefa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarTarefa"
                        action="../src/actions/processa-tarefa.php?acao=editar&id=<?php echo $tarefa['id']; ?>"
                        method="POST">
                        <input type="hidden" name="acao" value="editar">
                        <input type="hidden" name="id_tarefa" id="edit_id_tarefa">

                        <div class="mb-3">
                            <label for="edit_titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="edit_titulo" name="titulo" required>
                        </div>
                        <div class="d-flex flex-column flex-md-row w-100 gap-md-3">
                            <div class="mb-">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="PENDENTE">Pendente</option>
                                    <option value="CONCLUIDA">Concluída</option>
                                </select>
                            </div>
                            <div class="mb-3">
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

    <div class="toast-container position-fixed top-0 center p-3">
        <div id="feedbackToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toast-title">Notificação</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-body">
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(() => {
            const tarefasPorPagina = 5;

            function showToast(message, type = 'success') {
                const toastElement = document.getElementById('feedbackToast');
                if (toastElement) {
                    const toast = new bootstrap.Toast(toastElement, {
                        delay: 2000
                    });

                    const toastTitle = document.getElementById('toast-title');
                    const toastBody = document.getElementById('toast-body');

                    toastElement.classList.remove('bg-success', 'bg-danger');

                    if (type === 'success') {
                        toastTitle.innerText = 'Sucesso!';
                        toastElement.classList.add('bg-success');
                    } else {
                        toastTitle.innerText = 'Erro!';
                        toastElement.classList.add('bg-danger');
                    }

                    toastBody.innerText = message;
                    toast.show();

                } else {
                    console.error('Elemento #feedbackToast não foi encontrado');
                    alert(message);
                }
            }

            function renderizarPaginacao(totalTarefas, paginaAtual) {
                const container = $('#paginacao-container');
                container.empty();

                const totalPaginas = Math.ceil(totalTarefas / tarefasPorPagina);

                if (totalPaginas <= 1) return;

                let paginacaoHtml = '<ul class="pagination">';

                for (let i = 1; i <= totalPaginas; i++) {
                    let activeClass = (i === paginaAtual) ? 'active' : '';
                    paginacaoHtml += `
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
                }

                paginacaoHtml += '</ul>';
                container.html(paginacaoHtml);
            }

            function listarTarefas(page = 1) {

                let busca = $('#buscaTitulo').val();
                let status = $('#filtroStatus').val();

                let url = `../src/actions/processa-tarefa.php?acao=listar&page=${page}`;
                if (busca) url += '&busca=' + encodeURIComponent(busca);
                if (status) url += '&filtro_status=' + encodeURIComponent(status);
                
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: (response) => {
                        if (response.sucesso && response.tarefas) {
                            let tabelaBody = $('#lista-tarefas-body');
                            tabelaBody.empty();

                            if (response.tarefas.length === 0) {
                                tabelaBody.html('<tr><td colspan="6" class="text-center">Nenhuma tarefa encontrada. Crie uma nova tarefa!</td></tr>');
                                return;
                            }

                            $.each(response.tarefas, function (index, tarefa) {
                                let dataCriacaoFormatada = '';
                                console.log(tarefa.data_de_criacao)
                                if (tarefa.data_de_criacao) {
                                    const dataParts = tarefa.data_de_criacao.split(' ')[0];
                                    const parts = dataParts.split('-');
                                    if (parts.length === 3) {
                                        dataCriacaoFormatada = `${parts[2]}/${parts[1]}/${parts[0]}`;
                                    }
                                }

                                let dataLimiteFormatada = '';
                                let dataLimiteModal = '';
                                if (tarefa.data_limite) {
                                    const dataParts = tarefa.data_limite.split(' ')[0];
                                    const parts = dataParts.split('-');
                                    if (parts.length === 3) {
                                        dataLimiteFormatada = `${parts[2]}/${parts[1]}/${parts[0]}`;
                                        dataLimiteModal = dataParts;
                                    }
                                }

                                let statusBadge = (tarefa.status === 'CONCLUIDA') ?
                                    '<span class="badge bg-success text-bg-primary">Concluída</span>' :
                                    '<span class="badge text-bg-warning">Pendente</span>';

                                let tituloHtml = (tarefa.status === 'CONCLUIDA') ?
                                    `<s>${tarefa.titulo}</s>` :
                                    tarefa.titulo;

                                let botaoConcluir = (tarefa.status === 'PENDENTE') ?
                                    `<a href="../src/actions/processa-tarefa.php?acao=concluir&id=${tarefa.id}" class="btn btn-success btn-sm btn-concluir-ajax" title="Marcar como Concluída">
                                        <i class="bi bi-check-lg"></i>
                                    </a>` : '';

                                let botaoEditar = `
                                <button type="button" class="btn btn-warning btn-sm btn-editar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarTarefa"
                                        data-id="${tarefa.id}"
                                        data-titulo="${tarefa.titulo}"
                                        data-descricao="${tarefa.descricao}"
                                        data-status="${tarefa.status}"
                                        data-data_limite="${dataLimiteModal}" 
                                        title="Editar">
                                <i class="bi bi-pencil-fill"></i>
                                </button>`;

                                let botaoExcluir = `
                                <a href="../src/actions/processa-tarefa.php?acao=excluir&id=${tarefa.id}" class="btn btn-danger btn-sm btn-excluir-ajax" title="Excluir">
                                    <i class="bi bi-trash-fill"></i>
                                </a>`;

                                let htmlLinha = `
                                <tr data-tarefa-id="${tarefa.id}">
                                    <td class="align-middle">${statusBadge}</td>
                                    <td class="align-middle">${tituloHtml}</td>
                                    <td class="text-truncate align-middle" 
                                        style="max-width: 200px;"
                                    >
                                        ${tarefa.descricao}
                                    </td>
                                    <td class="align-middle">${dataCriacaoFormatada}</td>
                                    <td class="align-middle">${dataLimiteFormatada}</td>
                                    <td class="text-center align-middle d-flex justify-content-center gap-2">
                                        ${botaoConcluir}
                                        ${botaoEditar}
                                        ${botaoExcluir}
                                    </td>
                                </tr>
                            `;
                                tabelaBody.append(htmlLinha);
                            });
                        }
                        renderizarPaginacao(response.total_tarefas, response.pagina_atual);
                    },
                    error: () => {
                        showToast('Erro ao carregar a lista de tarefas.', 'danger');
                    }
                });
            }

            $('#lista-tarefas-body').on('click', '.btn-concluir-ajax', function (e) {

                e.preventDefault();

                let botaoClicado = $(this);
                let url = botaoClicado.attr('href');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.sucesso) {
                            let linhaDaTarefa = botaoClicado.closest('tr');

                            linhaDaTarefa.find('.badge').removeClass('bg-warning').addClass('bg-success').text('Concluída');

                            let tituloCell = linhaDaTarefa.find('td:nth-child(2)');
                            tituloCell.html('<s>' + tituloCell.text() + '</s>');

                            botaoClicado.remove();

                            showToast(response.mensagem, 'success');
                        } else {
                            showToast(response.mensagem, 'danger');
                        }
                    },
                    error: function () {
                        showToast('Erro de comunicação com o servidor.', 'danger');
                    }
                });
            });

            $('#formNovaTarefa').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);

                $.ajax({
                    url: '../src/actions/processa-tarefa.php?acao=criar',
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: (response) => {
                        if (response.sucesso) {
                            showToast(response.mensagem, 'success');
                            form[0].reset();
                            listarTarefas(1);
                        } else {
                            showToast(response.mensagem, 'danger');
                        }
                    },
                    error: () => {
                        showToast('Erro ao enviar a requisição.', 'danger');
                    }
                });
            });

            $('#formEditarTarefa').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);

                $.ajax({
                    url: '../src/actions/processa-tarefa.php?acao=editar',
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.sucesso) {
                            $('#modalEditarTarefa').modal('hide');
                            showToast(response.mensagem, 'success');
                            listarTarefas();
                        } else {
                            showToast(response.mensagem, 'danger');
                        }
                    },
                    error: () => {
                        showToast('Erro ao enviar a requisição.', 'danger');
                    }
                });
            });

            $('#modalEditarTarefa').on('show.bs.modal', function (e) {
                let button = $(e.relatedTarget);

                let id = button.data('id');
                let titulo = button.data('titulo');
                let descricao = button.data('descricao');
                let status = button.data('status');
                let data_limite = button.data('data_limite');

                let dataLimiteFormatada = '';
                if (data_limite) {
                    dataLimiteFormatada = data_limite.split(' ')[0];
                }

                let modal = $(this);
                modal.find('#edit_id_tarefa').val(id);
                modal.find('#edit_titulo').val(titulo);
                modal.find('#edit_descricao').val(descricao);
                modal.find('#edit_data_limite').val(dataLimiteFormatada);
                modal.find('#edit_status').val(status);
            });

            $('#lista-tarefas-body').on('click', '.btn-excluir-ajax', function (e) {
                e.preventDefault();

                let botaoClicado = $(this);
                let url = botaoClicado.attr('href');
                let linhaDaTarefa = botaoClicado.closest('tr');

                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "Esta ação não poderá ser desfeita!",
                    theme: 'dark',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                if (response.sucesso) {
                                    let tabelaBody = linhaDaTarefa.closest('tbody');

                                    linhaDaTarefa.fadeOut(500, function () {
                                        $(this).remove();

                                        let linhasRestantes = tabelaBody.find('tr').length;

                                        if (linhasRestantes === 0) {
                                            tabelaBody.append('<tr><td colspan="6" class="text-center">Nenhuma tarefa encontrada. Crie uma nova tarefa!</td></tr>');
                                        }
                                    });

                                    showToast(response.mensagem, 'success');
                                } else {
                                    showToast(response.mensagem, 'danger');
                                }
                            },
                            error: function () {
                                showToast('Erro de comunicação com o servidor.', 'danger');
                            }
                        });
                    }
                })
            });

            $('#btn-logout').on('click', function (e) {
                e.preventDefault();

                $.ajax({
                    url: '../src/actions/processa-logout.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.sucesso) {
                            showToast(response.mensagem, 'success');
                            setTimeout(() => {
                                window.location.href = 'login.php';
                            }, 2000);
                        }
                    },
                    error: function () {
                        window.location.href = 'login.php';
                    }
                });
            });

            let debounceTimer;
            $('#buscaTitulo').on('keyup', function (e) {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {
                    listarTarefas();
                }, 500)
            });

            $('#filtroStatus').on('change', function () {
                listarTarefas()
            });

            $('#paginacao-container').on('click', '.page-link', function (e) {
                e.preventDefault();
                let page = $(this).data('page');
                listarTarefas(page);
            });

            listarTarefas(1)
        })
    </script>
</body>

</html>