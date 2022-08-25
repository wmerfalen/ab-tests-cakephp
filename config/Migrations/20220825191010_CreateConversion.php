<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateConversion extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('conversion');
		$table->addColumn('ip', 'string', [
            'default' => null,
            'limit' => 39,  // In case of ipv6 
            'null' => false,
        ])
        ->addColumn('hash', 'string', [
            /**
             * We'll be using sha512 which is 128 bytes according to:
             * https://www.php.net/manual/en/function.hash.php#104987
             */
            'default' => null,
            'limit' => 128,
            'null' => false,
        ])->addColumn('choice', 'string', [
            'default' => null,
            'limit' => 16,
            'null' => true,
        ])->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ])->create();
    }
}
