<?php

// Defina suas credenciais do Imgur
$client_id="ce5d3a656e2aa51";

// Caminho para a imagem que você deseja enviar
$image_path = 'D:\xampp\tmp\rolex.jpg';

// Inicializa uma sessão cURL
$curl = curl_init();

// Configura as opções da solicitação cURL
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.imgur.com/3/image.json",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => array(
        'image' => base64_encode(file_get_contents($image_path))
    ),
    CURLOPT_HTTPHEADER => array(
        "Authorization: Client-ID $client_id"
    ),
    CURLOPT_SSL_VERIFYHOST => 0, // Desativa a verificação do host SSL
    CURLOPT_SSL_VERIFYPEER => 0  // Desativa a verificação do peer SSL
));

// Executa a solicitação cURL
$response = curl_exec($curl);


// Verifica por erros
if ($response === false) {
    $error = curl_error($curl);
    echo "Erro cURL: $error";
} else {
    
    echo "Resposta :$response";
    echo "<br>";
    
     $error = curl_error($curl);
    echo "Erro cURL: $error";
    echo "<br>";
    // Decodifica a resposta JSON
    $data = json_decode($response);
    
    
    print_r($response);
    
    // Verifica se houve sucesso na decodificação da resposta JSON
    if ($data !== null && property_exists($data, 'success')) {
        // Verifica se houve sucesso na resposta
        if ($data->success) {
            echo 'Link para a imagem carregada: ' . $data->data->link;
        } else {
            echo 'Erro ao carregar a imagem: ' . $data->data->error;
        }
    } else {
        echo 'Erro ao decodificar a resposta JSON.';
    }
}

// Fecha a sessão cURL
curl_close($curl);