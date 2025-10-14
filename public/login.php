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
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h3>Entre com a sua Conta</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($mensagem_erro) : ?>
                            <div class="alert alert-danger">
                                <?php echo $mensagem_erro; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($mensagem_sucesso) : ?>
                            <div class="alert alert-success">
                                <?php echo $mensagem_sucesso; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="../src/actions/processa-login.php">

                            <div class="mb-3">
                                <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" required />
                            </div>

                            <div class="mb-3">
                                <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required />
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">
                                    Entrar!
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small>Ainda não tem uma conta? <a href="registro.php" class="link-light">Faça o registro aqui</a></small>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
