<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ControlSwitchFixture
 */
class ControlSwitchFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'control_switch';
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
                'test_value' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
