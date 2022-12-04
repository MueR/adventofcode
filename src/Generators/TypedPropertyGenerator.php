<?php

namespace MueR\AdventOfCode\Generators;

use Laminas\Code\Generator\Exception\RuntimeException;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;
use Laminas\Code\Generator\ValueGenerator;
use Laminas\Code\Reflection\PropertyReflection;
use ReflectionClass;

class TypedPropertyGenerator extends PropertyGenerator
{
    protected ?TypeGenerator $type = null;

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

            $output .= sprintf(
                "%s%s%s const %s = %s",
                $output,
                $this->indentation,
                $this->getVisibility(),
                $name,
                ($defaultValue !== null ? $defaultValue->generate() : 'null;')
            );
        }

        $output .= sprintf(
            "%s%s%s%s $%s",
            $this->indentation,
            $this->getVisibility(),
            ($this->isStatic() ? ' static' : ''),
            ($this->getType() ? ' ' . $this->getType() : ''),
            $name
        );

        if ($defaultValue === null) {
            return $output . ';';
        }

        $defaultValue->setOutputMode(ValueGenerator::OUTPUT_SINGLE_LINE);

        return $output . ' = ' . ($defaultValue !== null ? $defaultValue->generate() : 'null;');
    }

    public function getType(): ?TypeGenerator
    {
        return $this->type;
    }

    public function setType(?TypeGenerator $type): void
    {
        $this->type = $type;
    }
}
