<?php
if ($_POST && isset($_POST['cep']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    
    $cep = $_POST['cep'];
    $cep = filtrarCep($cep);
    
    if (validarCep($cep)) {
        $address = buscarEnderecoViaCep($cep);
        
        if ($address && !property_exists($address, 'erro')) {
            echo json_encode([
                'success' => true,
                'logradouro' => $address->logradouro ?? '',
                'bairro' => $address->bairro ?? '',
                'localidade' => $address->localidade ?? '',
                'uf' => $address->uf ?? ''
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'CEP não encontrado'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'CEP inválido'
        ]);
    }
    exit();
}

function obterEndereco() {
    if (isset($_POST['cep'])) {
        $cep = $_POST['cep'];
        $cep = filtrarCep($cep);

        if (validarCep($cep)) {
            $address = buscarEnderecoViaCep($cep);
        if (property_exists($address, 'erro')){
            $address = enderecoVazio();
            $address->cep = 'CEP não encontrado';
        }
    } else {
        $address = enderecoVazio();
        $address->cep = 'CEP Inválido';
    }
} else {
    $address = enderecoVazio();
    }
    return $address;
}

function enderecoVazio() {
     return (object) [
         'cep' => '',
         'logradouro' => '',
         'bairro' => '',
         'localidade' => '',
         'uf' => '' 
    ];
}

function filtrarCep(String $cep):String {
    return preg_replace('/[^0-9]/', '', $cep);
}

function validarCep(String $cep):bool{
    return preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep);
}

function buscarEnderecoViaCep(String $cep) {
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $json = curl_exec($ch);
    curl_close($ch);

    return json_decode($json);
}

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/([0-9])\1{10}/', $cpf)) {
        return false;
    }
    for ($posicao_digito = 9; $posicao_digito < 11; $posicao_digito++) {
        $soma = 0;
        for ($posicao_atual = 0; $posicao_atual < $posicao_digito; $posicao_atual++) {
            $soma += $cpf[$posicao_atual] * (($posicao_digito + 1) - $posicao_atual);
        }
        $resto = $soma % 11;
        $digito = ($resto < 2) ? 0 : 11 - $resto;
        if ($digito != $cpf[$posicao_digito]) {
            return false;
        }
    }
    return true;
}
?>