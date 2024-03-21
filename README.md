# PHP PDO CRUD Controller

Este é um simples controlador PHP que facilita as operações CRUD (Create, Read, Update, Delete) em um banco de dados MySQL utilizando PDO (PHP Data Objects).

## Instalação

Você pode simplesmente baixar o arquivo `Controller.php` e incluí-lo no seu projeto PHP.

## Exemplo de Uso

### 1 - Configuração

```php
// Inclua a classe Controller
require('controller.php');

// Instancie o objeto Controller para acessar as funções CRUD
$db = new Controller();
```
### 2 - Inserção de Dados

```php
$insert_array = [
    "name" => "Aisaka",
    "email" => "exemplo@exemplo.com",
    "age" => 23
];

$insert_data = $db->insert("users", $insert_array);

if ($insert_data) {
    echo "Dados inseridos com sucesso!";
} else {
    echo "Erro ao inserir dados.";
}
```

### 3 - Seleção de Dados (Sem o ORDER BY)

```php
$select_array = [
    "age" => 23
];

$select_data = $db->select("users", "*", $select_array);

if ($select_data) {
    print_r($select_data);
} else {
    echo "Nenhum dado encontrado.";
}
```

### 4 - Seleção de Dados (Com o ORDER BY)

```php
$select_array = [
    "age" => 23
];

$select_data = $db->select("users", "*", $select_array, "age DESC");

if ($select_data) {
    print_r($select_data);
} else {
    echo "Nenhum dado encontrado.";
}
```

### 5 - Atualização de Dados

```php
$update_array = [
    "age" => 23
];

$update_where = [
    "email" => "exemplo@exemplo.com"
];

$update_data = $db->update("users", $update_array, $update_where);

if ($update_data) {
    echo "Dados atualizados com sucesso!";
} else {
    echo "Erro ao atualizar dados.";
}
```

### 6 - Exclusão de Dados

```php
$delete_where = array(
    "age" => 23
);

$delete_data = $db->delete("users", $delete_where);

if ($delete_data) {
    echo "Dados excluídos com sucesso!";
} else {
    echo "Erro ao excluir dados.";
}
```
