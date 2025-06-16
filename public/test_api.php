<?php
// Arquivo para testar se as APIs estão funcionando
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "❌ Usuário não logado<br>";
    exit;
}

$user_id = $_SESSION['usuario_id'];
echo "<h2>🧪 Teste das APIs</h2>";

// Teste 1: Verificar se UserSettings funciona
echo "<h3>1. Teste da Classe UserSettings</h3>";
try {
    require_once '../models/UserSettings.php';
    $userSettings = new UserSettings();
    echo "✅ Classe UserSettings carregada com sucesso<br>";
    
    // Teste buscar usuário
    $user = $userSettings->getUserById($user_id);
    if ($user) {
        echo "✅ Usuário encontrado: " . htmlspecialchars($user['nome']) . " (" . htmlspecialchars($user['email']) . ")<br>";
    } else {
        echo "❌ Usuário não encontrado<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro na classe UserSettings: " . $e->getMessage() . "<br>";
}

// Teste 2: Verificar APIs via cURL
echo "<h3>2. Teste das APIs via HTTP</h3>";

// Teste API get_user
$api_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/api/get_user.php?id=" . $user_id;
echo "🔗 Testando: " . $api_url . "<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $data = json_decode($response, true);
    if ($data && $data['success']) {
        echo "✅ API get_user funcionando: " . htmlspecialchars($data['user']['nome']) . "<br>";
    } else {
        echo "❌ API get_user retornou erro: " . htmlspecialchars($response) . "<br>";
    }
} else {
    echo "❌ API get_user não acessível (HTTP $http_code)<br>";
}

// Teste 3: Verificar estrutura de arquivos
echo "<h3>3. Verificação de Arquivos</h3>";

$files_to_check = [
    '../models/UserSettings.php',
    'api/get_user.php',
    'api/update_email.php',
    'api/update_password.php',
    'api/delete_account.php',
    '../src/js/configuracoes.js',
    '../src/css/configuracoes.css'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ " . $file . " existe<br>";
    } else {
        echo "❌ " . $file . " não encontrado<br>";
    }
}

// Teste 4: Verificar conexão com banco
echo "<h3>4. Teste de Conexão com Banco</h3>";
try {
    require_once '../config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "✅ Conexão com banco estabelecida<br>";
        
        // Testar query
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM usuarios");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Total de usuários no banco: " . $result['total'] . "<br>";
    } else {
        echo "❌ Falha na conexão com banco<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro de banco: " . $e->getMessage() . "<br>";
}

echo "<br><a href='configuracoes.php'>← Voltar para Configurações</a>";
?>
