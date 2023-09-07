<?php

namespace App\Models\Admin;

use CodeIgniter\Model;
use Firebase\JWT\JWT;
use stdClass;

class MemberModel extends Model
{
    public function getMember($member_id)
    {
        $sql = "
        SELECT *
        FROM sys_member
        WHERE member_id = {$member_id}
        ";

        return $this->db->query($sql)->getRow();
    }

    public function getNetwork($member_id)
    {
        $sql = "
        SELECT *
        FROM sys_network
        WHERE network_member_id = {$member_id}
        ";

        return $this->db->query($sql)->getRow();
    }

    // public function getOptionMember()
    // {
    //     $sql_member = "
    //     SELECT
    //         member_id,
    //         member_name,
    //         member_mobilephone,
    //         IFNULL(network_id,'') AS member_network_id,
    //         IFNULL(network_code,'') AS member_network_code
    //     FROM sys_member
    //     LEFT JOIN bin_member_ref ON member_ref_member_id = member_id
    //     LEFT JOIN bin_network ON network_id = member_ref_network_id
    //     ";

    //     return $this->db->query($sql_member)->getResult();
    // }

    // public function getDetailNetwork($member_id)
    // {
    //     $sql = "
    //     SELECT
    //     member.member_id,
    //     member.member_name,
    //     member_account_username,
    //     sponsor.member_ref_member_id,
    //     ifnull(sponsor.member_ref_network_code, '-') as kodeSponsor,
    //     upline.member_ref_member_id,
    //     ifnull(upline.member_ref_network_code, '-') as kodeUpline,
    //     ifnull(sponsorName.member_name, '-') as namaSponsor,
    //     ifnull(uplineName.member_name, '-') as namaUpline,
    //     bin_network.network_position as position
    //     FROM
    //     `bin_network`
    //     JOIN sys_member AS member
    //     ON
    //     member.member_id = network_member_id
    //     JOIN bin_member_ref ON member_ref_network_id = network_id

    //     LEFT JOIN bin_member_ref AS sponsor
    //     ON
    //     network_sponsor_network_id = sponsor.member_ref_network_id

    //     LEFT JOIN sys_member AS sponsorName
    //     ON
    //     sponsorName.member_id = sponsor.member_ref_member_id

    //     LEFT JOIN bin_member_ref AS upline
    //     ON
    //     network_upline_network_id = upline.member_ref_network_id

    //     LEFT JOIN sys_member AS uplineName
    //     ON
    //     uplineName.member_id = upline.member_ref_member_id

    //     JOIN sys_member_account 
    //     ON 
    //     member_account_member_id = network_member_id

    //     WHERE
    //     member.member_id = {$member_id}
    //     ";
    //     return $this->db->query($sql)->getRow();
    // }

    public function activeMember($id)
    {
        $sql = "
            UPDATE sys_member
            JOIN sys_network ON sys_network.network_member_id = sys_member.member_id
            SET sys_member.member_status = 1,
                sys_network.network_is_active = 1,
                sys_network.network_is_suspended = 0
            WHERE sys_member.member_id = '$id'
        ";
        return $this->db->query($sql);
    }

    public function deactiveMember($id)
    {
        $sql = "
            UPDATE sys_member
            JOIN sys_network ON sys_network.network_member_id = sys_member.member_id
            SET sys_member.member_status = 0,
                sys_network.network_is_active = 0,
                sys_network.network_is_suspended = 1
            WHERE sys_member.member_id = '$id'
        ";
        return $this->db->query($sql);
    }

    public function suspendMember($id)
    {
        $sql = "
            UPDATE sys_member
            JOIN sys_network ON sys_network.network_member_id = sys_member.member_id
            SET sys_member.member_status = 2,
                sys_network.network_is_active = 0,
                sys_network.network_is_suspended = 1
            WHERE sys_member.member_id = '$id'
        ";
        return $this->db->query($sql);
    }

    public function unsuspendMember($id)
    {
        $sql = "
            UPDATE sys_member
            JOIN sys_network ON sys_network.network_member_id = sys_member.member_id
            SET sys_member.member_status = 1,
                sys_network.network_is_active = 1,
                sys_network.network_is_suspended = 0
            WHERE sys_member.member_id = '$id'
        ";
        return $this->db->query($sql);
    }

