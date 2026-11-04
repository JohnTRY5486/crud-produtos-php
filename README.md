# Cadastro de Produtos (CRUD) em PHP e MySQL

Sistema simples de cadastro de produtos desenvolvido na disciplina de **Programação Para Web** (ADS - Claretiano).

## Funcionalidades

- Cadastrar, listar, buscar, editar e excluir produtos (CRUD completo)
- Busca por nome ou marca
- Marca única (não permite duas marcas iguais)
- Validação de formulários com JavaScript e também no servidor (PHP)
- Interface responsiva com Bootstrap 5

## Tecnologias

- PHP (PDO)
- MySQL
- HTML5, CSS3 e JavaScript
- Bootstrap 5

## Segurança

- Consultas com prepared statements (proteção contra SQL Injection)
- Saída escapada com `htmlspecialchars` (proteção contra XSS)
- Senhas armazenadas com `password_hash`
- Exclusão somente por método POST

## Como rodar

1. Instale o [XAMPP](https://www.apachefriends.org/) e ligue o **Apache** e o **MySQL**.
2. Copie estes arquivos para uma pasta chamada `crud_produtos` dentro de `C:\xampp\htdocs`.
3. Abra `http://localhost/phpmyadmin`, vá na aba **SQL**, cole o conteúdo do arquivo `banco.sql` e clique em **Executar**.
4. Acesse `http://localhost/crud_produtos/`.

## Estrutura

| Arquivo | Função |
|---|---|
| `index.php` | Lista e busca produtos |
| `cadastrar.php` | Cadastra um produto |
| `editar.php` | Edita um produto |
| `excluir.php` | Exclui um produto |
| `conexao.php` | Conexão com o banco |
| `cabecalho.php` / `rodape.php` | Partes comuns das páginas |
| `estilo.css` / `validacao.js` | Estilo e validação |
| `banco.sql` | Criação do banco `sistema_db` e da tabela `produtos` |

## Autor

João Victor Mini Massagi
