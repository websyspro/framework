<?php

namespace Websyspro\Core\Entitys\Dtos;

class PagedDTO
{
  public function __construct(
    public int $page,
    public int $rowsPerPage
  ){}
}