<?php

namespace App\Component\Extensions;

use Exception;
use Illuminate\Foundation\Testing\TestResponse;
use Opis\JsonSchema\Schema;
use Opis\JsonSchema\Validator;
use PHPUnit\Framework\Assert;

trait TestExtension
{
    /**
     * returns an schema file location that matches with given $method
     *
     * @param string $method
     * @return string
     */
    public function loadSchema($method)
    {
        $basePath = 'tests\schema\\';
        $subStr = explode('::', $method);
        $pathIndex = strpos($method, 'Tests\Feature\\') + strlen('Tests\Feature\\');
        $path = substr($subStr[0], $pathIndex) . '\\';
        $file = 'schema_' . $subStr[1] . '.json';
        return $basePath . $path . $file;
    }

    /**
     * @param TestResponse $response
     * @param string|null $__METHOD__
     */
    public function runSchemaTest($response, $__METHOD__ = null)
    {
        if ($__METHOD__ === null) {
            $e = new Exception();
            $caller = $e->getTrace()[1];
            $callerMethod = $caller['class'] . '::' . $caller['function'];
            $schema = Schema::fromJsonString(file_get_contents($this->loadSchema($callerMethod)));
        } else {
            $schema = Schema::fromJsonString(file_get_contents($this->loadSchema($__METHOD__)));
        }
        $responseData = json_decode($response->content());
        $validator = new Validator();
        $result = $validator->schemaValidation($responseData, $schema);
        if (!$result->isValid()) {
            dd($result->getErrors());
        }
        Assert::assertTrue($result->isValid(), 'Invalid response.');
    }
}