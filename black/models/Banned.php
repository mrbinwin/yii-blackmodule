<?php

/**
 * This is the model class for table "{{banned}}".
 *
 * The followings are the available columns in table '{{banned}}':
 * @property integer $id
 * @property string $ip
 * @property string $data
 * @property string $user_agent
 * @property string $reason
 *
 */
class Banned extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Banned the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{banned}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip', 'required'),
			array('ip, user_agent, reason', 'length', 'max'=>256),
		);
	}
}