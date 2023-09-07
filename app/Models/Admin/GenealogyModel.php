<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class GenealogyModel extends Model
{

    function getDetailData($networkID)
    {
        $sql = "
            SELECT
            main_network.network_member_id             AS member_id,
            main_network.network_code                  AS network_code,
            main_member.member_name                    AS member_name,
            main_member.member_image                   AS member_image,
            main_member.member_mobilephone             AS member_mobilephone,
            main_member.member_join_datetime           AS member_join_datetime,
            network_sponsor_member_id                  AS sponsor_member_id,
            IFNULL(network_sponsor_network_code,'-')   AS sponsor_network_code,
            network_upline_member_id                   AS upline_member_id,
            IFNULL(network_upline_network_code,'-')    AS upline_network_code,
            network_is_active,
            network_is_suspended
            FROM
            sys_network AS main_network
            JOIN sys_member AS main_member ON main_member.member_id = main_network.network_member_id
            WHERE main_network.network_member_id = ?
        ";
        return $this->db->query($sql, [$networkID])->getRowArray();
    }

    public function getNetworkInfo($networkID)
    {
        $sql = '
            SELECT main_network.network_code AS network_code,
            main_network.network_total_downline_left AS network_total_downline_left,
            main_network.network_total_downline_right AS network_total_downline_right,
            IFNULL(upline_network.network_code,"-") AS upline_network_code,
            IFNULL(sponsor_network.network_code,"-") AS sponsor_network_code,
            IFNULL(sponsor_member.member_name,"-")     AS sponsor_member_name,
            IFNULL(upline_network.network_id,0)        AS upline_network_id,
            IFNULL(upline_network.network_code,"-")    AS upline_network_code,
            IFNULL(upline_member.member_name,"-")      AS upline_member_name,
            main_member.member_name AS member_name,
            main_member.member_image AS member_image,
            main_member.member_join_datetime AS member_join_datetime,
            main_member.member_mobilephone AS member_mobilephone
            FROM bin_network main_network
            INNER JOIN sys_member main_member ON main_member.member_id = main_network.network_member_id
            LEFT JOIN bin_network upline_network ON upline_network.network_id = main_network.network_upline_network_id
            LEFT JOIN bin_network sponsor_network ON sponsor_network.network_id = main_network.network_sponsor_network_id
            LEFT JOIN sys_member upline_member ON upline_member.member_id = upline_network.network_member_id
            LEFT JOIN sys_member sponsor_member ON sponsor_member.member_id = sponsor_network.network_member_id
            WHERE main_network.network_id = ' . $networkID;
        return $this->db->query($sql)->getRowArray();
    }

    function getNetgrowToday($id, $today)
    {
        $sql = "SELECT netgrow_master_node_left, netgrow_master_node_right 
                FROM sys_netgrow_master 
                WHERE netgrow_master_member_id = {$id} AND netgrow_master_date = '{$today}'";

        return $this->db->query($sql)->getRow();
    }

    public function getMemberMaxLevel($networkID, $position = '')
    {
        $where = "WHERE netgrow_node_network_id = '" . $networkID . "'";
        if ($position != '') {
            $where .= " AND netgrow_node_position = '" . $position . "'";
        }
        $sql = "SELECT MAX(netgrow_node_level) AS max_level FROM bin_netgrow_node " . $where;
        $query = $this->db->query($sql)->getRow();
        if ($query) {
            return $query->max_level;
        }
        return 0;
    }

    public function getMemberSponsoringCount($networkID, $position = '')
    {
        $where = "WHERE netgrow_sponsor_network_id = '" . $networkID . "'";
        if ($position != '') {
            $where .= " AND netgrow_sponsor_position = '" . $position . "'";
        }
        $sql = "SELECT COUNT(netgrow_sponsor_id) AS sponsoring_count FROM bin_netgrow_sponsor " . $where;
        $query = $this->db->query($sql)->getRow();
        if ($query) {
            return $query->sponsoring_count;
        }
        return 0;
    }

    public function getGenealogy($parent, $limit, $offset, $more = false)
    {
        $res = [];

        try {
            $select = "
			network_member_id,
			network_code,
			member_name,
			member_image,
			network_product_package_name as network_type,
			network_activation_datetime,
			network_sponsor_network_code
			";

            $lv0 = $this->db->table('sys_network')
                ->join('sys_member', 'member_id = network_member_id')->select($select)->getWhere(['network_code' => $parent])->getRowArray();

            if (empty($lv0) || is_null($lv0)) {
                $res['status'] = true;
                $res['data'] = [];
                return $res;
            }

            $lv0['member_image'] = !empty($lv0['member_image']) ? UPLOAD_URL . URL_IMG_MEMBER . $lv0['member_image'] : UPLOAD_URL . URL_IMG_MEMBER . '_default.png';

            $lv1 = $this->db->table('sys_network')
                ->join('sys_member', 'member_id = network_member_id')->select($select)
                ->limit($limit, $offset)->getWhere(['network_sponsor_network_code' => $parent])->getResult('array');

            $lv1_total = $this->db->table('sys_network')
                ->join('sys_member', 'member_id = network_member_id')->select("count(FOUND_ROWS()) as total")->getWhere(['network_sponsor_network_code' => $parent])->getRow("total");

            foreach ($lv1 as $key => $value) {
                $lv1[$key]['member_image'] =  !empty($lv1[$key]['member_image']) ? UPLOAD_URL . URL_IMG_MEMBER . $lv1[$key]['member_image'] : UPLOAD_URL . URL_IMG_MEMBER . '_default.png';

                $lv2 = $this->db->table('sys_network')
                    ->join('sys_member', 'member_id = network_member_id')->select($select)
                    ->limit($limit, $offset)->getWhere(['network_sponsor_network_code' => $value['network_code']])->getResult('array');

                $lv2_total = $this->db->table('sys_network')
                    ->join('sys_member', 'member_id = network_member_id')->select("count(FOUND_ROWS()) as total")->getWhere(['network_sponsor_network_code' => $value['network_code']])->getRow("total");

                if (!$more) {
                    foreach ($lv2 as $_key => $_value) {
                        $lv2[$_key]['member_image'] = !empty($lv2[$_key]['member_image']) ? UPLOAD_URL . URL_IMG_MEMBER . $lv2[$_key]['member_image'] : UPLOAD_URL . URL_IMG_MEMBER . '_default.png';

                        $lv3 = $this->db->table('sys_network')
                            ->join('sys_member', 'member_id = network_member_id')->select($select)
                            ->limit($limit, $offset)->getWhere(['network_sponsor_network_code' => $_value['network_code']])->getResult('array');

                        $lv3_total = $this->db->table('sys_network')
                            ->join('sys_member', 'member_id = network_member_id')->select("count(FOUND_ROWS()) as total")->getWhere(['network_sponsor_network_code' => $_value['network_code']])->getRow("total");

                        foreach ($lv3 as $__key => $__value) {
                            $lv3[$__key]['member_image'] = !empty($lv3[$__key]['member_image']) ? UPLOAD_URL . URL_IMG_MEMBER . $lv3[$__key]['member_image'] : UPLOAD_URL . URL_IMG_MEMBER . '_default.png';

                            $lv3[$__key]['level'] = 3;
                        }

                        $lv2[$_key]['level'] = 2;
                        $lv3_more = false;
                        if (count($lv3) >= $limit && $lv3_total > $limit)
                            $lv3_more = true;
                        $lv2[$_key]['children_more'] = $lv3_more;
                        $lv2[$_key]['children'] = $lv3;
                    }
                }

                $lv1[$key]['level'] = 1;
                $lv2_more = false;
                if (count($lv2) >= $limit && $lv2_total > $limit)
                    $lv2_more = true;
                $lv1[$key]['children_more'] = $lv2_more;
                if (!$more)
                    $lv1[$key]['children'] = $lv2;
            }

            $lv0['level'] = 0;
            $lv1_more = false;
            if (count($lv1) >= $limit && $lv1_total > $limit)
                $lv1_more = true;
            $lv0['children_more'] = $lv1_more;
            $lv0['children'] = $lv1;

            $res['status'] = true;
            $res['data'] = $lv0;
            if ($more)
                $res['data'] = $lv0['children'];
        } catch (\Exception $e) {
            $res['msg'] = $e->getMessage();
        }

        return $res;
    }
}
