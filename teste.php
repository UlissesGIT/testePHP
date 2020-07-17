<?php

class Teste
{
    public $planos;
    public $precos;

    public function __construct()
    {
        // Ler os arquivos json
        $planos_file = file_get_contents('planos.json');
        $planos = json_decode($planos_file, true);
        $this->planos = $planos;

        $precos_file = file_get_contents('precos.json');
        $precos = json_decode($precos_file, true);
        $this->precos = $precos;

        // Iniciar o programa
        $this->index();
    }

    public function index()
    {
        echo 'Programa teste' . PHP_EOL;
        echo PHP_EOL;

        // Entrada - quantidade de beneficiarios
        $qtde_beneficiarios = 0;
        // Codigo do plano adquirido
        $plano = 0;
        // Array para armazenar os beneficiarios
        $users = [];

        // Recebe a qtde de beneficiarios e verifica se a entrada eh valida
        $flag = true;
        while ($flag) {
            $line = readline('Quantidade de beneficiarios: ');
            if (is_numeric($line)) {
                $qtde_beneficiarios = $line;
                $flag = false;
            } else {
                echo 'Valor invalido!' . PHP_EOL;
            }
        }

        // Recebe a idade dos beneficiarios e verifica se a entrada eh valida
        for ($i = 0; $i < $qtde_beneficiarios; $i++) {
            $flag = true;

            while ($flag) {
                $line = readline('Idade do beneficiário ' . ($i + 1)  . ': ');
                if (is_numeric($line)) {
                    $users[$i]['idade'] = $line;
                    $flag = false;
                } else {
                    echo 'Valor invalido!' . PHP_EOL;
                }
            }
        }

        // Recebe o nome dos beneficiarios
        for ($i = 0; $i < $qtde_beneficiarios; $i++) {
            $flag = true;

            while ($flag) {
                $line = readline('Nome do beneficiário ' . ($i + 1)  . ': ');
                if (empty($line)) {
                    echo 'Valor invalido!' . PHP_EOL;
                } else {
                    $users[$i]['nome'] = $line;
                    $flag = false;
                }
            }
        }
        
        // Recebe o ID do plano
        $flag = true;
        while ($flag) {
            $line = readline('Codigo do plano: ');
            if (empty($line)) {
                echo 'Valor invalido!' . PHP_EOL;
            } elseif ($this->verificaPlano($line) == false) {
                echo 'Plano invalido!' . PHP_EOL;
            } else {
                $plano = $line;
                $flag = false;
            }
        }
        
        // Coloca cada usuario em sua faixa
        for ($i = 0; $i < $qtde_beneficiarios; $i++) {
            foreach ($this->precos as $preco) {
                if ($preco['codigo'] == $plano) {
                    if ($users[$i]['idade'] >= 0 && $users[$i]['idade'] <= 17) {
                        $users[$i]['preco_plano'] = $preco['faixa1'];
                    } elseif ($users[$i]['idade'] >= 18 && $users[$i]['idade'] <= 40) {
                        $users[$i]['preco_plano'] = $preco['faixa2'];
                    } else {
                        $users[$i]['preco_plano'] = $preco['faixa3'];
                    }
                }
            }
        }
        
        $this->resumo($users, $plano);
    }

    private function verificaPlano($plano_id)
    {
        $result = false;

        foreach ($this->planos as $plano) {
            if ($plano['codigo'] == $plano_id) {
                $result = true;
            }
        }

        return $result;
    }

    private function calculaTotal($users)
    {
        $total = 0;

        foreach ($users as $user) {
            $total += $user['preco_plano'];
        }

        return $total;
    }

    private function resumo($users, $plano)
    {
        $total = $this->calculaTotal($users);

        echo PHP_EOL;
        echo '== Resultado ==' . PHP_EOL;
        echo 'Quantidade de beneficiários: ' . sizeof($users) . PHP_EOL;
        echo 'Plano: ' . $plano . PHP_EOL;
        echo 'Total do plano: R$' . number_format((float) $total, 2, '.', ',') . PHP_EOL;
        echo PHP_EOL;
        echo 'Lista dos beneficiarios' . PHP_EOL;
        foreach ($users as $user) {
            printf('Nome: %s - Idade: %d - Preco plano: R$%s' . PHP_EOL, $user['nome'], $user['idade'], number_format((float) $user['preco_plano'], 2, '.', ','));
        }
    }
}
new Teste();
