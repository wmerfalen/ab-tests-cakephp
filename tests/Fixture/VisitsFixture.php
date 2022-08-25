<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VisitsFixture
 */
class VisitsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'ip' => 'Lorem ipsum dolor sit amet',
                'converted' => 1,
                'created' => '2022-08-25 19:59:14',
            ],
        ];
        parent::init();
    }
}
