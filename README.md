# example acl vue

Este é um projeto de exemplo que demonstra a implementação de um sistema de Controle de Acesso (ACL - Access Control List) utilizando Laravel, Vue.js e Inertia.js. O sistema oferece uma estrutura robusta de gerenciamento de permissões e funções (roles) com os seguintes recursos principais:

### Recursos

- **Gerenciamento de Usuários**: Cadastro, edição e exclusão de usuários do sistema
- **Controle de Funções (Roles)**: Criação e gerenciamento de funções com diferentes níveis de acesso
- **Permissões Granulares**: Sistema flexível de permissões baseado em ações (visualizar, criar, editar, excluir)
- **Interface Moderna**: Frontend construído com Vue.js e Inertia.js para uma experiência fluida
- **Autenticação Segura**: Sistema completo de autenticação e autorização
- **Middleware de Permissões**: Proteção de rotas baseada em permissões do usuário

O projeto utiliza o pacote Spatie Laravel-Permission para gerenciamento de permissões e está estruturado de forma modular, permitindo fácil extensão para novos recursos.

### Estrutura de Permissões

O sistema vem pré-configurado com dois níveis de acesso:
- Super Admin: Acesso total ao sistema
- Admin: Acesso gerencial com permissões configuráveis

### Instalação

1. Clone o repositório
2. Instale as dependências do Composer


## Install

```bash
composer install
```

```bash
php artisan db:seed
```

## Run

```bash
php artisan serve
```

## Author

[Fábio Vige](https://fabiovige.dev)
