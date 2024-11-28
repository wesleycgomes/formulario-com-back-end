<?php
// CONFIGURAÇÕES DE CREDENCIAIS
$server = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'carros';

// CONEXÃO COM O BANCO DE DADOS
$conn = new mysqli($server, $usuario, $senha, $banco);

// VERIFICAR CONEXÃO COM O BANCO
if ($conn->connect_error) {
    die("Falha ao se comunicar com banco de dados: " . $conn->connect_error);
}

// PEGANDO OS DADOS VINDOS DO FORMULÁRIO
$titulo = $_POST['titulo'];
$preco = $_POST['preco'];
$descricao = $_POST['descricao'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$quilometragem = $_POST['quilometragem'];
$cambio = $_POST['cambio'];
$opcionais = isset($_POST['opcionais']) ? implode(", ", $_POST['opcionais']) : ''; // Se "opcionais" foi preenchido, criar uma string com os valores

// VERIFICAR SE OS DADOS OBRIGATÓRIOS FORAM PREENCHIDOS
if (empty($titulo) || empty($preco) || empty($descricao) || empty($marca) || empty($modelo) || empty($quilometragem) || empty($cambio)) {
    die("Por favor, preencha todos os campos obrigatórios.");
}

// PREPARAR COMANDO PARA A TABELA (A consulta SQL)
$sql = "INSERT INTO mensagens (titulo, preco, descricao, marca, modelo, quilometragem, cambio, opcionais) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// PREPARAR A DECLARAÇÃO SQL
$smtp = $conn->prepare($sql);

// VERIFICAR SE A PREPARAÇÃO FOI BEM-SUCEDIDA
if (!$smtp) {
    die("Erro ao preparar a consulta: " . $conn->error);
}

// VINCULAR OS PARÂMETROS PARA O COMANDO SQL
// Tipos de dados para bind_param:
// 's' -> string, 'i' -> inteiro, 'd' -> decimal, 'b' -> blob
$smtp->bind_param("ssssssss", $titulo, $preco, $descricao, $marca, $modelo, $quilometragem, $cambio, $opcionais);

// VERIFICAR SE O BIND PARAM FOI REALIZADO CORRETAMENTE
if ($smtp->errno) {
    die("Erro ao vincular os parâmetros: " . $smtp->error);
}

// EXECUTAR O COMANDO SQL
if ($smtp->execute()) {
    echo "Dados inseridos com sucesso!";
} else {
    echo "Erro ao inserir dados: " . $smtp->error;
}

// FECHAR A CONEXÃO
$smtp->close();
$conn->close();
?>