    public function deleteMember($id)
    {
        $sql = "
        UPDATE sys_member
        JOIN sys_network ON sys_network.network_member_id = sys_member.member_id
        SET sys_member.member_status = 3,
            sys_network.network_is_active = 0,
            sys_network.network_is_suspended = 1
        WHERE sys_member.member_id = '$id'
        ";
        return $this->db->query($sql);
    }

    // public function getCityProvinceOptions()
    // {
    //     $data = array();
    //     $child = array();
    //     $listData = array();

    //     $sql = "SELECT *
    //     FROM ref_province
    //     ORDER BY province_name ASC
    //     ";

    //     $query = $this->db->query($sql);

    //     if (!empty($query)) {
    //         $data['status'] = true;
    //         foreach ($query->getResult() as $key => $value) {
    //             $city =
    //                 " SELECT *
    //             FROM ref_city
    //             WHERE city_province_id = $value->province_id
    //             ORDER BY city_name ASC 
    //             ";
    //             $queryCity = $this->db->query($city);
    //             $city = $queryCity->getResult();
    //             $res = array(
    //                 "province_id" => $value->province_id,
    //                 "province_name" => $value->province_name,
    //                 "City" => $city
    //             );
    //             $listData[] = $res;
    //         }
    //         $data['result'] = $listData;
    //     } else {
    //         $data['status'] = false;
    //         $data['result'] = array();
    //     }
    //     return $data;
    // }

    public function getBankOptions()
    {
        $sql = "
        SELECT bank_name, bank_id
        FROM ref_bank
        WHERE bank_is_active = 1
        ";

        return $this->db->query($sql)->getResult('array');
    }

    public function getBankName($id)
    {
        $sql = "
        SELECT bank_name
        FROM ref_bank
        WHERE bank_is_active = 1 AND bank_id = $id
        ";

        return $this->db->query($sql)->getRow()->bank_name;
    }

    public function getTokenLogin($id, $ip_address, $administrator_id)
    {
        $current_datetime = date('Y-m-d H:i:s');

        $token_created = time();
        $token_duration = JWT_LIFE_TIME;
        $token_expired = $token_created + $token_duration;

        try {
            $member = $this->db->table('sys_member')->select("
                member_id,
                member_name,
                member_is_expired,
                member_status,
                member_image,
                network_code,
                IFNULL(network_type_id,0) AS member_network_type_id,
                IFNULL(network_type,'') AS member_network_type,
            ")
                ->join('sys_network', 'network_member_id = member_id', 'left')
                ->getWhere(['member_id' => $id])
                ->getRow();;

            if ($member->member_is_expired == 1 and $member->member_status == 0) {
                throw new \Exception('Pengguna tidak aktif.', 1);
            }

            $data_token = [
                'member_id' => $member->member_id,
                'network_id' => $member->member_network_id,
                'network_code' => $member->member_network_code,
                'network_updated' => $member->member_network_updated,
                'created' => $current_datetime,
                'expired' => date('Y-m-d H:i:s', $token_expired)
            ];

            $jwt_token = JWT::encode($data_token,  JWT_KEY);

            if (empty($jwt_token)) {
                throw new \Exception("Proses gagal silahkan coba kembali.", 1);
            }

            $data_access_log = [
                'member_access_log_member_id' => $member->member_id,
                'member_access_log_token' => $jwt_token,
                'member_access_log_ip_address' =>  $ip_address,
                'member_access_log_login_datetime' => $current_datetime,
                'member_access_log_token_created' => $token_created,
                'member_access_log_token_expired' => $token_expired
            ];

            $this->db->table('sys_member_access_log')->insert($data_access_log);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Proses gagal silahkan coba kembali.", 1);
            }

            $data_auth = [
                'auth_member_id' => $member->member_id,
                'auth_data' => json_encode($data_token),
                'auth_created' => $current_datetime,
                'auth_expired' => date('Y-m-d H:i:s', $token_expired)
            ];

            $this->db->table('sys_auth')->insert($data_auth);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Proses gagal silahkan coba kembali.", 1);
            }

            $result = array();
            $result['status'] = TRUE;
            $result['msg'] = "Data ditemukan.";

            $member->token = $jwt_token;
            $result['data'] = $member;
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $result['status'] = FALSE;
            $result['msg'] = $th->getMessage();
        }

