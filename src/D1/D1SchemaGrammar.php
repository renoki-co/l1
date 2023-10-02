<?php

namespace RenokiCo\L1\D1;

use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Support\Str;

class D1SchemaGrammar extends SQLiteGrammar
{
    public function compileTableExists()
    {
        return Str::of(parent::compileTableExists())
            ->replace('sqlite_master', 'sqlite_schema')
            ->__toString();
    }

    public function compileDropAllTables()
    {
        return Str::of(parent::compileDropAllTables())
            ->replace('sqlite_master', 'sqlite_schema')
            ->__toString();
    }

    public function compileDropAllViews()
    {
        return Str::of(parent::compileDropAllViews())
            ->replace('sqlite_master', 'sqlite_schema')
            ->__toString();
    }

    public function compileGetAllTables()
    {
        return Str::of(parent::compileGetAllTables())
            ->replace('sqlite_master', 'sqlite_schema')
            ->__toString();
    }

    public function compileGetAllViews()
    {
        return Str::of(parent::compileGetAllViews())
            ->replace('sqlite_master', 'sqlite_schema')
            ->__toString();
    }
}
