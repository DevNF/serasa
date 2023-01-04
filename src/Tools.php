<?php

namespace Fuganholi\Serasa;

use Exception;

/**
 * Classe Tools
 *
 * Classe responsável pela comunicação com a API do Serasa
 *
 * @category  Serasa
 * @package   Fuganholi\Serasa\Tools
 * @author    Diego Almeida <diego.feres82 at gmail dot com>
 * @copyright 2022 Serasa
 * @license   https://opensource.org/licenses/MIT MIT
 */
class Tools
{
    /**
     * URL base para comunicação com a API
     *
     * @var array
     */
    private static $API_URL = [
        1 => 'https://api.consultanegativacao.com.br/v1',
        2 => 'https://api.consultanegativacao.com.br/v1/homologacao',
    ];

    /**
     * Variável responsável por armazenar os dados a serem utilizados para comunicação com a API
     * Dados como token, ambiente(produção ou homologação) e debug(true|false)
     *
     * @var array
     */
    private $config = [
        'cnpjSH' => '',
        'tokenSH' => '',
        'cnpjUsuario' => '',
        'login' => '',
        'password' => '',
        'environment' => '',
        'debug' => false,
        'upload' => false,
        'decode' => true
    ];

    /**
     * Metodo contrutor da classe
     *
     * @param string $token Token utilizado para comunicação com a Serasa
     * @param boolean $environment Define o ambiente: 1 - Produção, 2 - Sandbox
     */
    public function __construct(int $environment = 1)
    {
        $this->setEnvironment($environment);
    }

    /**
     * Define se a classe realizará um upload
     *
     * @param bool $isUpload Boleano para definir se é upload ou não
     *
     * @access public
     * @return void
     */
    public function setUpload(bool $isUpload) :void
    {
        $this->config['upload'] = $isUpload;
    }

    /**
     * Define se a classe realizará o decode do retorno
     *
     * @param bool $decode Boleano para definir se é decode ou não
     *
     * @access public
     * @return void
     */
    public function setDecode(bool $decode) :void
    {
        $this->config['decode'] = $decode;
    }

    /**
     * Função responsável por definir se está em modo de debug ou não a comunicação com a API
     * Utilizado para pegar informações da requisição
     *
     * @param bool $isDebug Boleano para definir se é produção ou não
     *
     * @access public
     * @return void
     */
    public function setDebug(bool $isDebug) :void
    {
        $this->config['debug'] = $isDebug;
    }

    /**
     * Função responsável por definir o cnpj da software house a ser utilizado para comunicação com a API
     *
     * @param string $cnpj CNPJ da Software House
     *
     * @access public
     * @return void
     */
    public function setCnpjSH(string $cnpj) :void
    {
        $this->config['cnpjSH'] = $cnpj;
    }

    /**
     * Função responsável por definir o token da software house a ser utilizado para comunicação com a API
     *
     * @param string $token Token da Software House
     *
     * @access public
     * @return void
     */
    public function setTokenSH(string $token) :void
    {
        $this->config['tokenSH'] = $token;
    }

    /**
     * Função responsável por definir o cnpj do usuario a ser utilizado para autenticação com a API
     *
     * @param string $cnpj CNPJ para autenticação na API
     *
     * @access public
     * @return void
     */
    public function setCnpjUsuario(string $cnpj) :void
    {
        $this->config['cnpjUsuario'] = $cnpj;
    }

    /**
     * Função responsável por definir o login a ser utilizado para comunicação com a API
     *
     * @param string $login Login para autenticação
     *
     * @access public
     * @return void
     */
    public function setLogin(string $login) :void
    {
        $this->config['login'] = $login;
    }

    /**
     * Função responsável por definir a senha a ser utilizado para comunicação com a API
     *
     * @param string $password Senha para autenticação
     *
     * @access public
     * @return void
     */
    public function setPassword(string $password) :void
    {
        $this->config['password'] = $password;
    }

