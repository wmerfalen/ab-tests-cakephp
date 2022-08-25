<?php
namespace App\Model\Table;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;

class BaseTable extends Table
{
    use LocatorAwareTrait;
    protected $m_table_name = 'base';
    public function __construct($config = []) {
        parent::__construct();
        if (isset($config['table'])) {
            $this->m_table_name = $config['table'];
        }
    }
    protected function get_orm() {
        return $this->getTableLocator()->get($this->m_table_name);
    }

}
