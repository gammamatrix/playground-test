<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Test;

/**
 * \Playground\Test\SqlTrait
 */
trait SqlTrait
{
    protected bool $replace_quotes = true;

    /**
     * Replace the escape backtick.
     */
    protected function setUp(): void
    {
        // TODO unable to load the config this way
        //        if (in_array(config('database.default'), [
        //            'sqlite',
        //        ])) {
        //            $this->replace_quotes = true;
        //        }
    }

    /**
     * Replace the backtick with quotes.
     */
    protected function replace_quotes(string $sql): string
    {
        return $this->replace_quotes ? str_replace('`', '"', $sql) : $sql;
    }
}
