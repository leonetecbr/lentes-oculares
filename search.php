<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die;
}

$json = file_get_contents('php://input');

if (empty($json)) {
    http_response_code(400);
    die;
}

try {
    $data = json_decode($json, true);
} catch (Exception $e) {
    http_response_code(400);
    die;
}

if (!isset($data['esferico']['od'], $data['esferico']['oe'], $data['cilindrico']['od'], $data['cilindrico']['oe'])) {
    http_response_code(400);
    die;
}

if (!is_numeric($data['esferico']['od']) || !is_numeric($data['esferico']['oe']) || !is_numeric($data['cilindrico']['od']) || !is_numeric($data['cilindrico']['oe'])) {
    http_response_code(400);
    die;
}

try {
    $result['success'] = false;

    $data = [
        'esferico' => [
            'od' => floatval($data['esferico']['od']),
            'oe' => floatval($data['esferico']['oe'])
        ],
        'cilindrico' => [
            'od' => floatval($data['cilindrico']['od']),
            'oe' => floatval($data['cilindrico']['oe'])
        ]
    ];

    $cilindrico = $data['cilindrico'];
    $esferico = $data['esferico'];

    if ($esferico['od'] <= -3 && (($cilindrico['od'] == 0 && $esferico['od'] >= -12) || ($cilindrico['od'] < 0 && $esferico['od'] >= -10)) && $esferico['oe'] <= -3 && (($cilindrico['oe'] == 0 && $esferico['oe'] >= -12) || ($cilindrico['oe'] < 0 && $esferico['oe'] >= -10))) {
        $result['lens'] = 'Prime';
        $result['success'] = true;
    } elseif ($cilindrico['od'] >= -5 && $cilindrico['oe'] >= -5 && $esferico['od'] <= 0 && $esferico['oe'] <= 0 && $esferico['od'] >= -15 && $esferico['oe'] >= -15) {
        $result['lens'] = 'Vision';
        $result['success'] = true;
    } else {
        $result['message'] = 'Infelizmente ainda não temos uma lente disponível para o seu grau.';
    }
} catch (Exception $e) {
    http_response_code(500);
    $result['message'] = 'Estamos com problemas técnicos, aguarde um pouco antes de tentar novamente.';
} finally {
    echo json_encode($result);
}