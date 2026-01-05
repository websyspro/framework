<?php

namespace Websyspro\Elements\Collectons\Icons;

use Websyspro\Commons\DataList;

class Icon1kPlus
{
  public function __construct(
    private int $size,
    private string $fill,
    private string $viewBox,
    private string $path
  ){}

  public function get(
  ): string {
    return DataList::create([
      "<svg width=\"{$this->size}\" height=\"{$this->size}\" viewBox=\"{$this->viewBox}\">",
        "<path d=\"{$this->path}\" fill=\"{$this->fill}\" stroke=\"{$this->fill}\" stroke-width=\"2\"/>",
      "</svg>"
    ])->joinNotSpace();
  }

  public static function create(
    int $size = 24,
    string $fill = "rgb(60,60,60)",
    string $viewBox = "0 -960 960 960",
    string $path = "M672-406h28v-60h60v-28h-60v-60h-28v60h-60v28h60v60Zm-224 30h28v-96l98 96h42L506-482l110-102h-40l-100 94v-94h-28v208Zm-124 0h28v-208h-88v28h60v180Zm-92 204q-26 0-43-17t-17-43v-496q0-26 17-43t43-17h496q26 0 43 17t17 43v496q0 26-17 43t-43 17H232Zm0-28h496q12 0 22-10t10-22v-496q0-12-10-22t-22-10H232q-12 0-22 10t-10 22v496q0 12 10 22t22 10Zm-32-560v560-560Z",
  ): string {
    return ( new static(
      $size, $fill, $viewBox, $path
    ))->get();
  }
}