    /**
     * Função responsável por setar o ambiente utilizado na API
     *
     * @param int $environment Ambiente API (1 - Produção | 2 - Sandbox)
     *
     * @access public
     * @return void
     */
    public function setEnvironment(int $environment) :void
    {
        if (in_array($environment, [1, 2])) {
            $this->config['environment'] = $environment;
        }
    }

    /**
     * Recupera se é upload ou não
     *
     *
     * @access public
     * @return bool
     */
    public function getUpload() : bool
    {
        return $this->config['upload'];
    }

    /**
     * Recupera se faz decode ou não
     *
     *
     * @access public
     * @return bool
     */
    public function getDecode() : bool
    {
        return $this->config['decode'];
    }

    /**
     * Retorna o cnpjSH utilizado para comunicação com a API
     *
     * @access public
     * @return string
     */
    public function getCnpjSH() :string
    {
        return $this->config['cnpjSH'];
    }

    /**
     * Retorna o tokenSH utilizado para comunicação com a API
     *
     * @access public
     * @return string
     */
    public function getTokenSH() :string
    {
        return $this->config['tokenSH'];
    }

    /**
     * Retorna o cnpjUsuario utilizado para autenticação com a API
     *
     * @access public
     * @return string
     */
    public function getCnpjUsuario() :string
    {
        return $this->config['cnpjUsuario'];
    }

    /**
     * Retorna o login utilizado para comunicação com a API
     *
     * @access public
     * @return string
     */
    public function getLogin() :string
    {
        return $this->config['login'];
    }

    /**
     * Retorna o password utilizado para comunicação com a API
     *
     * @access public
     * @return string
     */
    public function getPassword() :string
    {
        return $this->config['password'];
    }

    /**
     * Recupera o ambiente setado para comunicação com a API
     *
     * @access public
     * @return int
     */
    public function getEnvironment() :int
    {
        return $this->config['environment'];
    }

    /**
     * Retorna os cabeçalhos padrão para comunicação com a API
     *
     * @access private
     * @return array
     */
    private function getDefaultHeaders() :array
    {
        $headers = [
            'Accept: application/json',
            "cnpjSH: {$this->config['cnpjSH']}",
            "tokenSH: {$this->config['tokenSH']}",
            "cnpjUsuario: {$this->config['cnpjUsuario']}",
            "login: {$this->config['login']}",
            "password: {$this->config['password']}",
        ];

        if (!$this->config['upload']) {
            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Type: multipart/form-data';
        }
        return $headers;
    }


    /**
     * Função responsável por realizar a consulta de crédito no serasa
     *
     * @param $data Dados para consulta de crédito
     * @param array $params Parametros adicionais para a requisição
     *
     * @access public
     * @return array
     */
    public function consultaCredito(array $data, array $params = []) :array
    {
        try {
            $dados = $this->post("consultas/assincrona", $data, $params);

            if ($dados['httpCode'] >= 200 && $dados['httpCode'] <= 299) {
                $protocolo = $dados['body']->protocolo;
                $retorno = [
                    'status' => 'PENDING',
                    'protocolo' => $protocolo
                ];

                $response = $this->consultaProtocolo($protocolo);

                if (!empty($response)) {
                    $retorno['result'] = $response;
                    $retorno['status'] = 'DONE';
                }

                return $retorno;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->errors)) {
                $errors = [];
                foreach ($dados['body']->errors as $error) {
                    $errors[] = $error->message;
                }

                throw new Exception(implode("\r\n", $errors), 1);
            }

            throw new Exception(json_encode($dados), 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        } finally {
            if (isset($defaultDecode)) {
                $this->setDecode($defaultDecode);
            }
        }
    }