        return $result;
    }

    // public function getMemberById($member_id)
    // {
    //     $sql_profile = "
    //      SELECT 
    //          member_id,
    //          member_name,
    //          member_email,
    //          member_mobilephone,
    //          member_gender,
    //          member_birth_place,
    //          member_birth_date,
    //          member_address,
    //          member_subdistrict_id,
    //          member_city_id,
    //          member_province_id,
    //          member_country_id,
    //          member_bank_id,
    //          member_bank_name,
    //          member_bank_account_name,
    //          member_bank_account_no,
    //          member_bank_city,
    //          member_bank_branch,
    //          member_identity_type,
    //          member_identity_no,
    //          member_identity_image,
    //          member_image,
    //          member_mother_name,
    //          member_devisor_name,
    //          member_devisor_relation,
    //          member_join_datetime,
    //          member_activation_datetime,
    //          member_expired_datetime,
    //          member_is_network,
    //          member_is_expired,
    //          member_status,
    //          IFNULL(member_ref_network_code,'') AS member_ref_network_code,
    //          IFNULL(network_id,'') AS member_network_id,
    //          IFNULL(network_code,'') AS member_network_code,
    //          IFNULL(network_type_id,'') AS member_network_type_id,
    //          IFNULL(network_type,'') AS member_network_type,
    //          IFNULL(network_sponsor_network_id,'') AS member_network_sponsor_network_id,
    //          IFNULL(network_sponsor_network_code,'') AS member_network_sponsor_network_code,
    //          IFNULL(network_upline_network_id,'') AS member_network_upline_network_id,
    //          IFNULL(network_upline_network_code,'') AS member_network_upline_network_code,
    //          IFNULL(network_position,'') AS member_network_position,
    //          IFNULL(network_left_node_network_id,'') AS member_network_left_node_network_id,
    //          IFNULL(network_right_node_network_id,'') AS member_network_right_node_network_id,
    //          IFNULL(network_total_sponsoring,'') AS member_network_total_sponsoring,
    //          IFNULL(network_activation_datetime,'') AS member_network_activation_datetime
    //      FROM sys_member
    //      LEFT JOIN bin_member_ref ON member_ref_member_id = member_id
    //      LEFT JOIN bin_network ON network_id = member_ref_network_id
    //      WHERE member_id = '$member_id'
    //    ";

    //     $result_profile = $this->db->query($sql_profile)->getRow();


    //     if ($result_profile) {
    //         $sql_city = "
    //          SELECT 
    //              city_name AS member_city_name
    //          FROM ref_city
    //          WHERE city_id = '{$result_profile->member_city_id}'
    //          ";

    //         $result_profile->member_city_name = !empty($result_profile->member_city_id) ? $this->db->query($sql_city)->getRow('member_city_name') : '';

    //         $sql_province = "
    //          SELECT 
    //              province_name AS member_province_name
    //          FROM ref_province
    //          WHERE province_id = '{$result_profile->member_province_id}'
    //          ";

    //         $result_profile->member_province_name = $result_profile->member_province_id ? $this->db->query($sql_province)->getRow('member_province_name') : '';

    //         $sql_country = "
    //              SELECT 
    //                  country_name AS member_country_name
    //              FROM ref_country
    //              WHERE country_id = '{$result_profile->member_country_id}'
    //          ";

    //         $result_profile->member_country_name = $result_profile->member_country_id ? $this->db->query($sql_country)->getRow('member_country_name') : '';

    //         $sql_subdistrict = "
    //             SELECT 
    //                 subdistrict_name AS member_subdistrict_name
    //             FROM ref_subdistrict
    //             WHERE subdistrict_id = '{$result_profile->member_subdistrict_id}'
    //          ";

    //         $result_profile->member_subdistrict_name = $result_profile->member_subdistrict_id ? $this->db->query($sql_subdistrict)->getRow('member_subdistrict_name') : '';


    //         $result_profile->member_image_url = !empty($result_profile->member_image) ? getenv('UPLOAD_URL') . URL_IMG_MEMBER . $result_profile->member_image : '';
    //         $result_profile->member_identity_image_url = !empty($result_profile->member_identity_image) ? getenv('UPLOAD_URL') . URL_IMG_MEMBER . $result_profile->member_identity_image : '';
    //     }

    //     return $result_profile;
    // }
    // public function getBefore($id)
    // {
    //     $sql_data_member = "
    //         SELECT 
    //             member_id,
    //             member_name,
    //             IFNULL(member_email,'') AS member_email,
    //             IFNULL(member_mobilephone,'') AS member_mobilephone,
    //             IFNULL(member_gender,'') AS member_gender,
    //             IFNULL(member_birth_place,'') AS member_birth_place,
    //             IFNULL(member_birth_date,'') AS member_birth_date,
    //             IFNULL(member_address,'') AS member_address,
    //             IFNULL(member_city_id,'') AS member_city_id,
    //             IFNULL(member_province_id,'') AS member_province_id,
    //             IFNULL(member_country_id,'') AS member_country_id,
    //             IFNULL(member_bank_id,'') AS member_bank_id,
    //             IFNULL(member_bank_name,'') AS member_bank_name,
    //             IFNULL(member_bank_account_name,'') AS member_bank_account_name,
    //             IFNULL(member_bank_account_no,'') AS member_bank_account_no,
    //             IFNULL(member_bank_city,'') AS member_bank_city,
    //             IFNULL(member_bank_branch,'') AS member_bank_branch,
    //             IFNULL(member_identity_type,'') AS member_identity_type,
    //             IFNULL(member_identity_no,'') AS member_identity_no,
    //             IFNULL(member_identity_image,'') AS member_identity_image,
    //             IFNULL(member_image,'') AS member_image,
    //             IFNULL(member_mother_name,'') AS member_mother_name,
    //             IFNULL(member_devisor_name,'') AS member_devisor_name,
    //             IFNULL(member_devisor_relation,'') AS member_devisor_relation,
    //             IFNULL(member_devisor_mobilephone,'') AS member_devisor_mobilephone,
    //             IFNULL(member_join_datetime,'') AS member_join_datetime,
    //             IFNULL(member_expired_datetime,'') AS member_expired_datetime,
    //             member_is_network,
    //             member_is_expired,
    //             member_status,
    //             member_ref_network_id,
    //             member_ref_network_code
    //         FROM sys_member
    //         LEFT JOIN bin_member_ref ON member_ref_member_id = member_id
    //         WHERE member_id = '{$id}'
    //         AND member_is_expired = '0'
    //         AND member_status = '1'
    //         ";

    //     $data_member = $this->db->query($sql_data_member)->getRow();
    //     return $data_member;
    // }

    public function getProvince()
    {
        $sql = "
            SELECT  
                province_id,
                province_name,
                province_latitude,
                province_longitude,
                province_is_active
            FROM ref_province 
            WHERE province_is_active = '1'
        ";

        $data = $this->db->query($sql)->getResult();

        return $data;
    }

    public function getCityByProvince($province_id)
    {

        $sql = "
            SELECT  
                city_id,
                city_province_id,
                city_name,
                city_latitude,
                city_longitude
            FROM ref_city 
            WHERE city_is_active = '1'
            AND city_province_id = '$province_id'
        ";

        $data = $this->db->query($sql)->getResult();

        return $data;
    }

    public function getMemberOptions()
    {
        $sql_member = "
        SELECT
            member_id,
            member_name,
            member_mobilephone,
            IFNULL(network_code,'') AS member_network_code
        FROM sys_member
        LEFT JOIN sys_network ON network_member_id = member_id
        ";

        return $this->db->query($sql_member)->getResult();
    }

    public function getStockistOptions($like = '')
    {
        $data =  $this->db
            ->table('inv_stockist')
            ->select("
            member_id AS id,
            CONCAT(member_account_username, ' - ', member_name, ' - ', member_mobilephone) AS text
            ")
            ->where('stockist_type', "master")
            ->join('sys_member', 'member_id = stockist_member_id')
            ->join('sys_member_account', 'member_account_member_id = member_id')
            ->limit(10);

        if ($like) {
            $data->orlike('network_code', $like)
                ->orLike('member_name', $like)
                ->orLike('member_mobilephone', $like);
        }

        $result = $data->get()->getResult();

        return $result;
    }

    public function getStockistMobileOptions($like = '')
    {
        $data =  $this->db
            ->table('inv_stockist')
            ->select("
            member_id AS id,
            CONCAT(member_account_username, ' - ', member_name, ' - ', member_mobilephone) AS text
            ")
            ->where('stockist_type', "mobile")
            ->join('sys_member', 'member_id = stockist_member_id')
            ->join('sys_member_account', 'member_account_member_id = member_id')
            ->limit(10);

        if ($like) {
            $data->orlike('network_code', $like)
                ->orLike('member_name', $like)
                ->orLike('member_mobilephone', $like);
        }

        $result = $data->get()->getResult();

        return $result;
    }
}
