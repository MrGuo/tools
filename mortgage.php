<?php
/**
 *  简易房贷计算器
 *  
 *  @author ChaoGuo
 *  @since 2016-02-19
 *
 *  第一版，简易版
 *  第二版，包括利率改变的计算
 *  第三版，加入提前还款
 */

//借款总额
//$totalLoanAmount = 1420000;
$totalLoanAmount = 2000000;
//折扣
$discount = 0.9;
//分期次数
$stageTimes = 360;
//开始还款日期
$loanDate = "2015-05-10";
//2016-01-01调整为4.9
$rateHistory = array(
    "2015" => 5.9,
    "2016" => 4.9,
    "2017" => 4.9,
);

//已还本金总额
$right = 0;
//剩余本金总额
$left = $totalLoanAmount;
//利息总额
$totalAccrual = 0;
//每月本金
$principal = bcdiv($totalLoanAmount, $stageTimes, 2);

//按键名倒序排列
krsort($rateHistory);

//每年调一次利率
for ($i=0; $i<$stageTimes; $i++) {
    //计算月份
    $date = date("Y-m-d", strtotime("{$loanDate} + {$i} month"));
    $year = substr($date, 0, 4);
    //当前月份的利率
    $curRate = isset($rateHistory[$year]) ? $rateHistory[$year] : $rateHistory[date('Y')];
    $rate = bcmul($curRate, $discount, 2);
    // 本月利息
    $accrual = bcdiv($left * bcdiv($rate, 100, 5), 12, 2);  
    // 本月利息，每年一月份利率发生变化时，当月利息的计算则是由时间计算区间内处于新老利率分别计算
    // 1月还会有问题，应该是银行采取了其他的计算方式
    $lastYearRate = NULL;
    if ($date < date("Y-m-d") && substr($date, 5, 2) == "01") {
        // 上一年剩下了几天 12-10 ~ 12-31按老利率(21 / 31)，1-01 ~1-10按新利率(10 / 31)
        $lastYear = date("Y", strtotime("-1 years"));
        // 上一年的利率
        foreach ($rateHistory as $_year => $_rate) {
            if ($_year <= $lastYear) {
                $lastYearRate = bcmul($_rate, $discount, 2);
            }
        }
        if (is_null($lastYearRate)) {
            die("ERROR");
        }
        $lastYearAccrual = bcmul(bcdiv($left * bcdiv($lastYearRate, 100, 5), 12, 2), bcdiv(21, 31, 2), 2);
        $currentYearAccrual = bcmul(bcdiv($left * bcdiv($rate, 100, 5), 12, 2), bcdiv(10, 31, 2), 2);

        $accrual = bcadd($lastYearAccrual, $currentYearAccrual, 2);
    }

    //所还利息总额
    $totalAccrual = bcadd($totalAccrual, $accrual, 2);
    //所还本金总额
    $right = bcadd($right, $principal, 2);
    //剩余本金
    $left = bcsub($left, $principal, 2);
    //本月还款总额
    $monthAmount = bcadd($accrual, $principal, 2);

    echo "月份:{$date} 支付利息:{$accrual} 支付本金:{$principal} 本月还款:{$monthAmount} 剩余本金{$left} \n";
}


