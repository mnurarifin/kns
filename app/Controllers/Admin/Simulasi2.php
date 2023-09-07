<?php

namespace App\Controllers\Admin;

ignore_user_abort(true);
set_time_limit(0);
ini_set('memory_limit', '256M');
class Simulasi2 extends BaseController
{
    private $levelMax = 10;
    private $batasMember = 0;
    private $batasMatch = 1;
    private $harga = 3000000;
    private $bonusMatch = 1000000;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    function index()
    {
        $mulai = date("Y-m-d H:i:s");
        $time_start = microtime(true);

        $this->db->query("DROP TABLE IF EXISTS `simulasi_master`");
        $this->db->query("DROP TABLE IF EXISTS `simulasi_network`");
        $this->db->query("DROP TABLE IF EXISTS `simulasi_netgrow`");
        $this->db->query("DROP TABLE IF EXISTS `simulasi_payout_match`");

        $tblMaster = "CREATE TABLE `simulasi_master` (
            `master_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `master_total_member` int(10) unsigned NOT NULL DEFAULT 0,
            `master_level` tinyint(4) unsigned NOT NULL DEFAULT 0,
            `master_is_execute` tinyint(1) unsigned NOT NULL DEFAULT 0,
            `master_date` date NOT NULL,
            PRIMARY KEY (`master_id`),
            KEY `is_execute` (`master_is_execute`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        $this->db->query($tblMaster);

        $tblNetwork = "CREATE TABLE `simulasi_network` (
            `network_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `network_upline_id` int(10) unsigned NOT NULL DEFAULT 0,
            `network_position` char(1) NOT NULL DEFAULT 'L',
            `network_level` smallint(6) NOT NULL DEFAULT 0,
            `network_left_id` int(10) unsigned NOT NULL DEFAULT 0,
            `network_right_id` int(10) unsigned NOT NULL DEFAULT 0,
            `network_left_node` int(10) unsigned NOT NULL DEFAULT 0,
            `network_right_node` int(10) unsigned NOT NULL DEFAULT 0,
            `network_omzet` int(10) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY (`network_id`),
            KEY `network_upline_id` (`network_upline_id`),
            KEY `posisi` (`network_left_id`,`network_right_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        $this->db->query($tblNetwork);

        $tblNetgrow = "CREATE TABLE `simulasi_netgrow` (
            `netgrow_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `netgrow_network_id` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_node_left` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_node_right` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_wait_left` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_wait_right` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_match` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_match_real` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_date` date NOT NULL,
            PRIMARY KEY (`netgrow_id`),
            KEY `id_and_date` (`netgrow_network_id`, `netgrow_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        $this->db->query($tblNetgrow);

        $tblPayout = "CREATE TABLE `simulasi_payout_match` (
            `payout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `payout_network_id` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_level` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_match` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_match_real` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_date` date NOT NULL,
            PRIMARY KEY (`payout_id`),
            KEY `payout_level` (`payout_level`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        $this->db->query($tblPayout);

        /*
        ========= EXECUTE =========
        */
        $this->createMasterLoop();

        $this->db->query('INSERT INTO simulasi_network SET network_id = 1, network_level = 1, network_omzet = '. $this->harga);

        $this->network();
        // $this->network3HU();

        $time_end = microtime(true);
        $selesai = date("Y-m-d H:i:s");
        $execution_time = ($time_end - $time_start) / 60;
        echo "<b>SIMULASI JARINGAN</b><br>start: {$mulai}<br>end: {$selesai}<br>Duration: {$execution_time} mins";

        $this->reportPayout();
    }

    function createMasterLoop()
    {
        $date = date("Y-m-d");
        $totalMember = pow(2, $this->levelMax)-1;
        $totalCount = 1;
        $level = 2;
        
        for ($n = 1; $n <= $this->levelMax; $n++) {
            $grow[$n] = 300/$n;
            $memberPerLevel[$n] = pow(2, $n);
        }

        while ($totalCount < $totalMember) {
            $addMember = ceil($totalCount * ($grow[$level]/100));

            if ($totalCount + $addMember >= $totalMember) {
                $addMember = $totalMember - $totalCount;
            }
            
            $this->db->query("INSERT INTO simulasi_master SET master_total_member = {$addMember}, master_level = {$level}, master_date = '{$date}'");
            
            $date = date("Y-m-d", strtotime($date. ' + 1 day'));
            $totalCount += $addMember;

            // echo '<br>'.$level.' - '.$totalMember.' - '.$totalCount.' - '.$addMember.'<br>';

            if ($totalCount >= $memberPerLevel[$level]){
                $level++;
            }
        }
    }

    function network()
    {
        $getMaster = $this->db->query('SELECT master_id, master_total_member, master_date FROM simulasi_master WHERE master_is_execute = 0 ORDER BY master_date ASC');
        foreach ($getMaster->getResult() as $rowMaster) {
            
            for ($n = 1; $n <= $rowMaster->master_total_member; $n++) {
                $getNetwork = $this->db->query('SELECT network_id, network_left_id, network_right_id, network_level FROM simulasi_network WHERE network_left_id = 0 OR network_right_id = 0 ORDER BY network_level ASC LIMIT 1')->getRow();
                $level = $getNetwork->network_level + 1;

                if ($getNetwork->network_left_id == 0) {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'L', network_level = {$level}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_left_id = {$id}, network_left_node = network_left_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    $this->netgrow($getNetwork->network_id, 'L', $getNetwork->network_level, $rowMaster->master_date);
                } else {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'R', network_level = {$level}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_right_id = {$id}, network_right_node = network_right_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    $this->netgrow($getNetwork->network_id, 'R', $getNetwork->network_level, $rowMaster->master_date);
                }
            }

            $this->db->query("UPDATE simulasi_master SET master_is_execute = 1 WHERE master_id = {$rowMaster->master_id}");
        }
    }

    function network3HU()
    {
        $getMaster = $this->db->query('SELECT master_id, master_total_member, master_date FROM simulasi_master WHERE master_is_execute = 0 ORDER BY master_date ASC');
        foreach ($getMaster->getResult() as $rowMaster) {
            
            for ($n = 1; $n <= $rowMaster->master_total_member; $n++) {
                $getNetwork = $this->db->query('SELECT network_id, network_left_id, network_right_id, network_level FROM simulasi_network WHERE network_left_id = 0 OR network_right_id = 0 ORDER BY network_level ASC LIMIT 1')->getRow();
                $level = $getNetwork->network_level + 1;

                if ($getNetwork->network_left_id == 0) {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'L', network_level = {$level}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_left_id = {$id}, network_left_node = network_left_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    if($level % 2 != 0) {
                        $this->netgrow($getNetwork->network_id, 'L', $getNetwork->network_level, $rowMaster->master_date);
                    }
                } else {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'R', network_level = {$level}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_right_id = {$id}, network_right_node = network_right_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    if($level % 2 != 0) {
                        $this->netgrow($getNetwork->network_id, 'R', $getNetwork->network_level, $rowMaster->master_date);
                    }
                }
            }

            $this->db->query("UPDATE simulasi_master SET master_is_execute = 1 WHERE master_id = {$rowMaster->master_id}");
        }
    }

    function netgrow($upline, $posisi, $level, $date)
    {
        if ($upline > 0) {
            $node_left = $node_right = $grow_match = $grow_match_real = 0;
            $prev_left = $prev_right = $wait_left = $wait_right = 0;

            $sql_grow = "SELECT netgrow_id, netgrow_node_left, netgrow_node_right,
                        netgrow_match, netgrow_match_real
                    FROM simulasi_netgrow WHERE netgrow_network_id = {$upline} AND netgrow_date = '{$date}'";
            $data_grow = $this->db->query($sql_grow)->getRow();
            if (!empty($data_grow)) {
                $node_left = $data_grow->netgrow_node_left;
                $node_right = $data_grow->netgrow_node_right;
                $grow_match = $data_grow->netgrow_match;
                $grow_match_real = $data_grow->netgrow_match_real;
            }

            if ($posisi == 'L') {
                $node_left++;
            } else {
                $node_right++;
            }

            $sql_prev = "SELECT netgrow_wait_left, netgrow_wait_right FROM simulasi_netgrow WHERE netgrow_network_id = {$upline} AND netgrow_date < '{$date}'";
            $growPrev = $this->db->query($sql_prev)->getRow();
            if (!empty($growPrev)) {
                $prev_left = $growPrev->netgrow_wait_left;
                $prev_right = $growPrev->netgrow_wait_right;
            }

            $total_left = $node_left + $prev_left;
            $total_right = $node_right + $prev_right;

            if ($total_right == $total_left) {
                $total_match_real = $node_right;
            } elseif ($total_right > $total_left) {
                $total_match_real = $node_left;
                $wait_right = $total_right - $total_left;
            } elseif ($total_right < $total_left) {
                $total_match_real = $node_right;
                $wait_left = $total_left - $total_right;
            }

            $total_match_now = ($total_match_real > $this->batasMatch) ? $this->batasMatch : $total_match_real;

            if (empty($data_grow)) {
                $sqlInsertGrow = "INSERT INTO simulasi_netgrow
                    SET netgrow_network_id = '{$upline}',
                        netgrow_node_left = '{$node_left}',
                        netgrow_node_right = '{$node_right}',
                        netgrow_wait_left = '{$wait_left}',
                        netgrow_wait_right = '{$wait_right}',
                        netgrow_match = '{$total_match_now}',
                        netgrow_match_real = '{$total_match_real}',
                        netgrow_date = '{$date}'
                ";
                $this->db->query($sqlInsertGrow);
            } else {
                $sql_update = "UPDATE simulasi_netgrow
                    SET netgrow_node_left = '{$node_left}',
                        netgrow_node_right = '{$node_right}',
                        netgrow_wait_left = '{$wait_left}',
                        netgrow_wait_right = '{$wait_right}',
                        netgrow_match = '{$total_match_now}',
                        netgrow_match_real = '{$total_match_real}'
                    WHERE netgrow_id = '{$data_grow->netgrow_id}'
                ";
                $this->db->query($sql_update);
            }

            if ($total_match_real > $grow_match_real) {
                $countMatch = ($total_match_now > $grow_match) ? 1 : 0;

                $payoutMatch = "INSERT INTO simulasi_payout_match
                        SET payout_network_id = {$upline}, 
                            payout_level = {$level}, 
                            payout_match = {$countMatch}, 
                            payout_match_real = 1,
                            payout_date = '{$date}'
                ";
                $this->db->query($payoutMatch);
            }

            $sql_up = 'SELECT network_upline_id, network_position FROM simulasi_network WHERE network_id = ' . $upline;
            $row_up = $this->db->query($sql_up)->getRow();

            $level--;
            $this->netgrow($row_up->network_upline_id, $row_up->network_position, $level, $date);
        }
    }

    function reportPayout()
    {
        echo '<table border="1">';
        echo "<tr><th>Level</th>
            <th>Total Member</th>
            <th>Total Pasangan</th>
            <th>Omzet</th>
            <th>Payout Level 1</th>
            <th>Payout Pasangan</th>
            <th>Payout Total</th>
            <th>%</th>
            </tr>";

        $sql = 'SELECT network_level, COUNT(network_id) AS total_member, SUM(network_omzet) AS total_omzet FROM simulasi_network GROUP BY network_level ORDER BY network_level ASC';
        $rowRes = $this->db->query($sql)->getResult();
        $sumMember = $sumPasangan = $sumOmzet = $sumSedekah = $sumLevel = $sumMatch = $sumPercent = 0;
        $c=1;
        foreach ($rowRes as $row) {
            $totalMatch = $this->db->query('SELECT SUM(payout_match) AS total_match FROM simulasi_payout_match WHERE payout_level = '.$row->network_level)->getRow()->total_match;
            $pasangan = !empty($totalMatch) ? $totalMatch : 0;
            $omzet = $row->total_member * $this->harga;
            //$payoutSedekah = $omzet - $row->total_omzet;
            $payoutLevel = (count($rowRes) == $c) ? 0 : $row->total_member * 1000000;
            $payoutMatch = $pasangan * $this->bonusMatch;
            $percentase = ($payoutLevel + $payoutMatch) / $row->total_omzet * 100;

            $sumMember += $row->total_member;
            $sumPasangan += $pasangan;
            $sumOmzet += $omzet;
            //$sumSedekah += $payoutSedekah;
            $sumLevel += $payoutLevel;
            $sumMatch += $payoutMatch;
            $sumPercent += $percentase;
            
            echo '<tr>
                <td>'.number_format($row->network_level).'</td>
                <td>'.number_format($row->total_member).'</td>
                <td>'.number_format($pasangan).'</td>
                <td>'.number_format($omzet).'</td>
                <td>'.number_format($payoutLevel).'</td>
                <td>'.number_format($payoutMatch).'</td>
                <td>'.number_format($payoutMatch + $payoutLevel).'</td>
                <td>'.number_format($percentase).'</td>
                </tr>';
            $c++;
        }

        $sumPayout = $sumLevel + $sumMatch;
        $avgPercent = $sumPayout / $sumOmzet * 100;
        echo '<tr>
                <td>Total</td>
                <td>'.number_format($sumMember).'</td>
                <td>'.number_format($sumPasangan).'</td>
                <td>'.number_format($sumOmzet).'</td>
                <td>'.number_format($sumLevel).'</td>
                <td>'.number_format($sumMatch).'</td>
                <td>'.number_format($sumPayout).'</td>
                <td>'.number_format($avgPercent).'</td>
                </tr>';
        echo '</table>';
    }
}
