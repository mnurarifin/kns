<?php

namespace App\Controllers\Pub;

use App\Controllers\BaseController;

class Ref extends BaseController
{
    public function index($network_slug)
    {
        if (!ctype_alnum($network_slug)) {
            $base_url = BASEURL;
            header("Location: {$base_url}/404");
            die();
        }

        $network = $this->db->table("sys_network")->join("sys_member", "member_id = network_member_id")->getWhere(["network_slug" => $network_slug]);
        if ($network == FALSE) {
            $base_url = BASEURL;
            header("Location: {$base_url}/404");
            die();
        }
        $network = $network->getRow();
        if ($network) {
            $ref = $this->db->table("sys_member")->join("sys_member_account", "member_account_member_id = member_id")
                ->join("sys_network", "network_member_id = member_id")
                ->join("ref_city", "city_id = member_city_id")
                ->join("ref_province", "province_id = member_province_id")
                ->orderBy("member_id", "ASC")
                ->select("member_name, member_account_username, member_image, city_name, network_code, member_email, member_mobilephone, province_name")->getWhere(["member_id" => $network->member_parent_member_id, "network_slug" => $network_slug])->getRow();
            $this->session->set(['ref' => $ref]);
            if ($ref) {
                if ($ref->member_image && file_exists(UPLOAD_PATH . URL_IMG_MEMBER . $ref->member_image)) {
                    $ref->member_image = UPLOAD_URL . URL_IMG_MEMBER . $ref->member_image;
                } else {
                    $ref->member_image = UPLOAD_URL . URL_IMG_MEMBER . "_default.png";
                }
            } else {
                $base_url = BASEURL;
                header("Location: {$base_url}/404");
                die();
            }
        } else {
            $base_url = BASEURL;
            header("Location: {$base_url}/404");
            die();
        }

        $data = [
            "member_name" => $ref->member_name,
            "member_image" => $ref->member_image,
            "member_email" => $ref->member_email,
            "member_mobilephone" => $ref->member_mobilephone,
            "member_city" => $ref->city_name,
            "member_province" => $ref->province_name,
            "member_code" => $ref->network_code,
        ];

        $this->template->title("Mitra");
        $this->template->breadcrumbs(["Mitra", "Mitra"]);
        $this->template->content("Pub/Registration/refView", $data);
        $this->template->show("Template/Pub/refPage");
        // $base_url = BASEURL;
        // header("Location: {$base_url}/registration");
        // die();
    }
}
