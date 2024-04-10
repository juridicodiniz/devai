<?php

/******************************************************************************************
 
Nome: excluir_produto.php
Função: apaga um produto específico. Somente o usuário que criou o produto deve ser capaz de remover o produto.
Método: POST
Autenticação: SIM

●  Parâmetros de entrada:
id -> indica o id do produto a ser excluido.

  
●  Exemplo de Resposta Positiva:
{
"sucesso":1,
}
 
 *
● Exemplo de Resposta Negativa:
{
"sucesso":0,
"erro":"faltam parametros",
"cod_erro":3
}

***************************************************************************************************/

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");


/*
 * O seguinte codigo abre uma conexao com o BD e adiciona um produto nele.
 * As informacoes de um produto sao recebidas atraves de uma requisicao POST.
 */
 
 /*
 * 
 * Códigos de erro:
 * 0 : falha de autenticação
 * 2 : falha banco de dados
 * 3 : faltam parametros
 */

// conexão com bd
require_once('conexao_db.php');

// autenticação
require_once('autenticacao.php');

// array de resposta
$resposta = array();

// verifica se o usuário conseguiu autenticar
if(autenticar($db_con)) {
	

	if ( isset($_POST['id']) ) {
		
		// Aqui sao obtidos os parametros
                $id = $_POST['id'];
						
		$client_id="ce5d3a656e2aa51";
		
		
		// Alteração no BD.
                $consulta = $db_con->prepare("DELETE FROM  WHERE usuarios_login = '$login' and id = $id ");
                
                
		if ($consulta->execute()) {
			// Se o produto foi inserido corretamente no servidor, o cliente 
			// recebe a chave "sucesso" com valor 1
			$resposta["sucesso"] = 1;
		} else {
			// Se o produto nao foi inserido corretamente no servidor, o cliente 
			// recebe a chave "sucesso" com valor 0. A chave "erro" indica o 
			// motivo da falha.
			$resposta["sucesso"] = 0;
			$resposta["erro"] = "Erro ao criar produto no BD: " . $consulta->error;
			$resposta["cod_erro"] = 2;
		}
	} else {
		// Se a requisicao foi feita incorretamente, ou seja, os parametros 
		// nao foram enviados corretamente para o servidor, o cliente 
		// recebe a chave "sucesso" com valor 0. A chave "erro" indica o 
		// motivo da falha.
		$resposta["sucesso"] = 0;
		$resposta["erro"] = "faltam parametros";
		$resposta["cod_erro"] = 3;
		
	}
}
else {
	// senha ou usuario nao confere
	$resposta["sucesso"] = 0;
	$resposta["erro"] = "usuario ou senha não confere";
	$resposta["cod_erro"] = 0;
}

// Fecha a conexao com o BD
$db_con = null;

// Converte a resposta para o formato JSON.
echo json_encode($resposta);




















?>