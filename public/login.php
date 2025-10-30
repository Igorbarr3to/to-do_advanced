<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="col-10 col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h3>Entre com a sua Conta</h3>
                </div>
                <div class="card-body">
                    <form method="post" id="formLogin">
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail"
                                required />
                        </div>

                        <div class="mb-3">
                            <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha"
                                required />
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100">
                                Entrar!
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>Ainda não tem uma conta? <a href="registro.php" class="link-light">Faça o registro
                            aqui</a></small>
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
            function showToast(message, type = 'success') {
                const toastElement = document.getElementById('feedbackToast');
                if (toastElement) {
                    const toast = new bootstrap.Toast(toastElement, {
                        delay: 2000
                    });

                    const toastTitle = document.getElementById('toast-title');
                    const toastBody = document.getElementById('toast-body');

                    toastElement.classList.remove('bg-success-subtle', 'bg-danger-subtle');
                    if (type === 'success') {
                        toastTitle.innerText = 'Sucesso!';
                        toastElement.classList.add('bg-success-subtle');
                    } else {
                        toastTitle.innerText = 'Erro!';
                        toastElement.classList.add('bg-danger-subtle');
                    }

                    toastBody.innerText = message;
                    toast.show();

                } else {
                    console.error('Elemento #feedbackToast não foi encontrado');
                    alert(message);
                }
            }

            $('#formLogin').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);

                $.ajax({
                    url: '../src/actions/processa-login.php',
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: (response) => {
                        if (response.sucesso) {
                            showToast(response.mensagem, 'success');
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 1000);
                        }
                    },
                    error: function (jqXHR) {
                        let mensagem = 'Erro ao processar o login.';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.mensagem) {
                            mensagem = jqXHR.responseJSON.mensagem;
                        }
                        showToast(mensagem, 'danger');
                    }
                });
            });
        });
    </script>
</body>

</html>