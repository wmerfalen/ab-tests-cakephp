<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ControlSwitch Model
 *
 * @method \App\Model\Entity\ControlSwitch newEmptyEntity()
 * @method \App\Model\Entity\ControlSwitch newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ControlSwitch[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ControlSwitch get($primaryKey, $options = [])
 * @method \App\Model\Entity\ControlSwitch findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ControlSwitch patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ControlSwitch[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ControlSwitch|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ControlSwitch saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ControlSwitch[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ControlSwitch[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ControlSwitch[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ControlSwitch[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ControlSwitchTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('control_switch');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('test_value')
            ->maxLength('test_value', 32)
            ->notEmptyString('test_value');

        return $validator;
    }
}
