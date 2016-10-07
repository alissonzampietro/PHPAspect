PHPAspect
===================


PHPAspect é um tutorial de como utilizar [**AOP (Aspect Oriented Programming ou Programação Orientada à Aspecto)**](https://pt.wikipedia.org/wiki/Programa%C3%A7%C3%A3o_orientada_a_aspecto) em PHP. Ele possui uma implementação simples de um MVC para a gerência de uma agenda de contatos, onde veremos como utilizar a AOP em duas situações: Na primeira, veremos como utilizar AOP para abstrair o sistema de autenticação, e na segunda, deixaremos o gerenciamento de transações das classes de repositório a cargo de uma classe que represente esse aspecto do sistema.

----------


Aspect Oriented Programming
-------------

AOP é um paradigma de programação utilizado para diminuir o acoplamento de códigos de interesse compartilhado. Muitos dicionários definem Apecto como aparência ou face exterior. Em programação, Aspecto é aquele código que não está relacionado a um objetos em si, mas seu comportamento é compartilhado por diversos objetos da aplicação, ou seja, faz parte da aparência da aplicação e é de interesse compartilhado. Um bom exemplo disso é a autorização em sistemas. Toda vez que um recurso da aplicação é acessado, é necessário verificar se o usuário que está acessando tem autorização para acessá-lo. Mesmo se isolarmos o código de autorização, ele precisará ser executado a todo momento em que for necessário fazer esta verificação. Outro exemplo é o controle transacional em repositórios que acessam bancos de dados. Os métodos que gravam dados no banco (insert, update ou delete) muitas veze precisam ser executados dentro de uma transação. Ou seja, iniciar uma transação e efetivar a gravação dos dados no banco é um comportamento de interesse compartilhado entre a classes de repositório.
Nós veremos como separar este comportamento de interesse compartilhado com AOP utilizando o framework [GO! Aop](https://github.com/goaop/framework).


Iniciando o projeto
-------------

Para iniciarmos o projeto, crie uma pasta chamada **PHPAspect** e dentro dela crie outra pasta chamada src. A estrutura de diretórios deverá ficar desta forma:

```
PHPAspect
|--src
```

Acesse a pasta **PHPAspect** através do seu terminal e vamos instalar as dependências necessárias para o nosso projeto com o [**composer**](https://getcomposer.org/doc/00-intro.md):

```
$ cd ~/PHPAspect
$ composer require goaop/framework respect/rest
```

Com as dependências instaladas, vamos configurar o namespace da nossa aplicação. Edite o arquivo **composer.json** e configure o carregamento das nossas classe de acordo com a **psr-4**:

```
{
    "require": {
        "goaop/framework": "^1.0",
        "respect/rest": "^0.6.0"
    },
    "autoload": {
    	"psr-4": {
    		"Aspecto\\": "src/"
    	}
    }
}
```

Vamos atualizar os arquivos de autoload do composer:

```
$ composer update
```

Após instaladas as dependências e configurado o namespace do projeto, vamos criar um arquivo chamado **index.php** na raiz do projeto, que será responsável pelo **bootstrap** da aplicação. É nele que iremos configurar o Respect/Rest e o GO! Aop. O framework [**Respect/Rest**](https://github.com/Respect/Rest) será responsável por gerenciar o roteamento da nossa aplicação.

Vamos iniciar nosso arquivo **index.php** configurando o **Respect/Rest** e criando uma rota para o controlador que iremos criar :

```
<?php
// index.php

require('vendor/autoload.php');

use Respect\Rest\Router;

$router = new Router();
$router->any('/contatos/', 'Aspecto\Controller\Contato');

echo $router->run();
```

Vamos criar também um diretório para o nosso controller e a classe **ContatoController.php**. A estrutura de diretórios neste momento deverá estar desta forma:

```
PHPApect
|--src
   |--Controller
      |--ContatoController.php       
|--composer.json
|--index.php
```

Vamos editar o arquivo *src/Controller/ContatoController.php* e implementar nossa classe:

```
<?php
// src/Controller/ContatoController.php

namespace Aspecto\Controller;

use Respect\Rest\Routable;

class ContatoController implements Routable {

	public function get($id) 
	{
		return "ok!";
	}

}
```
Agora vamos verificar se o roteamento está funcionando corretamente. A partir do diretório raiz da aplicação, execute o seguinte comando no terminal: 
```
$ php -sS localhost:8080
```
Acesse o endereço http://localhost:8080/contatos/ e verifique se o nosso **ok!** foi impresso na tela.
