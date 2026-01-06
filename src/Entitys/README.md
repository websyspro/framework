# Sistema de Entidades - Framework Websyspro

## Visão Geral

O sistema de entidades do Framework Websyspro fornece uma camada de abstração robusta para operações de banco de dados, utilizando atributos PHP 8+ para definir estruturas de tabelas, relacionamentos e comportamentos.

## Estrutura do Sistema

### Core
- **BaseEntity**: Classe base com campos de auditoria padrão
- **Repository**: Classe para operações CRUD e consultas complexas
- **StructureTable**: Análise e estruturação de tabelas baseada em atributos

### Decorações (Attributes)

#### Colunas
- `#[Text(size)]` - Colunas VARCHAR
- `#[Number()]` - Colunas BIGINT
- `#[Flag()]` - Colunas booleanas (SMALLINT)
- `#[Datetime()]` - Colunas DATETIME
- `#[Date()]` - Colunas DATE
- `#[Decimal()]` - Colunas decimais
- `#[LongText()]` - Colunas TEXT

#### Restrições
- `#[NotNull()]` - Campo obrigatório
- `#[PrimaryKey()]` - Chave primária
- `#[Unique()]` - Índice único
- `#[ForeignKey()]` - Chave estrangeira

#### Gerações
- `#[AutoIncrement()]` - Incremento automático

#### Eventos
- `#[Insert(value)]` - Valor padrão na inserção
- `#[Update(value)]` - Valor padrão na atualização
- `#[Delete(value)]` - Valor padrão na exclusão

## Exemplo de Uso

### Definindo uma Entidade

```php
<?php

use Websyspro\Core\Entitys\Core\Bases\BaseEntity;
use Websyspro\Core\Entitys\Decorations\Columns\Text;
use Websyspro\Core\Entitys\Decorations\Columns\Number;
use Websyspro\Core\Entitys\Decorations\Requireds\NotNull;

class Usuario extends BaseEntity
{
    #[NotNull()]
    #[Text(100)]
    public string $nome;

    #[NotNull()]
    #[Text(150)]
    public string $email;

    #[Number()]
    public ?int $idade;
}
```

### Usando o Repository

```php
<?php

use Websyspro\Core\Entitys\Repository;

// Criar repository
$repo = Repository::entity(Usuario::class);

// Inserir dados
$usuario = $repo->insert([
    'nome' => 'João Silva',
    'email' => 'joao@email.com',
    'idade' => 30
]);

// Buscar todos
$usuarios = $repo->all();

// Buscar com filtros
$usuariosAtivos = $repo
    ->where(fn($q) => $q->where('actived', 1))
    ->all();

// Buscar um registro
$usuario = $repo
    ->where(fn($q) => $q->where('email', 'joao@email.com'))
    ->one();

// Atualizar
$repo->update([
    'id' => 1,
    'nome' => 'João Santos'
]);
```

## Campos de Auditoria (BaseEntity)

Todas as entidades que herdam de `BaseEntity` possuem automaticamente:

- `id` - Chave primária com auto incremento
- `actived` - Status de ativação
- `activedBy` - Usuário que ativou
- `activedAt` - Data de ativação
- `createdBy` - Usuário que criou
- `createdAt` - Data de criação
- `updatedBy` - Usuário da última atualização
- `updatedAt` - Data da última atualização
- `deleted` - Flag de exclusão lógica
- `deletedBy` - Usuário que excluiu
- `deletedAt` - Data de exclusão

## Relacionamentos

### One-to-One
```php
#[OneToOne(Usuario::class, 'usuario_id')]
public ?Usuario $usuario;
```

### One-to-Many
```php
#[OneToMany(Pedido::class, 'cliente_id')]
public Collection $pedidos;
```

## Enumerações

- **AttributeType**: Tipos de atributos suportados
- **ColumnType**: Tipos de colunas de banco de dados
- **RelationshipType**: Tipos de relacionamentos

## Funcionalidades Avançadas

- **Soft Delete**: Exclusão lógica automática
- **Timestamps**: Controle automático de datas
- **Relacionamentos**: Suporte a relacionamentos complexos
- **Paginação**: Sistema de paginação integrado
- **Query Builder**: Construção dinâmica de consultas
- **Mapeamento**: Conversão automática entre objetos

## Boas Práticas

1. Sempre herde de `BaseEntity` para entidades principais
2. Use `#[NotNull()]` em campos obrigatórios
3. Defina tamanhos apropriados para campos `#[Text()]`
4. Utilize relacionamentos para manter integridade
5. Aproveite os eventos automáticos para auditoria