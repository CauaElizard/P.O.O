<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../../src/css/cadastro.css">
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Cadastro</h2>
            <form action="process_cadastro.php" method="post">
                <div class="input-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <button type="submit" class="submit-btn">Cadastrar</button>
            </form>
        </div>
    </div>
</body>
</html>

