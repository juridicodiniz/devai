<?php

/*****************************************************************************************
● Função: atualiza um produto específico. Somente o usuário que criou o produto deve ser capaz de atualizar o produto.

  * Método: POST 
  *  Autenticação: SIM
 
● Parâmetros de entrada:
    id -> indica o id do produto a ser atualizado.
    novo_nome -> indica o novo nome que o produto terá.
    novo_preco -> indica o novo preco que o produto terá.
    nova_descricao -> indica a nova descrição que o produto terá.
    nova_img -> indica a nova imagem que o produto terá.
  
 
● Exemplo de Resposta Positiva:
{
    "sucesso":1,
}
 

● Exemplo de Resposta Negativa:
{
    "sucesso":0,
    "erro":"faltam parametros",
    "cod_erro":3
}
*/

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
	
	// Primeiro, verifica-se se todos os parametros foram enviados pelo cliente.
	// A criacao de um produto precisa dos seguintes parametros:
	// nome - nome do produto
	// preco - preco do produto
	// descricao - descricao do produto
	// img - imagem do produto
	if ( isset($_POST['id']) && isset($_POST['novo_nome']) && isset($_POST['novo_preco']) && isset($_POST['nova_descricao']) && isset($_FILES['nova_img'])) {
		
		// Aqui sao obtidos os parametros
                $id = $_POST['id'];
		$novo_nome = $_POST['novo_nome'];
		$novo_preco = $_POST['novo_preco'];
		$nova_descricao = $_POST['nova_descricao'];
		
		$filename = $_FILES['nova_img']['tmp_name'];
/*************************************************/$client_id="ce5d3a656e2aa51"; 
		$handle = fopen($filename, "r");
		$data = fread($handle, filesize($filename));
		$pvars   = array('image' => base64_encode($data));
		$timeout = 30;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
		$out = curl_exec($curl);
		curl_close ($curl);
		$pms = json_decode($out,true);
		$img_url=$pms['data']['link'];
		

		// Alteração no BD.              
                
                $consulta = $db_con->prepare("UPDATE  produtos SET nome='$novo_nome', preco = '$novo_preco', descricao ='$nova_descricao', img='$img_url' WHERE usuarios_login = '$login' and id = $id ");
                
                
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