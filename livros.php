<?php
/**
 * Arquivo principal da aplicação de cadastro de livros
 * 
 * Este arquivo serve como ponto de entrada da aplicação,
 * gerenciando as rotas e controlando o fluxo da aplicação.
 */

require_once 'models/Livro.php';
require_once 'models/LivroRepository.php';
require_once 'config/Database.php';

// Inicializar o repositório de livros
$database = new Database();
$livroRepository = new LivroRepository($database);

// Processar ações do formulário
$acao = $_GET['acao'] ?? 'listar';
$mensagem = '';

switch ($acao) {
    case 'adicionar':
        if ($_POST) {
            try {
                $livro = new Livro(
                    null,
                    $_POST['titulo'],
                    $_POST['autor'],
                    $_POST['data_publicacao'], 
                    $_POST['isbn']
                );
                
                if ($livroRepository->adicionar($livro)) {
                    $mensagem = "Livro adicionado com sucesso!";
                } else {
                    $mensagem = "Erro ao adicionar livro.";
                }
            } catch (Exception $e) {
                $mensagem = "Erro: " . $e->getMessage();
            }
        }
        include 'public/livros/adicionar.php';
        break;
        
    case 'editar':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: livros.php');
            exit;
        }
        
        $livro = $livroRepository->buscarPorId($id);
        if (!$livro) {
            $mensagem = "Livro não encontrado.";
            include 'public/livros/listar.php';
            break;
        }
        
        if ($_POST) {
            try {
                $livroAtualizado = new Livro(
                    $id,
                    $_POST['titulo'],
                    $_POST['autor'],
                    $_POST['data_publicacao'], // Mudança aqui: era (int)$_POST['ano']
                    $_POST['isbn']
                );
                
                if ($livroRepository->editar($livroAtualizado)) {
                    $mensagem = "Livro atualizado com sucesso!";
                    $livro = $livroAtualizado;
                } else {
                    $mensagem = "Erro ao atualizar livro.";
                }
            } catch (Exception $e) {
                $mensagem = "Erro: " . $e->getMessage();
            }
        }
        include 'public/livros/editar.php';
        break;
        
    case 'excluir':
        $id = $_GET['id'] ?? null;
        if ($id && $livroRepository->excluir($id)) {
            $mensagem = "Livro excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir livro.";
        }
        // Redirecionar para listagem após exclusão
        header('Location: livros.php?mensagem=' . urlencode($mensagem));
        exit;
        
    default:
        // Listar livros
        $livros = $livroRepository->listarTodos();
        if (isset($_GET['mensagem'])) {
            $mensagem = $_GET['mensagem'];
        }
        include 'public/livros/listar.php';
        break;
}
?>
