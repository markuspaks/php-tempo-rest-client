<?php

namespace TempoRestApi;

interface ParametersInterface
{
    /**
     * Return http parameters query
     * Example: param1=value1&param2=123
     *
     * @return string
     */
    public function getHttpQuery(): string;
}