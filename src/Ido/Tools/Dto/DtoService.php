<?php

namespace Ido\Tools\Dto;


class DtoService
{

    public function transfer($data, $dtoProvider)
    {
        $dtoBuilder = new DtoBuilder($data);
        if(is_callable($dtoProvider)){
            call_user_func_array($dtoProvider, [$dtoBuilder]);
        }else{
            $dto= $this->createDto($dtoProvider);
            $dto->dto($dtoBuilder);
        }

        return $dtoBuilder->getResult();
    }

    /**
     * @param $dtoclass
     * @return Dto
     * @throws \Exception
     */
    public function createDto($dtoclass){
        if ($dtoclass instanceof Dto) {
            return $dtoclass;
        }
        if (!is_string($dtoclass) || !class_exists($dtoclass)) {
            throw new \Exception("Dto {$dtoclass} 不存在");
        }
        try {
            $reflection = new \ReflectionClass($dtoclass);
        } catch (\ReflectionException $e) {
        }
        if (!$reflection->implementsInterface(Dto::class)) {
            throw new \Exception("dto必须是\\Dto\\Dto的子类");
        }

        return $reflection->newInstanceWithoutConstructor();
    }
}