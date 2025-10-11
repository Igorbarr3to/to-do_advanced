<?php
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
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="bg-dark text-light">
    <div class="container mt-5 ">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <header class="card-header text-center">
                    <h3>Crie sua Conta</h3>
                </header>

                <main class="card-body">
                    <?php if ($mensagem_erro): ?>
                        <div class="alert alert-danger">
                            <?php echo $mensagem_erro; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($mensagem_sucesso): ?>
                        <div class="alert alert-success">
                            <?php echo $mensagem_sucesso; ?>
                        </div>
                    <?php endif; ?>

                    <form
                        method="post"
                        action="../src/processa-registro.php"
                        class="container-sm ">
                        <div class="mb-3">
                            <input type="text" name="nome" id="nome" placeholder="Seu nome" class="form-control bg-body-tertiary" />
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control bg-body-tertiary" placeholder="E-mail" />
                        </div>

                        <div class="mb-3">
                            <input type="password" name="senha" id="senha" class="form-control bg-body-tertiary" placeholder="Senha" />
                        </div>

                        <div class="mb-3">
                            <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control bg-body-tertiary" placeholder="Confirmar senha" />
                        </div>

                        <div id="verifica_senha"></div>

                        <button
                            type="submit"
                            class="btn btn-primary ">
                            Registrar-se!
                        </button>
                    </form>
                </main>
                <div class="card-footer text-center">
                    <small>Já tem uma conta? <a href="login.php">Faça login aqui</a></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#senha, #confirmar_senha').on('keyup', function() {
                let senha = $('#senha').val();
                let confirmarSenha = $('#confirmar_senha').val();
                if (confirmarSenha.length > 0) {
                    if (senha != confirmarSenha) {
                        $('#verifica_senha').text('As senhas não coincidem').css('color', 'red');
                        $('button').prop("disabled", true);
                    } else {
                        $('#verifica_senha').text('As senhas conferem!').css('color', 'green');
                        $('button').prop("disabled", false);
                    }
                } else {
                    $('#verifica_senha').text('');
                }
            })
        })
    </script>
</body>

</html>