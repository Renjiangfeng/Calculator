<?php


namespace Calculator\Discount;

//优惠规则验证
class DiscountRules
{
	private $rules;
	public function __construct($rules)
	{
		$this->rules = $rules;
	}
	/**
	 * 判断活动是否有效
	 *  规则 // contains_product{"product":"1,2,3,4"}  固定产品
	 *         contains_category {"category":1租赁    2充冷}  分类产品
	 *         item_total{"amount":12800}  满多少钱 减钱  或者  满多少钱打折
	 * @param  array $condition  订单现有的条件
	 * @return bool
	 */
	private function IsEligible($condition){
		$eligible = false;
		switch ($this->rules['type']){
			case 'contains_product' : //选择固定商品
				$eligible = in_array($condition['product_id'],$this->rules['configuration']['product'])?true:false;
				break;
			case 'contains_category' ://选择固定分类
				$eligible = in_array($condition['category_id'],$this->rules['configuration']['category'])?true:false;
				break;
			case 'item_total' : //订单总金额
				$eligible = $condition['amount'] >= $this->rules['configuration']['amount']?true:false;
				break;
			default ;
		}
		return  $eligible;
	}
}