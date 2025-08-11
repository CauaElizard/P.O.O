<?php include 'public/livros/layout.php'; ?>

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
            <label for="data_publicacao">Data de Publicação:</label>
            <input type="date" id="data_publicacao" name="data_publicacao" required 
                   min="1000-01-01" max="<?= date('Y-m-d') ?>"
                   value="<?= isset($_POST['data_publicacao']) ? $_POST['data_publicacao'] : '' ?>">
            <small style="color: #666; font-size: 0.9em;">
                Selecione a data completa de publicação do livro
            </small>
        </div>
        
        <div class="form-group">
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required 
                   placeholder="Ex: 978-3-16-148410-0"
                   value="<?= isset($_POST['isbn']) ? htmlspecialchars($_POST['isbn']) : '' ?>">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success">💾 Salvar Livro</button>
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
        this.value = '';
    }
});
</script>

</body>
</html>
