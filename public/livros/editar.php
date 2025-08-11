<?php include 'views/layout.php'; ?>

<div class="card">
    <h2>✏️ Editar Livro</h2>
    
    <form method="POST" action="livros.php?acao=editar&id=<?= $livro->getId() ?>">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required 
                   value="<?= htmlspecialchars($livro->getTitulo()) ?>">
        </div>
        
        <div class="form-group">
            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" required 
                   value="<?= htmlspecialchars($livro->getAutor()) ?>">
        </div>
        
        <div class="form-group">
            <label for="ano">Ano de Publicação:</label>
            <input type="number" id="ano" name="ano" required min="1000" max="<?= date('Y') + 1 ?>"
                   value="<?= $livro->getAno() ?>">
        </div>
        
        <div class="form-group">
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required 
                   value="<?= htmlspecialchars($livro->getIsbn()) ?>">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success">💾 Atualizar Livro</button>
            <a href="index.php" class="btn">↩️ Voltar</a>
        </div>
    </form>
</div>

</body>
</html>
