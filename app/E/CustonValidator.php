<?php


namespace App\E;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CustonValidator extends Validator
{

    public function __construct(Translator $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->setCustomMessages(['cpfcnpj' => 'Cpf/Cnpj inválidos']);
        $this->setCustomMessages(['cpf' => 'Cpf inválidos']);
        $this->setCustomMessages(['senha' => 'Senha senha deve possuir mais 5 caracteres']);
    }

    public function validateCpfcnpj($attribute, $value, $parameters)
    {
        return true;
    }

    public function validateCpf($attribute, $value, $parameters)
    {


        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $value );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }

    public function validateSenha($attribute, $value, $parameters)
    {
        $aux = trim($value);

        if (strlen($aux) < 6) {
            return false;
        }

//        if (in_array($aux, ['123456', '654321', '000000', '1111111', '222222'])) {
//            return false;
//        }

        return true;
    }
}
