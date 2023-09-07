<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\DashboardModel;
use App\Models\Admin\ReportModel;

class Dashboard extends BaseServiceController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->dashboardModel = new DashboardModel();
        $this->ReportModel = new ReportModel();
    }

    // pertumbuhan jaringan
    public function getNetgrowWeek()
    {
        $date = date("Y-m-d");
        $date = date("Y-m-d", strtotime("{$date} -6 day"));

        for ($n = 0; $n < 7; $n++) {
            $data[$n]['date'] = $date;

            $regis = $this->dashboardModel->rangeRegistration($date);
            $activ = $this->dashboardModel->rangeActivation($date);

            $keyReg = array_keys(array_column($regis, 'tanggal'), $date);
            $data[$n]['registration'] = (!empty($keyReg)) ? (int) $regis[$keyReg[0]]['total'] : 0;

            $keyActiv = array_keys(array_column($activ, 'tanggal'), $date);
            $data[$n]['activation'] = (!empty($keyActiv)) ? (int) $activ[$keyActiv[0]]['total'] : 0;

            $date = date("Y-m-d", strtotime("{$date} +1 day"));
        }

        $this->createRespon(200, 'Pertumbuhan Jaringan Mingguan', $data);
    }

    //Total Komisi dan Omzet
    public function getKomisiOmzet()
    {
        $regPrice = $this->dashboardModel->sumRegist();
        $activationPrice = $this->dashboardModel->sumActivation();

        $getRegistration = $this->dashboardModel->countRegistration();
        $getActivation = $this->dashboardModel->countActivation();
        $getStockist = $this->dashboardModel->countStockist();

        $data['bonusName'] = ["sponsor", "cash_reward", "gen_node", "power_leg", "matching_leg"];
        $data['bonusLabel'] = ["Sponsor", "Cash Reward", "Generasi Titik", "Power Leg", "Matching Leg"];

        //KOLOM TOTAL KOMISI
        $getKomisi = $this->dashboardModel->totalBonus();

        $data['saldoBonus']["sponsor"] = $getKomisi ? (int) $getKomisi->bonus_sponsor : 0;
        $data['saldoBonus']["cash_reward"] = $getKomisi ? (int) $getKomisi->bonus_cash_reward : 0;
        $data['saldoBonus']["gen_node"] = $getKomisi ? (int) $getKomisi->bonus_gen_node : 0;
        $data['saldoBonus']["power_leg"] = $getKomisi ? (int) $getKomisi->bonus_power_leg : 0;
        $data['saldoBonus']["matching_leg"] = $getKomisi ? (int) $getKomisi->bonus_matching_leg : 0;

        $data['totalKomisi'] = $getKomisi ? (int) $getKomisi->bonus_total : 0;
        $data['totalPaid'] = (int) $this->dashboardModel->sumTotalTransfer();
        $data['totalSaldo'] = $data['totalKomisi'] - $data['totalPaid'];

        //KOLOM TOTAL OMZET
        $data['totalReg'] = $regPrice;
        $data['totalActivation'] = $activationPrice;
        $data['totalOmzet'] = $data['totalReg'] + $data['totalActivation'];
        $data['totalPayout'] = $data['totalKomisi'];
        $data['totalProfit'] = $data['totalOmzet'] - $data['totalPayout'];

        //KOLOM TOTAL MEMBER
        $data['countReg'] = $getRegistration;
        $data['countMember'] = $getActivation;
        $data['countStockist'] = $getStockist;
        $data['saldo_acc'] = (int) $this->dashboardModel->saldoStockist()->acc;
        $data['saldo_paid'] = (int) $this->dashboardModel->saldoStockist()->paid;


        $this->createRespon(200, 'Omzet vs Payout', $data);
    }

    public function getSerialRegistrasi()
    {
        $data['total'] = $this->dashboardModel->countTotalSerial("activation");
        $data['sold'] = $this->dashboardModel->countTotalSerial("activation", "active");
        $data['used'] = $this->dashboardModel->countTotalSerial("activation", "used");
        $data['available'] = $data['sold'] - $data['used'];

        $this->createRespon(200, 'Data Serial Registrasi', $data);
    }

    public function getBonusWeek()
    {
        $date = date("Y-m-d");
        $date = date("Y-m-d", strtotime("{$date} -6 day"));

        for ($n = 0; $n < 7; $n++) {
            $data[$n]['date'] = $date;
            $bonus = $this->dashboardModel->rangeBonus($date);
            $key = array_keys(array_column($bonus, 'tanggal'), $date);
            $data[$n]['bonus'] = (!empty($key)) ? (int) $bonus[$key[0]]['total'] : 0;

            $date = date("Y-m-d", strtotime("{$date} +1 day"));
        }

        $this->createRespon(200, 'Histori Bonus Mingguan', $data);
    }

    public function getSerialAktivasi()
    {
        $result = array();
        $result['total'] = $this->dashboardModel->countSerialAll();
        $result['sold'] = $this->dashboardModel->serialAktivasiSold();
        $result['used'] = $this->dashboardModel->serialAktivasiUsed();

        $this->createRespon(200, 'Total Serial Aktivasi', $result);
    }

    public function getSerialUpgrade()
    {
        $result = array();
        $result['total'] = $this->dashboardModel->countSerialAll();
        $result['sold'] = $this->dashboardModel->serialUpgradeSold();
        $result['used'] = $this->dashboardModel->serialUpgradeUsed();

        $this->createRespon(200, 'Total Serial Upgrade', $result);
    }

    public function getSerialRO()
    {
        $result = array();
        $result['total'] = $this->dashboardModel->countSerialAll();
        $result['sold'] = $this->dashboardModel->serialROSold();
        $result['used'] = $this->dashboardModel->serialROUsed();

        $this->createRespon(200, 'Total Serial RO', $result);
    }

    public function getSaldoEwallet()
    {
        $result = array();
        $result['omzet'] = $this->dashboardModel->getIncome(date('m'), date('Y'));
        $result['persentase'] = ARR_CONFIG_FEE_IT_PERCENTAGE;
        $result['royalti'] = $this->dashboardModel->getRoyalty(date('m'), date('Y'));

        $this->createRespon(200, 'Total Ewallet Serial', $result);
    }
}
