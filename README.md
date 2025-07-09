# Gerenciamento de reservas

## Telas do projeto

### 1 - Home

> Home ao carregar a página

![Home Padrão](https://i.imgur.com/dDhoUdx.png)

### 2 - Autenticação

> Verificar se cliente já possui cadastro

![Login](https://i.imgur.com/DNNV4dC.png)

> Formulário de cadastro do cliente

![Cadastro de usuários](https://i.imgur.com/e3JJPao.png)

### 3 - Fazer a reserva

> Código de verificação do e-mail

![Verificação do e-mail](https://i.imgur.com/TMvA4Od.png)

> Revisar dados e confirmar reserva

![Revisar dados e confirmar reserva](https://i.imgur.com/VvnOwwf.png)

> E-mail de confirmação da reserva

![E-mail de confirmação](https://i.imgur.com/jh4aAzI.jpg)

## O que foi utilizado

### Front-end:

- Nuxt.JS
- TypeScript
- Tailwind CSS
- Pinia para gerenciamento de estados

### Back-end

- PHP 8.2
- Laravel 11
- MySQL
- Docker
- Sistema de autenticação com Laravel Sanctum
- Testes unitários e integração com Pest

## Funcionalidades

- [x] Verificação e Cadastro de usuário
- [x] Disparo de e-mails
- [x] Fazer uma reserva
- [x] Editar dados

## Como rodar

#### Pré-Requisitos

- Node
- PHP 8.2
- Docker

#### Antes de tudo, clone este repositório

```bash
    git clone https://github.com/TeuSoares/reservation-management.git
```

#### Configurando servidor 👇

1. Inicialize o servidor e entre no container do app

```bash
    docker compose up -d nginx
    docker compose exec app bash
```

2. Instale as dependências

```bash
    composer install
```

3. Crie o arquivo `.env` e configure as varíaveis necessárias

```bash
    cp .env.example .env
```

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

```
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
```

4. Execute os comandos: `php artisan key:generate` e `php artisan config:cache`

5. Após criar seu banco de dados e configura-lo no .env, você pode fazer a migração das tabelas necessárias. Para isso rode o comando `php artisan migrate`

#### Inicializando o front-end 👇

1. Acesse a pasta web

```bash
    cd web
```

2. Instalando dependências

```bash
    npm install
```

3. Configure o .env

```bash
    cp .env.example .env
```

```
API_URL='http://localhost/api'
```

4. Inicializar projeto

```bash
    npm run dev
```

## Autor

- **Mateus Soares** [Linkedin](https://www.linkedin.com/in/mateus-soares-santos/)

## Versão

1.0.0

## Licença

Este projeto está licenciado sob a Licença MIT.
