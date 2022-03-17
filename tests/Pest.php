<?php

function normalizePath(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}
