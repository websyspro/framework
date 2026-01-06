<?php

namespace Websyspro\Core\Entitys\Enums;

/**
 * Enumeração dos tipos de atributos suportados.
 * 
 * Define os diferentes tipos de atributos que podem ser aplicados
 * às propriedades das entidades para configurar comportamentos
 * específicos no banco de dados.
 * 
 * @package Websyspro\Core\Entitys\Enums
 * @author Framework Websyspro
 * @version 1.0
 */
enum AttributeType: int
{
  /** Tipo de coluna padrão */
  case column = 1;
  
  /** Campo obrigatório (NOT NULL) */
  case requireds = 2;
  
  /** Índice único (UNIQUE) */
  case uniques = 3;
  
  /** Índice de performance (INDEX) */
  case indexes = 4;
  
  /** Chave estrangeira (FOREIGN KEY) */
  case foreigns = 5;
  
  /** Relacionamento um-para-um */
  case oneToOne = 6;
  
  /** Relacionamento um-para-muitos */
  case oneToMany = 7;
  
  /** Chave primária (PRIMARY KEY) */
  case primaryKey = 8;
  
  /** Geração automática (AUTO_INCREMENT) */
  case generations = 9;
  
  /** Evento de inserção */
  case insert = 10;
  
  /** Evento de atualização */
  case update = 11;
  
  /** Evento de exclusão */
  case delete = 12;
  
  /** Mapeamento personalizado */
  case mapper = 13;
}