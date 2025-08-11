<?php include 'public/livros/layout.php'; ?>

<div class="card">
    <h2>📚 Lista de Livros</h2>
    
    <?php if (empty($livros)): ?>
        <div class="empty-state">
            <h3>Nenhum livro cadastrado</h3>
            <p>Comece adicionando seu primeiro livro ao sistema!</p>
            <a href="../../livros.php?acao=adicionar" class="btn btn-success">➕ Adicionar Primeiro Livro</a>
        </div>
    <?php else: ?>
        <p><strong>Total de livros:</strong> <?= count($livros) ?></p>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Data de Publicação</th>
                    <th>ISBN</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livros as $livro): ?>
                    <tr>
                        <td><?= $livro->getId() ?></td>
                        <td><?= htmlspecialchars($livro->getTitulo()) ?></td>
                        <td><?= htmlspecialchars($livro->getAutor()) ?></td>
                        <td>
                            <span title="<?= $livro->getDataPublicacaoFormatada('d/m/Y') ?>">
                                <?= $livro->getDataPublicacaoFormatada('d/m/Y') ?>
                            </span>
                            <small style="color: #666; display: block; font-size: 0.8em;">
                                (<?= $livro->getAnoPublicacao() ?>)
                            </small>
                        </td>
                        <td><?= htmlspecialchars($livro->getIsbn()) ?></td>
                        <td>
                            <div class="actions">
                                <a href="livros.php?acao=editar&id=<?= $livro->getId() ?>" 
                                   class="btn btn-small">✏️ Editar</a>
                                <a href="livros.php?acao=excluir&id=<?= $livro->getId() ?>" 
                                   class="btn btn-danger btn-small"
                                   onclick="return confirm('Tem certeza que deseja excluir este livro?')">🗑️ Excluir</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
