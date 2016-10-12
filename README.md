# PHPAspect

PHPAspect é um tutorial de como utilizar [**AOP (Aspect Oriented Programming ou Programação Orientada à Aspecto)**](https://pt.wikipedia.org/wiki/Programa%C3%A7%C3%A3o_orientada_a_aspecto) em PHP. Ele possui uma implementação simples de um MVC para a gerência de uma agenda de contatos, onde veremos como utilizar a AOP em duas situações: Na primeira, veremos como utilizar AOP para abstrair o sistema de autenticação, e na segunda, deixaremos o gerenciamento de transações das classes de repositório a cargo de uma classe que represente esse aspecto do sistema.


## Aspect Oriented Programming

AOP é um paradigma de programação utilizado para diminuir o acoplamento de códigos de interesse compartilhado. Muitos dicionários definem Apecto como aparência ou face exterior. Em programação, Aspecto é aquele código que não está relacionado a um objetos em si, mas seu comportamento é compartilhado por diversos objetos da aplicação, ou seja, faz parte da aparência da aplicação e é de interesse compartilhado. Um bom exemplo disso é a autorização em sistemas. Toda vez que um recurso da aplicação é acessado, é necessário verificar se o usuário que está acessando tem autorização para acessá-lo. Mesmo se isolarmos o código de autorização, ele precisará ser executado a todo momento em que for necessário fazer esta verificação. Outro exemplo é o controle transacional em repositórios que acessam bancos de dados. Os métodos que gravam dados no banco (insert, update ou delete) muitas veze precisam ser executados dentro de uma transação. Ou seja, iniciar uma transação e efetivar a gravação dos dados no banco é um comportamento de interesse compartilhado entre a classes de repositório.
Nós veremos como separar este comportamento de interesse compartilhado com AOP utilizando o framework [Go! Aop](https://github.com/goaop/framework).


## Trabalhando com o Go!Aop

Para conseguirmos trabalhar com o Go! Aop, devemos entender como podemos dizer que uma parte do sistema possui um determinado aspecto e em que momento o aspecto irá trabalhar. Para isso, usamos **Pointcuts** e **Advices**. Para saber mais, acesse a sessão [documentação completa sobre pointcuts e advices](http://go.aopphp.com/docs/pointcuts-and-advices/).


### Pointcuts

Os *Pointcuts* dizem quais são os alvos, ou seja, em que métodos do sistema o comportamento do aspecto será executado. Para isso, devemos informar se será aplicado em método publico ou privado, seguido da classe, do método e seus argumentos. Veja o exemplo a seguir:

```
<?php

	/**
	 * O método validaAcesso será executado toda vez que o método get da 
	 * classe Aspecto\Controller\ContatoController for invocado com o parâmetro $id
	 *
	 * @Before("execution(public Aspecto\Controller\ContatoController->get($id)")
	 */
	public function validaAcesso(MethodInvocation $invocation) {}
```

Podemos também utilizar o caracter * como coringa, o que nos permitirá aplicar o aspecto em diversas classes e diversos métodos. Aqui é onde as coisas ficam legais, pois é dessa forma que criamos um aspecto de fato na aplicação. 


### Advice

Os *Advices* determinam em que momento, durante a execução de um método, o aspecto será invocado. O Go! Aop nos permite fazer isso através de *annotations*. São elas: @Before, @After, @AfterThrowing e @Around. 

* @Before: O comportamento do aspecto será executado **antes** do método alvo
* @After: O comportamento do aspecto será executado **depois** do método alvo
* @AfterThrowing: O comportamento do aspecto será executado somente se o método alvo lançar alguma exceção
* @Around: O comportamento do aspecto será executado **antes e depois** do método alvo


### Exemplos

#### Exemplo 1 - Antes de qualquer método público da classe Aspecto\Controller\ContatoController com qualquer parâmetro

```
<?php

	/**
	 * @Before("execution(public Aspecto\Controller\ContatoController->*(*)")
	 */
	public function validaAcesso(MethodInvocation $invocation) {}
```

#### Exemplo 2 - Depois de qualquer método público de qualquer classe que estiver dentro do pacote Aspecto\Controller e terminar com Controller

```
<?php

	/**
	 * 
	 * @After("execution(public Aspecto\Controller\*Controller->*(*)")
	 */
	public function validaAcesso(MethodInvocation $invocation) {}
```

#### Exemplo 3 - Antes e depois de qualquer método da aplicação

```
<?php

	/**
	 * 
	 * @Around("execution(* *->*(*)")
	 */
	public function validaAcesso(MethodInvocation $invocation) {}
```


## Instalando e configurando o *Go! Aop*

Crie uma pasta chamada **PHPAspect** e dentro dela e crie um arquivo `composer.json` com o seguinte conteúdo:

```
{
    "require": {
        "goaop/framework": "^1.0",
        "respect/rest": "^0.6.0"
    },
    "autoload": {
    	"psr-4": {
    		"Aspect\\": "src/"
    	}
    }
}
```

Após isso, rode o comando `composer install` para instalar as dependências. Crie uma pasta na raiz do projeto chamada `src` e dentro dela crie um arquivo chamado `ApplicationAspectKernel.php` na raiz do projeto. É este arquivo que irá no permitir integrar os aspectos à nossa aplicação. Iremos criar uma classe que extende de `Go\Core\AspectKernel` e implementa o método `configureAop`. É por meio dele que iremos registrar os aspectos. 

```
<?php
// src/ApplicationAspectKernel.php

namespace Aspect;

use Go\Core\AspectKernel;
use Go\Core\AspectContainer;

/**
 * Application Aspect Kernel
 */
class ApplicationAspectKernel extends AspectKernel
{

    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     *
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
    }
}
```

Após isso, iremos inicializar o framework no bootstrap da nossa aplicação. Crie um arquivo chamado `index.php` na raiz do projeto com o seguinte conteúdo: 

```
<?php

require_once('vendor/autoload.php');

use Aspect\ApplicationAspectKernel;

$applicationAspectKernel = ApplicationAspectKernel::getInstance();
$applicationAspectKernel->init(array(
        'includePaths' => array(
            __DIR__ . '/src/'
        )
));
```

Vamos criar duas classes para demonstrar o funcionamento do aspecto. Crie dois arquivos dentro de `src` chamados `ClasseA.php` e `ClaseB.php`, respectivamente. Elas terão apenas um método chamado `executa()` que não receberá nenhum parâmetro.

```
// src/ClasseA.php

namepace Aspect;

class ClasseA {
	public function executa() {}
}

```

```
// src/ClasseB.php

namepace Aspect;

class ClasseB {
	public function executa() {}
}

```

Com nossas classes criadas, iremos criar nosso primeiro aspecto. Dentro de `src`, crie um arquivo chamado `ProfiladorAspect.php`. Ele conterá a classe responsável imprimir o tempo total de execução de todos os métodos que forem executados. Como esse comportamento é um comportamento é um comportamento de interesse compartilhado, ele será um aspecto da nossa aplicação.

```
<?php

namespace Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\After;

class ProfiladorAspect implements Aspect
{

    protected $objects = [];

    /**
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public Aspect\*->*(*))")
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
    	$obj = $invocation->getThis();
    	$id = spl_object_hash($obj);
    	$this->objects[$id] = time();
    }

    /**
     *
     * @param MethodInvocation $invocation Invocation
     * @After("execution(public Aspect\*->*(*))")
     */
    public function afterMethodExecution(MethodInvocation $invocation)
    {
    	$obj = $invocation->getThis();
    	$id = spl_object_hash($obj);

    	$total = time() - $this->objects[$id];

    	$class = get_class($obj);
    	$method = $invocation->getMethod()->getName();

    	echo "O método {$method} da classe {$class} levou {$total} para ser executado \n";
    }
}
```

O método `beforeMethodInvocation` recebe a anotação `@Before` para que seja executado antes do nosso alvo, e `public Aspect\*->(*)` define que nosso alvo é qualquer método de qualquer classe que esteja dentro do pacote `Aspect`. O mesmo ocorre com o método `afterMethodInvocation`, a diferença é que este recebe a annotation `@After`, para que seja executado ao fim do método alvo, nos permitindo obter o tempo total de execução do método.

Agora precisamos registrar o nosso aspecto. Para isso, editaremos a classe `Aspect\ApplicationAspectKernel` e colocaremos uma linha dentro do método `configureAop()`, ficando então desta forma:

```
<?php

...
	protected function configureAop(AspectContainer $container) 
	{
		$container->registerAspect(new ProfiladorAspect());
	}
...


Registrado o nosso aspecto, vamos instanciar as classes `Aspect\ClasseA` e `Aspect\ClasseB` e executar seus métodos `executa()` dentro do arquivo `index.php`. As duas classes não devem saber da existência do aspecto. Portanto, ao executar o método `executa()` de cada classe, os compartamentos contidos dentro do aspecto deverão ser invocados. Insira as linhas a seguir no final do arquivo `index.php`



Com isso, basta executar o arquivo `index.php` que se encontra na raiz do projeto por linha de comando ou rodar um servidor php e veremos a saída do nosso provilador.

#### Linha de comando:

`$ php index.php`

#### Servidor:

`$ php -sS localhost:8080`

Deverá ser impresso a seguinte mensagem:

```
O método executa da classe Aspect\ClasseA levou 0 para ser executado 
O método executa da classe Aspect\ClasseB levou 0 para ser executado
```

Vamos colocar uma chamada da função `sleep()` dentro dos métodos `executa()` para ver melhor o seu funcionamento. Insira a seguinte linha nos dois métodos `executa()`:

```
<?php

...
	public function executa() {
		sleep(rand(1, 5));
	}
```

Execute novamente o arquivo index.php e veja 
