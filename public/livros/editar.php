<?php include 'public/livros/layout.php'; ?>

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
            <label for="data_publicacao">Data de Publicação:</label>
            <input type="date" id="data_publicacao" name="data_publicacao" required 
                   min="1000-01-01" max="<?= date('Y-m-d') ?>"
                   value="<?= $livro->getDataPublicacaoBanco() ?>">
            <small style="color: #666; font-size: 0.9em;">
                Data atual: <?= $livro->getDataPublicacaoFormatada('d/m/Y') ?>
            </small>
        </div>
        
        <div class="form-group">
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required 
                   value="<?= htmlspecialchars($livro->getIsbn()) ?>">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success ">💾 Atualizar Livro</button>
            <a href="livros.php" class="btn">↩️ Voltar</a>
        </div>
    </form>
</div>

<script>
// Adicionar validação JavaScript para melhor UX
document.getElementById('data_publicacao').addEventListener('change', function() {
    const dataEscolhida = new Date(this.value);
    const hoje = new Date();
    
    if (dataEscolhida > hoje) {
        alert('A data de publicação não pode ser futura!');
        this.value = '<?= $livro->getDataPublicacaoBanco() ?>';
    }
});
</script>

</body>
</html>
