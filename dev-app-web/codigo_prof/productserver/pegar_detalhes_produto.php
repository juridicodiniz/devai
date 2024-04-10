<?php

/* * **********************************************************
  Função: obtém os dados referentes a um produto específico.
 * Método: GET
 * Autenticação: SIM
 * Autor: Fernando Diniz

 * **********************************************************

  ● Parâmetros de entrada:
  id -> indica o id do produto requerido pelo cliente.

  ●  Exemplo de Resposta Positiva:
  {
  "sucesso":1,
  "nome":"Produto 1 - teste",
  "preco":"100.00",
  "descricao":"Descricao do produto 1",
  "criado_por":"danielrt",
  "criado_em":"2023-09-26 02:38:14 +0000",
  "img":"https:\/\/i.imgur.com\/kS76v0M.jpg"
  }

  ● Exemplo de Resposta Negativa:
  {
  "sucesso":0,
  "erro":"faltam parametros",
  "cod_erro":3
  }

 * ********************************************************************** */

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

// conexão com bd
require_once('conexao_db.php');

// autenticação
require_once('autenticacao.php');

// array for JSON resposta
$resposta = array();

// verifica se o usuário conseguiu autenticar
if (autenticar($db_con)) {
    

    // Primeiro, verifica-se se o parametro de entrada foi enviado pelo cliente.
    // id -> indica o id do produto requerido pelo cliente.

    if (isset($_GET['id'])) {

        $id = $_GET['id'];

        // Realiza uma consulta ao BD e obtem o produto do id, caso ele exista.
        $consulta = $db_con->prepare("SELECT * FROM produtos WHERE id= $id");
        if ($consulta->execute()) {          
         
            $resposta["produto"] = array();
            $resposta["sucesso"] = 1;           

            if ($consulta->rowCount() > 0) {
                $linha = $consulta->fetch(PDO::FETCH_ASSOC);                    
              
                $resposta["nome"] = $linha["nome"];
                $resposta["preco"] = $linha["preco"];                
                $resposta["descricao"] = $linha["descricao"];
                //$resposta["criado_por"] = $linha["criado_por"];
                $resposta["criado_por"] = $linha["usuarios_login"];
                $resposta["criado_em"] = $linha["criado_em"]; 
                $resposta["img"] = $linha["img"];     
                
            }
        } else {
            // Caso ocorra falha no BD, o cliente 
            // recebe a chave "sucesso" com valor 0. A chave "erro" indica o 
            // motivo da falha.
            $resposta["sucesso"] = 0;
            $resposta["erro"] = "Erro no BD: " . $consulta->error;
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
} else {
    // senha ou usuario nao confere
    $resposta["sucesso"] = 0;
    $resposta["erro"] = "usuario ou senha não confere";
    $resposta["cod_erro"] = 0;
}

// fecha conexão com o bd
$db_con = null;

// Converte a resposta para o formato JSON.
echo json_encode($resposta);
?>   