<?php
namespace App\Model\Table;

use App\Model\Table\BaseTable;

class ConversionTable extends BaseTable
{
    public function __construct(){
        parent::__construct(['table' => 'conversion']);
    }

	public function save_conversion(string $user, string $color) {
        $conversion = $this->get_orm();
        
		$resultset = $conversion->find()->all();

		foreach ($resultset as $row) {
			// Each row is now an instance of our Article class.
			echo $row->title;
		}

	}
}
