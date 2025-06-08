<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../src/css/login.css">
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Login</h2>
            <form action="process_login.php" method="post">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <button type="submit" class="submit-btn">Entrar</button>
                
                <div class="form-footer">
                    <a href="#" class="forgot-password">Esqueceu a senha?</a>
                    <p class="signup-link">
                        Não tem conta? <a href="cadastro.php">Cadastre-se</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

