<?php
session_start();
session_unset();
session_destroy();
header('Location: ../../public/login.php?sucesso=Você foi desconectado com sucesso!');
die();
?>