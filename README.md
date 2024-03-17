# PHP PDO CRUD Controller

Este é um simples controlador PHP que facilita as operações CRUD (Create, Read, Update, Delete) em um banco de dados MySQL utilizando PDO (PHP Data Objects).

## Instalação

Você pode simplesmente baixar o arquivo `Controller.php` e incluí-lo no seu projeto PHP.

## Uso

### 1 - Configuração

```php
<?php
// Inclua a classe Controller
require_once 'Controller.php';

// Instancie o objeto Controller para acessar as funções CRUD
$db = new Controller();
?>
```
### 2 - Inserção de Dados
