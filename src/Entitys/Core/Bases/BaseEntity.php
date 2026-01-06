<?php

namespace Websyspro\Core\Entitys\Core\Bases;

use Websyspro\Core\Util;
use Websyspro\Core\Entitys\Core\Commons\Now;
use Websyspro\Core\Entitys\Decorations\Columns\Datetime;
use Websyspro\Core\Entitys\Decorations\Columns\Flag;
use Websyspro\Core\Entitys\Decorations\Columns\Number;
use Websyspro\Core\Entitys\Decorations\Constraints\PrimaryKey;
use Websyspro\Core\Entitys\Decorations\Events\Delete;
use Websyspro\Core\Entitys\Decorations\Events\Insert;
use Websyspro\Core\Entitys\Decorations\Events\Update;
use Websyspro\Core\Entitys\Decorations\Generations\AutoIncrement;
use Websyspro\Core\Entitys\Decorations\Requireds\NotNull;

/**
 * Classe base para todas as entidades do framework.
 * 
 * Fornece campos padrão de auditoria e controle de estado para todas as entidades,
 * incluindo informações de criação, atualização, ativação e exclusão lógica.
 * 
 * @package Websyspro\Core\Entitys\Core\Bases
 * @author Framework Websyspro
 * @version 1.0
 */
class BaseEntity
{
  /**
   * Identificador único da entidade.
   * 
   * Campo de chave primária com incremento automático.
   * Todos os registros devem ter um ID único e não nulo.
   */
  #[NotNull()]
  #[Number()]
  #[PrimaryKey()]
  #[AutoIncrement()]    
  public int $id;

  /**
   * Status de ativação do registro.
   * 
   * Indica se o registro está ativo (true) ou inativo (false).
   * Por padrão, novos registros são criados como ativos.
   */
  #[Flag()]
  #[NotNull()]
  #[Insert(1)]
  public bool $actived;

  /**
   * ID do usuário que ativou o registro.
   * 
   * Referência ao usuário responsável pela ativação.
   * Valor padrão é 1 para novos registros.
   */
  #[NotNull()]
  #[Number()]
  #[Insert(1)]
  public int $activedBy;

  /**
   * Data e hora da ativação do registro.
   * 
   * Timestamp automático definido no momento da criação.
   * Formato: Y-m-d H:i:s
   */
  #[NotNull()]
  #[Datetime()]
  #[Insert(Now::class)]
  public string $activedAt;

  /**
   * ID do usuário que criou o registro.
   * 
   * Referência ao usuário responsável pela criação.
   * Valor padrão é 1 para novos registros.
   */
  #[NotNull()]
  #[Number()]
  #[Insert(1)] 
  public int $createdBy;

  /**
   * Data e hora da criação do registro.
   * 
   * Timestamp automático definido no momento da criação.
   * Formato: Y-m-d H:i:s
   */
  #[NotNull()]
  #[Datetime()]
  #[Insert(Now::class)]
  public string $createdAt;

  /**
   * ID do usuário que fez a última atualização.
   * 
   * Referência ao usuário responsável pela última modificação.
   * Automaticamente definido como 1 durante atualizações.
   */
  #[Number()]
  #[Update(1)]
  public ?int $updatedBy;

  /**
   * Data e hora da última atualização.
   * 
   * Timestamp automático atualizado a cada modificação.
   * Formato: Y-m-d H:i:s
   */
  #[Datetime()]
  #[Update(Now::class)]
  public ?string $updatedAt;

  /**
   * Flag de exclusão lógica.
   * 
   * Indica se o registro foi excluído logicamente (soft delete).
   * false/0 = ativo, true/1 = excluído
   */
  #[Flag()]
  #[Insert(0)]
  #[Delete(1)]
  public ?bool $deleted;

  /**
   * ID do usuário que excluiu o registro.
   * 
   * Referência ao usuário responsável pela exclusão lógica.
   * Automaticamente definido como 1 durante exclusões.
   */
  #[Number()]
  #[Delete(1)]
  public ?int $deletedBy;

  /**
   * Data e hora da exclusão lógica.
   * 
   * Timestamp automático definido no momento da exclusão.
   * Formato: Y-m-d H:i:s
   */
  #[Datetime()]
  #[Delete(Now::class)]
  public ?string $deletedAt;

  /**
   * Verifica se a entidade possui dados.
   * 
   * Determina se o objeto contém propriedades com valores,
   * útil para validar se uma entidade foi populada.
   * 
   * @return bool True se a entidade contém dados, false caso contrário
   */
  public function exist(
  ): bool {
    return empty(get_object_vars($this)) === false;
  }

  /**
   * Mapeia a entidade atual para outro tipo de entidade.
   * 
   * Converte os dados da entidade atual para uma nova instância
   * da classe especificada, mantendo os valores das propriedades compatíveis.
   * 
   * @param string $toEntity Nome da classe de destino para o mapeamento
   * @return mixed Nova instância da classe especificada com dados mapeados
   */
  public function mapper(
    string $toEntity
  ): mixed {
    return Util::hydrateObject(
      $this, 
      $toEntity
    );
  }
}