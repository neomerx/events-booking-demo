<?php

use Illuminate\Database\Migrations\Migration;

abstract class BaseMigration extends Migration
{
    const ACTION_CASCADE = 'CASCADE';
    const ACTION_RESTRICT = 'RESTRICT';
    const ACTION_SET_NULL = 'SET NULL';
    const ACTION_NO_ACTION = 'NO ACTION';
    const ACTION_SET_DEFAULT = 'SET DEFAULT';
}
