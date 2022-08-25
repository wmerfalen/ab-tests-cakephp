<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ControlSwitchTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ControlSwitchTable Test Case
 */
class ControlSwitchTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ControlSwitchTable
     */
    protected $ControlSwitch;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ControlSwitch',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ControlSwitch') ? [] : ['className' => ControlSwitchTable::class];
        $this->ControlSwitch = $this->getTableLocator()->get('ControlSwitch', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ControlSwitch);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ControlSwitchTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
