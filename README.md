<h1 align="center">Service Desk - SyncMedic </h1>

<p align="center">Software de gestão de chamados </p>

<p align="center">
<img src="https://img.shields.io/badge/Laravel/Lumen-5.8-red" alt="">
<img src="https://img.shields.io/badge/NPM-6.14.17-blue" alt="">
 <img src="https://img.shields.io/badge/NODE-14.19.3-blue" alt="">
<img src="https://img.shields.io/badge/VueJs-2.6.12-green" alt="">
<img src="https://img.shields.io/badge/PHP->=7.1-blueviolet" alt="">
<img src="https://img.shields.io/badge/License-MIT-orange" alt="">
</p>

Sobre:
---
Este projeto fornece diretivas de gerenciamento de chamados e atividades.


Instalação:
---
Para execução do projeto será necessário a instalação e configuração das seguintes dependências.

<ol>
  <li>PHP - v7.1</li>
  <li>Node - v14.19.3</li>
  <li>NPM - v6.14.17</li>
  <li>Composer - <a href="https://getcomposer.org/">Pode ser baixado aqui.</a></li>
  <li>WebPack</li>
</ol>

O passo a passo aqui descrito é para instalação com sistema operaicional LINUX.

Install NVM (automático):

1. SSH para o servidor.
2. Instale o NVM.
   
   ```
   $ curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.38.0/install.sh | bash
   ```
   
Instalar NVM (manual):

1. SSH para o servidor.
2. Instale o NVM. O comando a seguir instala a versão mais recente do NVM no $HOME/.nvm.

```$ export NVM_DIR="$HOME/.nvm" && (
    git clone https://github.com/nvm-sh/nvm.git "$NVM_DIR"
    cd "$NVM_DIR"
    git checkout `git describe --abbrev=0 --tags --match "v[0-9]*" $(git rev-list --tags --max-count=1)`
  ) && \. "$NVM_DIR/nvm.sh"
  ``` 
  
3. Edite seu ~/.bashrc arquivo:

```
$ nano ~/.bashrc
```

Adicione as seguintes linhas no final do arquivo:

```
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm
```
4. Salve e saia do arquivo.
5. Recarregue o seu ~/.bashrcpara ativar o NVM.

```
$ source ~/.bashrc
```

6. Verifique se o NVM está instalado.

```
nvm -v
0.38.0
```

Instalar o Node.JS:
---

Para instalar uma versão específica do Node.JS, especifique o número da versão desejada.

```
nvm install 14 ( Versão do Node que desejar )
nvm use 14 ( Caso tenha mais de uma versão é só colocar qual deseja usar )
```

Verifique a versão do node

```
node -v
```

Realizado as devidas instalações, basta executar o composer.
Abra o seu terminal do diretório do projeto e execute o seguinte comando.
   ```
   composer install -vvv
   ```
Caso prefira usar o arquivo .phar do composer, basta executar o seguinte comando. Lembrando que o mesmo deverá estar 
na raiz do projeto.
   ```
   php composer.phar install -vvv
   ```

O projeto também consta com uma ferramenta que é o WebPack, sendo assim é necessário rodar o seguinte comando.
Abra o terminal no diretório do projeto e execute. (Obs.: Dependendo da IDE dá para configurar para que não fique 
digitando o comando.)

Para execução do script de build, deve se estar no seguinte diretório.

```
public/webapp

Execute: npm run build
```
   
Servidor:
---
Hoje o projeto não está configurado com docker, mais está sendo preparado para isso.

No entanto pode-se usar qualquer servidor para testar o sistema.

Para um primeiro momento pode se usar o Server do php usando o seguinte comando.

Como não foi configurado nenhum host para o projeto é importante que esse comando seja executando no seguinte diretório URL/public. 

 ```
 sudo php -S 127.0.0.1:8080
 ```
 
Dessa forma basta abrir no seu browser <a href="http://127.0.0.1:8080">http://127.0.0.1</a>

Bando de dados:
---

Para criação do banco e tabelas basta configurar o arquivo .env e executar as migrations.

   ```
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=<nome-do-bando>
    DB_USERNAME=root
    DB_PASSWORD=
   ```
   ```
    php artisan migrate:refresh --database=mysql --seed
   ``` 

Features:
---

- [x] Cadastro de atividades
- [x] Cadastro de subatividades
- [x] Cadastro de empresas
- [x] Cadastro de departamentos
- [x] Cadastro de usuário
- [x] Cadastro de permissões
- [x] Abertura de chamado
- [x] Relatórios

Tecnologias
---

As seguintes ferramentas foram usadas na construção do projeto:

- [VueJs](https://vuejs.org/)
- [Vuex](https://vuex.vuejs.org/)
- [Node.js](https://nodejs.org/en/)
- [PHP](https://www.php.net/)
- [RabbitMQ](https://www.rabbitmq.com/)


Autor
---

<img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/7145009?v=4" width="100px;" alt=""/>
<br />
<sub><b><h3>Jonatan Passo</b></sub>
