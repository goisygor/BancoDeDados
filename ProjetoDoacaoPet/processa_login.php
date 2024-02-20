<?php
require_once 'conectaBD.php';
// Conectar ao BD (com o PHP)
// Verificar se está chegando dados por POST
if (!empty($_POST)) {
    // Iniciar SESSAO (session_start)
    session_start();
    try {
    // Montar a SQL
    $sql = "SELECT nome, email, telefone, data_nascimento 
    FROM usuarios
    WHERE email = :email  AND senha = :senha"; // dados de entrada
    // Preparar a SQL (pdo)
    $stmt = $pdo->prepare($sql);
    // Definir/Organizar os dados p/ SQL
    $dados = array(
    ':email' => $_POST['email'],
    ':senha' => md5($_POST['senha']) // senha ja cripitografada com md5
    );
    $stmt->execute($dados); 
    //$stmt->execute(array(':email' => $_POST['email'], ':senha' => $_POST['senha']));
    $result = $stmt->fetchAll();
    if ($stmt->rowCount() == 1) { // Se o resultado da consulta SQL trouxer um registro
    // Autenticação foi realizada com sucesso
    $result = $result[0]; // indice 0
    // Definir as variáveis de sessão
    $_SESSION['nome'] = $result['nome']; // RESULT SET NOME
    $_SESSION['email'] = $result['email']; // RESULT SET EMAIL
    $_SESSION['data_nascimento'] = $result['data_nascimento']; // RESULT SET DATA
    $_SESSION['telefone'] = $result['telefone']; // RESULT SET TELEFONE ( ELE IRÁ GUARDAR TODAS ESSAS INFORMAÇÕES)
    // Redirecionar p/ página inicial (ambiente logado)
    header("Location: index_logado.php");
    } else { // Signifca que o resultado da consulta SQL não trouxe nenhum registro
    // Falha na autenticaçao
    // Destruir a SESSAO
    session_destroy();
    // Redirecionar p/ página inicial (login)
    header("Location: index.php?msgErro=E-mail e/ou Senha inválido(s).");
    }
    } catch (PDOException $e) {
    die($e->getMessage());
    }
    }
    else {
        header("Location: index.php?msgErro=Você não tem permissão para acessar esta página..");
        }
        die();
        ?>