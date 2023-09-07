<?php

namespace App\Controllers\Admin\Service;

use App\Controllers\Admin\Service\BaseServiceController;
use App\Models\Admin\GenealogyModel;

class Genealogy extends BaseServiceController
{
    private $GenealogyModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->GenealogyModel = new GenealogyModel;
        $this->path = UPLOAD_URL . URL_IMG_MEMBER;
    }

    public function generateGenealogyBinary()
    {
        $topNetworkCode = $this->request->uri->getSegment(5);
        $topMemberID = 1;
        if ($topNetworkCode) {
            $topMemberID = $this->functionLib->getOne('sys_network', 'network_member_id', array('network_code' => $topNetworkCode));
            if ($topMemberID == '') {
                $this->createRespon(400, 'Mitra tidak ditemukan');
            }
        }
        $levelDepth = 3;
        $today = date("Y-m-d");
        $dataMax = 0;
        $arrLevelNodeStart = array();
        for ($i = 0; $i <= $levelDepth; $i++) {
            $pow = pow(2, $i);
            $dataMax = $dataMax + $pow;
            array_push($arrLevelNodeStart, $pow);
        }

        $arrData = array();
        $upline = $level = $levelCurrent = 0;

        for ($x = 1; $x <= $dataMax; $x++) {

            if ($x % 2 == 0) {
                $positionText = 'kiri';
                $upline++;
            } else {
                $positionText = 'kanan';
            }

            if ($x == 1) {
                $memberID = $topMemberID;
            } else {
                if (isset($arrGenea[$upline][$positionText])) {
                    $memberID = $arrGenea[$upline][$positionText];
                } else {
                    $memberID = '';
                }

                if (in_array($x, $arrLevelNodeStart)) {
                    $level++;
                    $levelCurrent++;
                }
            }

            if ($memberID == '') {
                $arrData[$x]['genealogy_sort'] = $x;
                $arrData[$x]['genealogy_status'] = 'blank';
                $arrData[$x]['genealogy_is_active'] = 0;
                $arrData[$x]['genealogy_parent'] = $upline;
                $arrData[$x]['genealogy_image'] = $this->path . 'profile_kosong.svg';
                $arrData[$x]['genealogy_link'] = '';
                $arrData[$x]['genealogy_data'] = new \stdClass;
            } else {
                $query = $this->GenealogyModel->getDetailData($memberID);
                if (!empty($query)) {
                    $getDownline = $this->GenealogyModel->getNetgrowToday($memberID, $today);
                    if (!empty($getDownline)) {
                        $totalDownlineLeftToday = $getDownline->netgrow_master_node_left;
                        $totalDownlineRightToday = $getDownline->netgrow_master_node_right;
                    } else {
                        $totalDownlineLeftToday = $totalDownlineRightToday = 0;
                    }

                    if ($query['member_image'] != '') {
                        $imagesSrc = $this->path . $query['member_image'];
                    } else {
                        $imagesSrc = $this->path . '_default.png';
                    }

                    $arrData[$x]['genealogy_sort'] = $x;
                    $arrData[$x]['genealogy_status'] = 'filled';
                    $arrData[$x]['genealogy_is_active'] = ($query['network_is_active'] == 0 || $query['network_is_suspended'] == 1) ? 0 : 1;
                    $arrData[$x]['genealogy_parent'] = $upline;
                    $arrData[$x]['genealogy_image'] = $imagesSrc;
                    $arrData[$x]['genealogy_link'] = site_url('genealogy/show?network_code=' . $query['network_code']);
                    $arrData[$x]['genealogy_data'] = array_merge($query, array('node_today_left' => $totalDownlineLeftToday, 'node_today_right' => $totalDownlineRightToday));

                    $arrGenea[$x]['kiri'] = $query['network_left_node_member_id'];
                    $arrGenea[$x]['kanan'] = $query['network_right_node_member_id'];
                } else {
                    $arrData[$x]['genealogy_sort'] = $x;
                    $arrData[$x]['genealogy_status'] = 'empty';
                    $arrData[$x]['genealogy_is_active'] = 0;
                    $arrData[$x]['genealogy_parent'] = $upline;
                    $arrData[$x]['genealogy_image'] = $this->path . 'profile_add_4.svg';
                    $arrData[$x]['genealogy_link'] = '';
                    $arrData[$x]['genealogy_data'] = new \stdClass;
                }
            }
        }

        $this->createRespon(200, 'Data Genealogy', $arrData);
    }

    // public function generateGenealogyBinaryMobile()
    // {   
    //     $data = array();

    //     try {
    //         $date = date('Y-m-d');

    //         $top_code_network = $this->request->uri->getSegment(4);
    //         $top_network_id = 1;

    //         $level_depth = 2;
    //         $data_max = 0;

    //         $upline = $level = $current_level = 0;

    //         $level_node_start = array();

    //         if ($top_code_network) {
    //             $top_network_id = $this->functionLib->getOne('sys_network', 'network_id', array('network_code' => $top_code_network));
    //         }

    //         if ($top_network_id == '') {
    //             throw new \Exception(`Mitra tidak ditemukan.`, 1);
    //         }


    //         for ($i = 0; $i <= $level_depth; $i++) {
    //             $pow = pow(2, $i);
    //             $data_max = $data_max + $pow;

    //             array_push($level_node_start, $pow);
    //         }


    //         for ($x = 1; $x <= $data_max; $x++) {

    //             if ($x % 2 == 0) {
    //                 $text_position = 'kiri';
    //                 $upline++;
    //             } else {
    //                 $text_position = 'kanan';
    //             }


    //             if ($x == 1) {
    //                 $network_id = $top_network_id;
    //             } else {

    //                 if (isset($geneology[$upline][$text_position])) {
    //                     $network_id = $geneology[$upline][$text_position];
    //                 } else {
    //                     $network_id = '';
    //                 }

    //                 if (in_array($x, $level_node_start)) {
    //                     $level++;
    //                     $current_level++;
    //                 }
    //             }

    //             if ($network_id == '') {
    //                 $data[$x]['genealogy_sort'] = $x;
    //                 $data[$x]['genealogy_status'] = 'blank';
    //                 $data[$x]['genealogy_is_active'] = 0;
    //                 $data[$x]['genealogy_parent'] = $upline;
    //                 $data[$x]['genealogy_image'] = $this->path . 'profile_kosong.svg';
    //                 $data[$x]['genealogy_link'] = '';
    //                 $data[$x]['genealogy_data'] = new \stdClass;
    //             } else {
    //                 $detail = $this->GenealogyModel->getDetailData($network_id);

    //                 if (!empty($detail)) {
    //                     $get_downline = $this->GenealogyModel->getNetgrowToday($network_id, $date);

    //                     if(!empty($get_downline)){
    //                         $total_downline_left_today = $get_downline->netgrow_master_node_left;
    //                         $total_downline_right_today = $get_downline->netgrow_master_node_right;
    //                     } else {
    //                         $total_downline_left_today = $total_downline_right_today = 0;
    //                     }

    //                     if ($detail['member_image'] != '') {
    //                         $images = $this->path . $detail['member_image'];
    //                     }else {
    //                         $images = $this->path . '_default.jpg';
    //                     }

    //                     $data[$x]['genealogy_sort'] = $x;
    //                     $data[$x]['genealogy_status'] = 'filled';
    //                     $data[$x]['genealogy_is_active'] = ($detail['network_is_active'] == 0 || $detail['network_is_suspended']== 1 ) ? 0 : 1;
    //                     $data[$x]['genealogy_parent'] = $upline;
    //                     $data[$x]['genealogy_image'] = $images;
    //                     $data[$x]['genealogy_link'] = site_url('genealogy/show?network_code='.$detail['network_code']);
    //                     $data[$x]['genealogy_data'] = array_merge($detail, array('node_today_left' => $total_downline_left_today, 'node_today_right' => $total_downline_right_today));

    //                     $geneology[$x]['kiri'] = $detail['network_left_node_network_id'];
    //                     $geneology[$x]['kanan'] = $detail['network_right_node_network_id'];

    //                 } else {
    //                     $data[$x]['genealogy_sort'] = $x;
    //                     $data[$x]['genealogy_status'] = 'empty';
    //                     $data[$x]['genealogy_is_active'] = 0;
    //                     $data[$x]['genealogy_parent'] = $upline;
    //                     $data[$x]['genealogy_image'] = $this->path . 'profile_add_4.svg';
    //                     $data[$x]['genealogy_link'] = '';
    //                     $data[$x]['genealogy_data'] = new \stdClass;
    //                 }
    //             }
    //         }

    //        $this->createRespon(200, 'Data Genealogy', $data);

    //     } catch (\Throwable $th) {
    //         $this->createRespon(400,$th->getMessage(),$data);
    //     }
    // }

    public function getMemberInfo($networkCode)
    {
        $memberID = $this->functionLib->getOne('sys_network', 'network_id', array('network_code' => $networkCode));
        if (!empty($memberID)) {
            $data['arrData'] = $this->GenealogyModel->getNetworkInfo($memberID);
            $data['arrData']['maxLevelLeft'] = $this->GenealogyModel->getMemberMaxLevel($memberID, 'L');
            $data['arrData']['maxLevelRight'] = $this->GenealogyModel->getMemberMaxLevel($memberID, 'R');
            $data['arrData']['sponsoringCount'] = $this->GenealogyModel->getMemberSponsoringCount($memberID);

            $this->createRespon(200, 'Data Mitra', $data);
        } else {
            $this->createRespon(400, 'Mitra tidak ditemukan.');
        }
    }

    public function generateGeneology()
    {
        try {
            if (!empty($this->request->getGet()))
                $data = $this->request->getGet();
            else
                $data = (array)$this->request->getJSON();

            $limit = 5;
            if (array_key_exists('limit', $data))
                $limit = $data['limit'];
            $offset = 0;
            if (array_key_exists('offset', $data))
                $offset = $data['offset'];

            $parent = 'KNS1000001';
            if (array_key_exists('parent', $data))
                $parent = $data['parent'];

            $genealogy = $this->GenealogyModel->getGenealogy($parent, $limit, $offset);

            if (!$genealogy['status'])
                throw new \Exception($genealogy['msg'], 1);

            $this->createRespon(200, 'Data Geneology', $genealogy['data']);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage(), $data);
        }
    }

    public function generateGeneologyMore()
    {
        try {
            if (!empty($this->request->getGet()))
                $data = $this->request->getGet();
            else
                $data = (array)$this->request->getJSON();

            $limit = 5;
            if (array_key_exists('limit', $data))
                $limit = $data['limit'];
            $offset = 0;
            if (array_key_exists('offset', $data))
                $offset = $data['offset'];

            $parent = 'KNS1000001';
            if (array_key_exists('parent', $data))
                $parent = $data['parent'];

            $genealogy = $this->GenealogyModel->getGenealogy($parent, $limit, $offset, true);

            if (!$genealogy['status'])
                throw new \Exception($genealogy['msg'], 1);

            $this->createRespon(200, 'Data Geneology', $genealogy['data']);
        } catch (\Throwable $th) {
            $this->createRespon(400, $th->getMessage(), $data);
        }
    }
}
