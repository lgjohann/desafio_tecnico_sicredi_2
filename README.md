# API Desafio Técnico Sicredi

Este projeto é uma API RESTful desenvolvida com **PHP 8.4** e **Laravel 12**, utilizando **PostgreSQL** como banco de dados.

## Pré-requisitos

* **PHP 8.4** (com extensões necessárias instaladas)
* **Composer**
* **PostgreSQL** (Eu utilizei a versão 18)
* **Docker & Docker Compose** (Opcional, para rodar em container)

### Caso você esteja utilizando Windows

**Eu fiz o projeto utilizando Ubuntu como sistema operacional**, notei que a instalação e configuração é bem diferente da configuração no Windows, após alguns testes e anotações sobre como fazer funcionar o projeto partindo do zero, cheguei na conclusão que a maneira mais fácil de preparar o ambiente é utilizando o **[Laravel Herd](https://herd.laravel.com/windows)**. Ele instala a versão mais recente do PHP, que é utilizado pelo projeto, Composer e o ambiente de desenvolvimento de forma rápida e prática.

É necessário apenas baixar, instalar e abrir o aplicativo para preparar o ambiente. Há algumas extensões utilizadas pelas bibliotecas JWT que exigem uma instalação mais complicada no Windows e que podem ocasionar erros ao tentar instalar as dependências do projeto, mas o Herd instala elas automaticamente.

Para o banco de dados, você pode baixar o **[PostgreSQL](https://www.enterprisedb.com/downloads/postgres-postgresql-downloads)** ou algum outro banco relacional da sua preferência.

---

## Configuração Inicial

1. **Clone o repositório:**
```bash
   git clone git@github.com:lgjohann/desafio_tecnico_sicredi_2.git
   cd desafio_tecnico_sicredi_2
```

2. **Configure o ambiente:**

   Duplique o arquivo `.env.example` e renomeie para `.env`, ou então utilize o `.env` já presente na pasta do projeto, essa é a minha configuração de ambiente.
   Caso queira levar como exemplo.


3. **Configure o Banco de Dados:**

   Abra o arquivo `.env` e configure suas credenciais.

    - **Se rodar localmente:** Use `DB_HOST=localhost`

    - **Se rodar com Docker:** Use `DB_HOST=db`


---

# Há duas maneiras de rodar o projeto: localmente ou com Docker.

## Rodando Localmente

Após configurar o `.env` e criar o banco de dados no seu PostgreSQL ou outro banco de dados local, execute:

1. **Instale as dependências:**

```bash
composer install
```

2. **Gere a chave da aplicação:**

```bash
php artisan key:generate
```

3. **Execute as migrações do banco:**

```bash
php artisan migrate
```

4. **Inicie o servidor:**

    _Recomendo o comando nativo do PHP para evitar instabilidades do **php artisan serve** no Windows. Eu não consegui fazer funcionar com o **php artisan serve** em ambiente Windows, apenas Linux._

   ```bash
   php -S localhost:8000 -t public
   ````

    O endereço da API será então em: `http://localhost:8000`

---

## Rodando com Docker

Se preferir utilizar o Docker para isolar o ambiente:

_Uma observação sobre o docker: no meu ambiente de teste Windows, os comandos não estavam rodando corretamente se executados diretamente assim:
`docker-compose exec app composer install`, era necessário executá-los puros: `composer install` diretamente dentro do container laravel-app na aba exec do Docker Desktop. mas acredito ser algum problema do meu ambiente. De qualquer modo, leve isso em consideração em caso de erro e aplique aos outros comandos em ambiente docker._

1. **Suba os containers:**

     ```
	    docker-compose up -d
     ```

2. **Execute os comandos de configuração dentro do container:**

   Instalar dependências:

   ```bash
   docker-compose exec app composer install
   ```

   Gerar chave de segurança:

    ```
    docker-compose exec app php artisan key:generate
    ```

   Rodar migrações do banco:

    ```
    docker-compose exec app php artisan migrate
    ```

---

## Testando a API

Ao testar os endpoints utilizando o **Postman**, **Insomnia** ou similares, é obrigatório enviar o seguinte cabeçalho (Header) em todas as requisições:

| **Key**  | **Value**          |
| -------- | ------------------ |
| `Accept` | `application/json` |
  

**Por que?**

O Laravel detecta automaticamente o tipo de resposta esperada. Sem esse cabeçalho, em caso de erro (como 404 ou erro de validação), o Laravel tentará redirecionar para a página HTML anterior acessada (que não existe) em vez de retornar a mensagem de erro em JSON como esperado.

---

## Documentação da API (Swagger)

Para acessar a documentação do Swagger, visualizar os endpoints, explicações, schemas e testar a API:

**Acesse em:**
`http://localhost:8000/api/documentation`

### Gerando a documentação

A documentação gerada já é a mais atualizada, mas caso seja feito uma alteração nas anotações do swagger no projeto para teste, não esqueça de gerar novamente o arquivo do Swagger.

**Localmente:**

```bash
php artisan l5-swagger:generate
````

**Via Docker:**

```bash
docker-compose exec app php artisan l5-swagger:generate 
```


---
