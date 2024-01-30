<?php

namespace Pelmered\LaravelDumper\Tests\Traits;

trait TestHelpers
{
    public function assertArrayStructure(array $structure, array $arrayData): static
    {
        foreach ($structure as $key => $value) {
            if (is_array($value) && $key === '*') {
                $this->assertIsArray($arrayData);

                foreach ($arrayData as $arrayDataItem) {
                    $this->assertArrayStructure($structure['*'], $arrayDataItem);
                }
            } elseif (is_array($value)) {
                $this->assertArrayHasKey($key, $arrayData);

                $this->assertArrayStructure($structure[$key], $arrayData[$key]);
            } else {
                $this->assertArrayHasKey($value, $arrayData);
            }
        }

        return $this;
    }
}
