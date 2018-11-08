<?php
/**
 * Created by PhpStorm.
 * User: Ren
 * Date: 2018/11/3
 * Time: 17:08
 */

namespace Calculator;

/**
 * @brief 房贷计算
 * @class Mortgage
 */
class Mortgage
{
    protected $payload;
    /**
     * 房屋贷款总额
     * */
    protected $total_amount;
    /**
     * 房屋贷款年利率
     * */
    protected $rate_year;
    /**
     * 房屋贷款月数
     * */
    protected $mortgage_month;

    public function __construct(array $config)
    {
        /**
         * amount 贷款总额(万元) rate_year 贷款年利率 mortgage_month 贷款月数
         * */
        $this->payload = $config;
    }

    /**
     * 等额本息
     * @brief 等额本息
     * @return array
     * */
    function debx()
    {

        $total['amount']         = $this->payload['amount']; //贷款总额
        $total['rate_year']      = $this->payload['rate_year']; //贷款年利率
        $total['mortgage_month'] = $this->payload['mortgage_month']; //贷款月数，30年就是360个月

        $mortgage_month = $this->payload['mortgage_month'];      //贷款月数，30年就是360个月
        $amount         = $this->payload['amount'] * 10000; //贷款总额
        $rate_year      = $this->payload['rate_year'] / 100;  //贷款年利率
        $month_refund   = $amount * $rate_year / 12 * pow(1 + $rate_year / 12,
                $mortgage_month) / (pow(1 + $rate_year / 12, $mortgage_month) - 1); //每月还款金额
        $list           = [];
        $total_interest = 0; //总利息
        for ($i = 0; $i < $mortgage_month; $i++) {
            $interest_month = $amount * $rate_year / 12;   //每月还款利息
            $principal      = $month_refund - $interest_month;  //每月还款本金
            $list[]         = [
                'principal' => round($principal, 2),
                'interest'  => round($interest_month, 2),
                'total'   => round(($principal + $interest_month), 2),
            ];
            $amount         = $amount - $principal;
            $total_interest = $total_interest + $interest_month;
        }

        $total['total_interest'] = round(($total_interest / 10000), 2);//总利息
        $total['month_refund']   = round($month_refund, 2);//月供
        $total['total_refund']   = round(($this->payload['amount'] * 10000 + $total_interest) / 10000, 2);//总还款
        $total['list']           = $list;
        return $total;

    }

    /*
     * 等额本金
    *   */
    function debj()
    {

        $total['amount']         = $this->payload['amount']; //贷款总额
        $total['rate_year']      = $this->payload['rate_year']; //贷款年利率
        $total['mortgage_month'] = $this->payload['mortgage_month']; //贷款月数，30年就是360个月

        $mortgage_month = $this->payload['mortgage_month'];
        $amount = $this->payload['amount'] * 10000; //贷款总额
        $rate_year    = $this->payload['rate_year'] / 100;  //贷款年利率
        $principal      = $amount / $mortgage_month; //每个月还款本金
        $total_interest = 0; //总利息
        $list = array();
        $refund_first_month          = $principal + ($amount * $rate_year / 12);//第一个月还款
        $total['refund_first_month'] = round($refund_first_month, 2); //第一个月还款，必须放到循环的上面
        for ($i = 0; $i < $mortgage_month; $i++) {
            $interest_month = $amount * $rate_year / 12; //每月还款利息
            $list[]         = [
                'principal' => round($principal, 2),
                'interest'  => round($interest_month, 2),
                'total'   => round(($principal + $interest_month), 2),
            ];
            $amount -= $principal;
            $total_interest = $total_interest + $interest_month;
        }

        $total['total_interest'] = round(($total_interest / 10000), 2); //总利息，
        $total['refund_last_month']  = round(($principal + $interest_month), 2); //最后一个月还款
        return $total;
    }
}