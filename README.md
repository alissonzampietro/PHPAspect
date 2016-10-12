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

Crie uma pasta chamada **PHPAspect** e dentro dela e crie um composer.json seguinte conteúdo:

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

Após isso, rode o `composer install` para instalar as nossas dependências.
