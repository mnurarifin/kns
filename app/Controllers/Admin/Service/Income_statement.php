<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\IncomeStatementModel;

class Income_statement extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper(['form', 'url']);
        $this->incomeStatementModel = new IncomeStatementModel();
    }

    public function get_data($month = '', $year = '')
    {
        $data = [];
        $totReg = $totOmsetReg = $totSponsor = $totGenNode = $totPowerLeg = $totMatchingLeg = $totCashReward = $totIt = $totPay = $totBruto = $totDiff = $totProfit = 0;

        $detail = $this->incomeStatementModel->getData($month, $year);
        foreach ($detail as $key => $value) {
            $detail[$key]->total_activation =  $value->total_trx;
            // $detail[$key]->total_fee_it =  $value->total_reg * ($value->bonus_log_date <= '2022-09-23' ? 1000 : 1500);
            $detail[$key]->total_fee_it = 0;
            $detail[$key]->total_payout = $value->total_sponsor + $value->total_gen_node + $value->total_power_leg + $value->total_matching_leg + $value->total_cash_reward + $detail[$key]->total_fee_it;
            $detail[$key]->total_bruto =  $detail[$key]->total_activation - $detail[$key]->total_payout;
            $detail[$key]->total_profit =  $detail[$key]->total_bruto - $detail[$key]->total_diff;

            $totReg += $value->total_reg;
            $totOmsetReg += $detail[$key]->total_activation;
            $totSponsor += $value->total_sponsor;
            $totGenNode += $value->total_gen_node;
            $totPowerLeg += $value->total_power_leg;
            $totMatchingLeg += $value->total_matching_leg;
            $totCashReward += $value->total_cash_reward;
            $totIt += $detail[$key]->total_fee_it;
            $totPay += $detail[$key]->total_payout;
            $totBruto += $detail[$key]->total_bruto;
            $totDiff += $detail[$key]->total_diff;
            $totProfit += $detail[$key]->total_profit;
        }

        // $penjualan = $this->incomeStatementModel->get_selling_serial($month, $year);
        // foreach ($penjualan as $row) {

        // }

        $data['results'] = $detail;
        $data['total'] = [
            'TOTAL',
            $totReg,
            $totOmsetReg,
            $totSponsor,
            $totGenNode,
            $totPowerLeg,
            $totMatchingLeg,
            $totCashReward,
            $totPay,
            $totBruto,
            $totDiff,
            $totProfit,
        ];

        $this->createRespon(200, 'Data', $data);
    }

    function get_data_month()
    {
        $arr_results = [
            ['val' => '01', 'name' => 'Januari'],
            ['val' => '02', 'name' => 'Februari'],
            ['val' => '03', 'name' => 'Maret'],
            ['val' => '04', 'name' => 'April'],
            ['val' => '05', 'name' => 'Mei'],
            ['val' => '06', 'name' => 'Juni'],
            ['val' => '07', 'name' => 'Juli'],
            ['val' => '08', 'name' => 'Agustus'],
            ['val' => '09', 'name' => 'September'],
            ['val' => '10', 'name' => 'Oktober'],
            ['val' => '11', 'name' => 'November'],
            ['val' => '12', 'name' => 'Desember']
        ];
        $this->createRespon(200, 'Data', $arr_results);
    }

    function get_data_year()
    {
        $arr_results =  $this->incomeStatementModel->getYear();

        $this->createRespon(200, 'Data', $arr_results);
    }
}
