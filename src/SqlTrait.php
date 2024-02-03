<?php
/**
 * Playground
 */
namespace Playground\Test;

/**
 * \Playground\Test\SqlTrait
 */
trait SqlTrait
{
    protected bool $replace_quotes = false;

    /**
     * Replace the escape backtick.
     */
    protected function setUp(): void
    {
        if (in_array(env('DB_CONNECTION'), [
            'sqlite',
        ])) {
            $this->replace_quotes = true;
        }
    }

    /**
     * Replace the backtick with quotes.
     */
    protected function replace_quotes(string $sql): string
    {
        return $this->replace_quotes ? str_replace('`', '"', $sql) : $sql;
    }
}
