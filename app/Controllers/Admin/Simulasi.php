<?php

namespace App\Controllers\Admin;

class Simulasi extends BaseController
{
    private $levelMax = 10;
    private $batasMember = 0;
    private $batasMatch = 10000;
    private $harga = 2500000;
    private $bonusMatch = 1000000;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    function index()
    {
        $mulai = date("Y-m-d H:i:s");
        $time_start = microtime(true);

        $this->db->query("DROP TABLE IF EXISTS `simulasi_network`");
        $this->db->query("DROP TABLE IF EXISTS `simulasi_netgrow`");
        $this->db->query("DROP TABLE IF EXISTS `simulasi_payout_match`");

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
            PRIMARY KEY (`network_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        $this->db->query($tblNetwork);

        $tblNetgrow = "CREATE TABLE `simulasi_netgrow` (
            `netgrow_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `netgrow_network_id` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_node_left` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_node_right` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_match` int(10) unsigned NOT NULL DEFAULT 0,
            `netgrow_match_real` int(10) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY (`netgrow_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        $this->db->query($tblNetgrow);

        // $tblNetgrow = "CREATE TABLE `simulasi_payout_match` (
        //     `payout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        //     `payout_level` int(10) unsigned NOT NULL DEFAULT 0,
        //     `payout_netgrow` int(10) unsigned NOT NULL DEFAULT 0,
        //     `payout_network` int(10) unsigned NOT NULL DEFAULT 0,
        //     `payout_match` int(10) unsigned NOT NULL DEFAULT 0,
        //     `payout_match_real` int(10) unsigned NOT NULL DEFAULT 0,
        //     `payout_omset` int(10) unsigned NOT NULL DEFAULT 0,
        //     `payout_bonus` int(10) unsigned NOT NULL DEFAULT 0,
        //     `payout_percent` float(4,2) unsigned NOT NULL DEFAULT 0,
        //     PRIMARY KEY (`payout_id`)
        //     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        //     ";
        // $this->db->query($tblNetgrow);
        $tblNetgrow = "CREATE TABLE `simulasi_payout_match` (
            `payout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `payout_network_id` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_level_target` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_level_source` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_match` int(10) unsigned NOT NULL DEFAULT 0,
            `payout_match_real` int(10) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY (`payout_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        $this->db->query($tblNetgrow);

        $this->db->query('INSERT INTO simulasi_network SET network_id = 1, network_level = 1, network_omzet = '. $this->harga);
        $this->db->query('INSERT INTO simulasi_netgrow SET netgrow_network_id = 1');

        // $this->network();
        $this->network3HU();

        $time_end = microtime(true);
        $selesai = date("Y-m-d H:i:s");
        $execution_time = ($time_end - $time_start) / 60;
        echo "<b>SIMULASI JARINGAN</b><br>start: {$mulai}<br>end: {$selesai}<br>Duration: {$execution_time} mins";

        $this->reportPayout();
    }

    function network()
    {
        $totalMember = 0;

        for ($n = 2; $n <= $this->levelMax; $n++) {
            $totalMember++;
            $memberLevel = pow(2, ($n - 1));

            for ($x = 1; $x <= $memberLevel; $x++) {
                if ($this->batasMember > 0 && $this->batasMember == $totalMember) {
                    break;
                }

                $getNetwork = $this->db->query('SELECT network_id, network_left_id, network_right_id, network_level FROM simulasi_network WHERE network_left_id = 0 OR network_right_id = 0 ORDER BY network_level ASC, network_id ASC LIMIT 1')->getRow();

                if ($getNetwork->network_left_id == 0) {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'L', network_level = {$n}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_left_id = {$id}, network_left_node = network_left_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    $this->pasangan($getNetwork->network_id, 'L', $getNetwork->network_level, $n);
                } else {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'R', network_level = {$n}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_right_id = {$id}, network_right_node = network_right_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    $this->pasangan($getNetwork->network_id, 'R', $getNetwork->network_level, $n);
                }
            }
        }
    }

    function network3HU()
    {
        $totalMember = 0;

        // ANAK ROOT
        // kaki kiri
        $this->db->query("INSERT INTO simulasi_network SET network_upline_id = 1, network_position = 'L', network_level = 2, network_omzet = {$this->harga}");
        $no2Id = $this->db->insertID();
        $this->db->query("UPDATE simulasi_network SET network_left_id = {$no2Id}, network_left_node = network_left_node + 1 WHERE network_id = 1");
        $this->pasangan(1, 'L', 1, 2);

        // kaki kanan, tidak diakumulasi keatas & tidak menghasilkan pasangan
        $this->db->query("INSERT INTO simulasi_network SET network_upline_id = 1, network_position = 'R', network_level = 2");
        $no3Id = $this->db->insertID();
        $this->db->query("UPDATE simulasi_network SET network_right_id = {$no3Id}, network_right_node = network_right_node + 1 WHERE network_id = 1");
        // $this->pasangan(1, 'L', 1, 2);

        for ($n = 3; $n <= $this->levelMax; $n = $n + 2) {
            $totalMember++;
            $memberLevel = pow(2, ($n - 1));

            for ($x = 1; $x <= $memberLevel; $x++) {
                if ($this->batasMember > 0 && $this->batasMember == $totalMember) {
                    break;
                }

                $getNetwork = $this->db->query('SELECT network_id, network_left_id, network_right_id, network_level FROM simulasi_network WHERE network_left_id = 0 OR network_right_id = 0 ORDER BY network_level ASC, network_id ASC LIMIT 1')->getRow();

                // ID UTAMA
                if ($getNetwork->network_left_id == 0) {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'L', network_level = {$n}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_left_id = {$id}, network_left_node = network_left_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    $this->pasangan($getNetwork->network_id, 'L', $getNetwork->network_level, $n);
                } else {
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$getNetwork->network_id}, network_position = 'R', network_level = {$n}, network_omzet = {$this->harga}");
                    $id = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_right_id = {$id}, network_right_node = network_right_node + 1 WHERE network_id = {$getNetwork->network_id}");

                    $this->pasangan($getNetwork->network_id, 'R', $getNetwork->network_level, $n);
                }

                if ($n < $this->levelMax) {
                    // ID ANAK
                    // kaki kiri
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$id}, network_position = 'L', network_level = " . ($n + 1) . ", network_omzet = {$this->harga}");
                    $leftId = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_left_id = {$leftId}, network_left_node = network_left_node + 1 WHERE network_id = {$id}");
                    $this->pasangan($id, 'L', $n, $n + 1);

                    // kaki kanan, tidak diakumulasi keatas & tidak menghasilkan pasangan
                    $this->db->query("INSERT INTO simulasi_network SET network_upline_id = {$id}, network_position = 'R', network_level = " . ($n + 1));
                    $rightId = $this->db->insertID();
                    $this->db->query("UPDATE simulasi_network SET network_right_id = {$rightId}, network_right_node = network_right_node + 1 WHERE network_id = {$id}");
                    // $this->pasangan(1, 'L', 1, 2);
                }
            }
        }
    }

    function pasangan($upline, $posisi, $level, $levelSource)
    {
        if ($upline > 0) {

            $sql_grow = "SELECT netgrow_node_left, netgrow_node_right,
                        netgrow_match, netgrow_match_real
                    FROM simulasi_netgrow WHERE netgrow_network_id = {$upline} ORDER BY netgrow_id DESC LIMIT 1";
            $data_grow = $this->db->query($sql_grow)->getRow();
            if (!empty($data_grow)) {
                $node_left = $data_grow->netgrow_node_left;
                $node_right = $data_grow->netgrow_node_right;
                $grow_match = $data_grow->netgrow_match;
                $grow_match_real = $data_grow->netgrow_match_real;
            } else {
                $node_left = $node_right = $grow_match = $grow_match_real = 0;
            }

            if ($posisi == 'L') {
                $total_node_left = $node_left + 1;
                $total_node_right = $node_right;
            } else {
                $total_node_left = $node_left;
                $total_node_right = $node_right + 1;
            }

            if ($total_node_right == $total_node_left) {
                $total_match_real = $total_node_right;
            } elseif ($total_node_right > $total_node_left) {
                $total_match_real = $total_node_left;
            } elseif ($total_node_right < $total_node_left) {
                $total_match_real = $total_node_right;
            }

            $total_match_now = ($total_match_real > $this->batasMatch) ? $this->batasMatch : $total_match_real;

            if (empty($data_grow)) {
                $sqlInsertGrow = "INSERT INTO simulasi_netgrow
                    SET netgrow_network_id = '{$upline}',
                        netgrow_node_left = '{$total_node_left}',
                        netgrow_node_right = '{$total_node_right}',
                        netgrow_match = '{$total_match_now}',
                        netgrow_match_real = '{$total_match_real}'
                ";
                $this->db->query($sqlInsertGrow);
            } else {
                $sql_update = "UPDATE simulasi_netgrow
                    SET netgrow_node_left = '{$total_node_left}',
                        netgrow_node_right = '{$total_node_right}',
                        netgrow_match = '{$total_match_now}',
                        netgrow_match_real = '{$total_match_real}'
                    WHERE netgrow_network_id = '{$upline}'
                ";
                $this->db->query($sql_update);
            }

            if ($total_match_real > $grow_match_real) {
                $countMatch = ($total_match_now > $grow_match) ? 1 : 0;

                $payoutMatch = "INSERT INTO simulasi_payout_match
                        SET payout_network_id = {$upline}, 
                            payout_level_target = {$level}, 
                            payout_level_source = {$levelSource}, 
                            payout_match = {$countMatch}, 
                            payout_match_real = 1
                ";
                $this->db->query($payoutMatch);
            }

            $sql_up = 'SELECT network_upline_id, network_position FROM simulasi_network WHERE network_id = ' . $upline;
            $row_up = $this->db->query($sql_up)->getRow();

            $level--;
            $this->pasangan($row_up->network_upline_id, $row_up->network_position, $level, $levelSource);
        }
    }

    function reportPayout()
    {
        $sumMember = $sumPasangan = $sumOmzet = $sumSedekah = $sumLevel = $sumMatch = $sumPercent = 0;
        $c=1;

        echo '<table border="1">';
        echo "<tr><th>Level</th>
            <th>Total Member</th>
            <th>Total Pasangan</th>
            <th>Omzet</th>
            <th>Omzet Sedekah</th>
            <th>Payout</th>
            <th>%</th>
            </tr>";

        $sql = 'SELECT network_level, COUNT(network_id) AS total_member, SUM(network_omzet) AS total_omzet FROM simulasi_network GROUP BY network_level ORDER BY network_level ASC';
        $rowRes = $this->db->query($sql)->getResult();
        foreach ($rowRes as $row) {
            $totalMatch = $this->db->query('SELECT SUM(payout_match) AS total_match FROM simulasi_payout_match WHERE payout_level_target = '.$row->network_level)->getRow()->total_match;
            $pasangan = !empty($totalMatch) ? $totalMatch : 0;
            $omzet = $row->total_member * $this->harga;
            $omzetSedekah = $omzet - $row->total_omzet;
            $payout = $pasangan * $this->bonusMatch;

            $payoutLevel = (count($rowRes) == $c) ? 0 : $row->total_member * 1000000;
            $payoutMatch = $pasangan * $this->bonusMatch;
            $percentase = $payout / $row->total_omzet * 100;
            
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
                <td>'.number_format($omzetSedekah).'</td>
                <td>'.number_format($payout).'</td>
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
