<?php


namespace Calculator\Discount;


class DiscountApplicator
{
	private $action;
	public function __construct($action)
	{
		$this->action = $action;
	}
	/**
	 * calculate order discount.
	 *
	 * @param int $amount 订单金额
	 * @return int
	 */
	public function calculate($amount)
	{
		$amount = 0;//优惠金额
		switch ($this->action['type']){
			case 'order_total' : //一口价
				$amount = $amount - $this->action['amount'];
				break;
			case 'order_ratio' ://打折
				$amount = $amount*(1 - $this->action['ratio']/10);
				break;
			case 'item_total' : //减去固定金额
				$amount = $this->action['amount'];
				break;
			default ;
		}
		return  $amount;
	}
}