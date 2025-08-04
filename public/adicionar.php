<?php include 'public/layout.php'; ?>

<div class="card">
    <h2>➕ Adicionar Novo Livro</h2>
    
    <form method="POST" action="livros.php?acao=adicionar">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required 
                   value="<?= isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" required 
                   value="<?= isset($_POST['autor']) ? htmlspecialchars($_POST['autor']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label for="ano">Ano de Publicação:</label>
            <input type="number" id="ano" name="ano" required min="1000" max="<?= date('Y') + 1 ?>"
                   value="<?= isset($_POST['ano']) ? $_POST['ano'] : '' ?>">
        </div>
        
        <div class="form-group">
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required 
                   placeholder="Ex: 978-3-16-148410-0"
                   value="<?= isset($_POST['isbn']) ? htmlspecialchars($_POST['isbn']) : '' ?>">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success">💾 Salvar Livro</button>
            <a href="index.php" class="btn">↩️ Voltar</a>
        </div>
    </form>
</div>

</body>
</html>
