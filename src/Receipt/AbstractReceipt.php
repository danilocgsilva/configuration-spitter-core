<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerFile;
use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Exception;

abstract class AbstractReceipt
{
    protected DockerFile $dockerFile;

    protected DockerCompose $dockerCompose;

    public array $parameters;

    public function getDockerFileObject(): DockerFile
    {
        return $this->dockerFile;
    }

    public function getDockerComposeObject(): DockerCompose
    {
        return $this->dockerCompose;
    }

    public function validateParameters(string $propertyWithParameter): array
    {
        $propertyWithParameterArray = explode(":", $propertyWithParameter);
        $property = $propertyWithParameterArray[0];
        if (!in_array($property, $this->getparameters())) {
            throw new Exception("The given property (" . $property . ") is not expected to be received.");
        }
        if (count($propertyWithParameterArray) === 2) {
            $argument = $propertyWithParameterArray[1];
        } else {
            $argument = '';
        }
        return [
            'property' => $property,
            'countTerms' => count($propertyWithParameterArray),
            'argument' => $argument
        ];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