    /**
     * Função responsável por consultar o protocolo de uma consulta de crédito
     *
     * @param $protocolo Protocolo obtido na consulta de crédito
     * @param array $params Parametros adicionais para a requisição
     *
     * @access public
     * @return array
     */
    public function consultaProtocolo(string $protocolo, array $params = [])
    {
        try {
            $i = 1;
            $completed = false;
            $params = [
                [
                    'name' => 'protocolo',
                    'value' => $protocolo
                ]
            ];

            $defaultDecode = $this->getDecode();
            $this->setDecode(false);

            while($i <= 2 && !$completed) {
                $dados = $this->get('consultas/assincrona', $params);
                if ($dados['httpCode'] === 202) {
                    sleep(5);
                } else {
                    $completed = true;
                    if ($dados['httpCode'] === 200) {
                        return $dados['body'];
                    } else {
                        $dados['body'] = json_decode($dados['body']);
                    }
                }

                $i++;
            }

            if (!$completed) {
                return '';
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->errors)) {
                $errors = [];
                foreach ($dados['body']->errors as $error) {
                    $errors[] = $error->message;
                }

                throw new Exception(implode("\r\n", $errors), 1);
            }

            throw new Exception(json_encode($dados), 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        } finally {
            if (isset($defaultDecode)) {
                $this->setDecode($defaultDecode);
            }
        }
    }

    /**
     * Execute a GET Request
     *
     * @param string $path
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function get(string $path, array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders()
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a POST Request
     *
     * @param string $path
     * @param string $body
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function post(string $path, array $body = [], array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => !$this->config['upload'] ? json_encode($body) : $this->convertToFormData($body),
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders()
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a PUT Request
     *
     * @param string $path
     * @param string $body
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function put(string $path, array $body = [], array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders(),
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($body)
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a PATCH Request
     *
     * @param string $path
     * @param string $body
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function patch(string $path, array $body = [], array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders(),
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => json_encode($body)
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a DELETE Request
     *
     * @param string $path
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function delete(string $path, array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders(),
            CURLOPT_CUSTOMREQUEST => "DELETE"
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a OPTION Request
     *
     * @param string $path
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function options(string $path, array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_CUSTOMREQUEST => "OPTIONS"
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Função responsável por realizar a requisição e devolver os dados
     *
     * @param string $path Rota a ser acessada
     * @param array $opts Opções do CURL
     * @param array $params Parametros query a serem passados para requisição
     *
     * @access protected
     * @return array
     */
    protected function execute(string $path, array $opts = [], array $params = []) :array
    {
        if (!preg_match("/^\//", $path)) {
            $path = '/' . $path;
        }

        $url = self::$API_URL[$this->config['environment']];

        $url .= $path;

        $curlC = curl_init();

        if (!empty($opts)) {
            curl_setopt_array($curlC, $opts);
        }

        if (!empty($params)) {
            $paramsJoined = [];

            foreach ($params as $param) {
                if (isset($param['name']) && !empty($param['name']) && isset($param['value']) && (!empty($param['value']) || $param['value'] == 0)) {
                    $paramsJoined[] = urlencode($param['name'])."=".urlencode($param['value']);
                }
            }

            if (!empty($paramsJoined)) {
                $params = '?'.implode('&', $paramsJoined);
                $url = $url.$params;
            }
        }

        curl_setopt($curlC, CURLOPT_URL, $url);
        curl_setopt($curlC, CURLOPT_RETURNTRANSFER, true);
        if (!empty($dados)) {
            curl_setopt($curlC, CURLOPT_POSTFIELDS, json_encode($dados));
        }
        $retorno = curl_exec($curlC);
        $info = curl_getinfo($curlC);
        $return["body"] = $this->config['decode'] ? json_decode($retorno) : $retorno;
        $return["httpCode"] = curl_getinfo($curlC, CURLINFO_HTTP_CODE);
        if ($this->config['debug']) {
            $return['info'] = curl_getinfo($curlC);
        }
        curl_close($curlC);

        return $return;
    }

    /**
     * Função responsável por montar o corpo de uma requisição no formato aceito pelo FormData
     */
    private function convertToFormData($data)
    {
        $dados = [];

        $recursive = false;
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $dados[$key] = $value;
            } else {
                foreach ($value as $subkey => $subvalue) {
                    $dados[$key.'['.$subkey.']'] = $subvalue;

                    if (is_array($subvalue)) {
                        $recursive = true;
                    }
                }
            }
        }

        if ($recursive) {
            return $this->convertToFormData($dados);
        }

        return $dados;
    }
}
