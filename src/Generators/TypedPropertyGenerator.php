<?php

namespace MueR\AdventOfCode\Generators;

use Laminas\Code\Generator\Exception\RuntimeException;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\ValueGenerator;
use Laminas\Code\Reflection\PropertyReflection;
use ReflectionClass;

class TypedPropertyGenerator extends PropertyGenerator
{
    private ?string $type = null;

    public static function fromReflection(PropertyReflection $reflectionProperty): TypedPropertyGenerator
    {
        $property = parent::fromReflection($reflectionProperty);

        if ($reflectionProperty->getType()) {
            $property->setType($reflectionProperty->getType());
        }

        $property->setSourceDirty(false);

        return $property;
    }

    public function generate(): string
    {
        $name = $this->getName();
        $defaultValue = $this->getDefaultValue();
        $self = new ReflectionClass($this);

        $output = '';

        if (($docBlock = $this->getDocBlock()) !== null) {
            $docBlock->setIndentation('    ');
            $output .= $docBlock->generate();
        }

        if ($this->isConst()) {
            if ($defaultValue !== null && !$defaultValue->isValidConstantType()) {
                throw new RuntimeException(sprintf(
                    'The property %s is said to be '
                    . 'constant but does not have a valid constant value.',
                    $this->name
                ));
            }

            return $output . $this->indentation . $this->getVisibility() . ' const ' . $name . ' = '
                . ($defaultValue !== null ? $defaultValue->generate() : 'null;');
        }

        $output .= $this->indentation . $this->getVisibility() . ($this->isStatic() ? ' static' : '') . ($this->getType() ? ' ' . $this->getType() : '') . ' $' . $name;

        if ($defaultValue === null) {
            return $output . ';';
        }

        $defaultValue->setOutputMode(ValueGenerator::OUTPUT_SINGLE_LINE);

        return $output . ' = ' . ($defaultValue !== null ? $defaultValue->generate() : 'null;');
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
