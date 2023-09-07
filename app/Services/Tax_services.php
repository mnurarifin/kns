<?php

namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Common_model;

class Tax_services extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->common_model = new Common_model();
    }

    public function init_tax_member($member_id, $tax_year, $tax_no, $datetime)
    {
        $arr_data = [];
        $arr_data['tax_member_member_id'] = $member_id;
        $arr_data['tax_member_year'] = $tax_year;
        $arr_data['tax_member_income'] = 0;
        $arr_data['tax_member_income_pkp'] = 0;
        $arr_data['tax_member_tax_npwp_value'] = 0;
        $arr_data['tax_member_tax_nonnpwp_value'] = 0;
        $arr_data['tax_member_tax_real_value'] = 0;
        $arr_data['tax_member_tax_difference_value'] = 0;
        $arr_data['tax_member_tax_no'] = $tax_no;
        $arr_data['tax_member_arr_data'] = '[]';
        $arr_data['tax_member_last_update_datetime'] = $datetime;

        if ($this->common_model->insertData('tax_member', $arr_data) == FALSE) {
            throw new \Exception("Gagal menambah data pajak.", 1);
        }

        return $arr_data;
    }

    // public function calculate_tax_member($member_id, $income, $tax_year, $tax_date, $tax_no, $datetime, $ref_id = null)
    // {
    //     $is_npwp = ($tax_no != '') ? TRUE : FALSE;
    //     $obj_tax_member = $this->db->table('tax_member')->select('*')->where(['tax_member_member_id' => $member_id, 'tax_member_year' => $tax_year])->get()->getRow();
    //     if (is_null($obj_tax_member)) {
    //         $obj_tax_member = (object)$this->init_tax_member($member_id, $tax_year, $tax_no, $datetime);
    //     }
    //
    //     $arr_tax_data = (array)json_decode($obj_tax_member->tax_member_arr_data);
    //     $income_acc = $obj_tax_member->tax_member_income + $income;
    //     $income_pkp = 0.5 * $income;
    //     $income_pkp_acc = $obj_tax_member->tax_member_income_pkp + $income_pkp;
    //     $tax_value_acc = getCalculateAnnuallyTaxPPh21Value($income_pkp_acc, $is_npwp);
    //     $tax_value = $tax_value_acc - $obj_tax_member->tax_member_tax_value;
    //     $arr_tax_data_add = [[
    //         'ref_id' => $ref_id,
    //         'date' => $tax_date,
    //         'income' => $income,
    //         'income_pkp' => $income_pkp,
    //         'income_pkp_acc' => $income_pkp_acc,
    //         'tax_value' => $tax_value,
    //         'tax_value_acc' => $tax_value_acc,
    //     ]];
    //
    //     //tambahkan data potongan pajak ke array data sebelumnya
    //     $arr_tax_data_merge = array_merge($arr_tax_data, $arr_tax_data_add);
    //
    //     return [
    //         'tax_value' => $tax_value,
    //         'total_income' => $income_acc,
    //         'total_income_pkp' => $income_pkp_acc,
    //         'total_tax_value' => $tax_value_acc,
    //         'arr_tax_data' => $arr_tax_data_merge,
    //     ];
    // }

    // public function update_tax_member($member_id, $income, $tax_year, $tax_date, $tax_no, $datetime, $ref_id = null)
    // {
    //     $arr_calculate_tax = $this->calculate_tax_member($member_id, $income, $tax_year, $tax_date, $tax_no, $datetime, $ref_id);
    //
    //     $arr_data = [];
    //     $arr_data['tax_member_income'] = $arr_calculate_tax['total_income'];
    //     $arr_data['tax_member_income_pkp'] = $arr_calculate_tax['total_income_pkp'];
    //     $arr_data['tax_member_tax_value'] = $arr_calculate_tax['total_tax_value'];
    //     $arr_data['tax_member_tax_difference_value'] = 0;
    //     $arr_data['tax_member_tax_no'] = $tax_no;
    //     $arr_data['tax_member_arr_data'] = json_encode($arr_calculate_tax['arr_tax_data']);
    //     $arr_data['tax_member_last_update_datetime'] = $datetime;
    //
    //     $builder = $this->db->table('tax_member');
    //     $builder->where('tax_member_member_id', $member_id);
    //     $builder->where('tax_member_year', $tax_year);
    //     $builder->update($arr_data);
    //     if ($this->db->affectedRows() <= 0) {
    //         throw new \Exception("Gagal mengubah data pajak.", 1);
    //     }
    //
    //     return [
    //         'tax_value' => $arr_calculate_tax['tax_value'],
    //         'total_income' => $arr_calculate_tax['total_income'],
    //         'total_income_pkp' => $arr_calculate_tax['total_income_pkp'],
    //         'total_tax_value' => $arr_calculate_tax['total_tax_value'],
    //         'arr_tax_data' => $arr_calculate_tax['arr_tax_data'],
    //     ];
    // }

    public function calculate_tax_member($member_id, $income, $tax_year, $tax_date, $tax_no, $datetime, $ref_id = null)
    {
        $is_npwp = ($tax_no != '') ? TRUE : FALSE;
        $obj_tax_member = $this->db->table('tax_member')->select('*')->where(['tax_member_member_id' => $member_id, 'tax_member_year' => $tax_year])->get()->getRow();
        if (is_null($obj_tax_member)) {
            $obj_tax_member = (object)$this->init_tax_member($member_id, $tax_year, $tax_no, $datetime);
        }

        $arr_tax_data = (array)json_decode($obj_tax_member->tax_member_arr_data);
        $income_acc = $obj_tax_member->tax_member_income + $income;
        $income_pkp = 0.5 * $income;
        $income_pkp_acc = $obj_tax_member->tax_member_income_pkp + $income_pkp;

        //npwp
        $tax_npwp_value_acc = getCalculateAnnuallyTaxPPh21Value($income_pkp_acc, TRUE);
        $tax_npwp_value = $tax_npwp_value_acc - $obj_tax_member->tax_member_tax_npwp_value;

        //nonnpwp
        $tax_nonnpwp_value_acc = getCalculateAnnuallyTaxPPh21Value($income_pkp_acc, FALSE);
        $tax_nonnpwp_value = $tax_nonnpwp_value_acc - $obj_tax_member->tax_member_tax_nonnpwp_value;

        //real
        $tax_real_value_acc = ($is_npwp == TRUE) ? $obj_tax_member->tax_member_tax_real_value + $tax_npwp_value : $obj_tax_member->tax_member_tax_real_value + $tax_nonnpwp_value;
        $tax_real_value = ($is_npwp == TRUE) ? $tax_npwp_value : $tax_nonnpwp_value;

        $arr_tax_data_add = [[
            'ref_id' => $ref_id,
            'date' => $tax_date,
            'income' => $income,
            'income_pkp' => $income_pkp,
            'income_pkp_acc' => $income_pkp_acc,
            'tax_npwp_value' => $tax_npwp_value,
            'tax_npwp_value_acc' => $tax_npwp_value_acc,
            'tax_nonnpwp_value' => $tax_nonnpwp_value,
            'tax_nonnpwp_value_acc' => $tax_nonnpwp_value_acc,
            'tax_real_value' => $tax_real_value,
            'tax_real_value_acc' => $tax_real_value_acc,
        ]];

        //tambahkan data potongan pajak ke array data sebelumnya
        $arr_tax_data_merge = array_merge($arr_tax_data, $arr_tax_data_add);

        return [
            'tax_npwp_value' => $tax_npwp_value,
            'tax_nonnpwp_value' => $tax_nonnpwp_value,
            'tax_real_value' => $tax_real_value,
            'total_income' => $income_acc,
            'total_income_pkp' => $income_pkp_acc,
            'total_tax_npwp_value' => $tax_npwp_value_acc,
            'total_tax_nonnpwp_value' => $tax_nonnpwp_value_acc,
            'total_tax_real_value' => $tax_real_value_acc,
            'arr_tax_data' => $arr_tax_data_merge,
        ];
    }

    public function update_tax_member($member_id, $income, $tax_year, $tax_date, $tax_no, $datetime, $ref_id = null)
    {
        $arr_calculate_tax = $this->calculate_tax_member($member_id, $income, $tax_year, $tax_date, $tax_no, $datetime, $ref_id);

        $arr_data = [];
        $arr_data['tax_member_income'] = $arr_calculate_tax['total_income'];
        $arr_data['tax_member_income_pkp'] = $arr_calculate_tax['total_income_pkp'];
        $arr_data['tax_member_tax_npwp_value'] = $arr_calculate_tax['total_tax_npwp_value'];
        $arr_data['tax_member_tax_nonnpwp_value'] = $arr_calculate_tax['total_tax_nonnpwp_value'];
        $arr_data['tax_member_tax_real_value'] = $arr_calculate_tax['total_tax_real_value'];
        $arr_data['tax_member_tax_difference_value'] = 0;
        $arr_data['tax_member_tax_no'] = $tax_no;
        $arr_data['tax_member_arr_data'] = json_encode($arr_calculate_tax['arr_tax_data']);
        $arr_data['tax_member_last_update_datetime'] = $datetime;

        $builder = $this->db->table('tax_member');
        $builder->where('tax_member_member_id', $member_id);
        $builder->where('tax_member_year', $tax_year);
        $builder->update($arr_data);
        if ($this->db->affectedRows() <= 0) {
            throw new \Exception("Gagal mengubah data pajak.", 1);
        }

        return [
            'tax_npwp_value' => $arr_calculate_tax['tax_npwp_value'],
            'tax_nonnpwp_value' => $arr_calculate_tax['tax_nonnpwp_value'],
            'tax_real_value' => $arr_calculate_tax['tax_real_value'],
            'total_income' => $arr_calculate_tax['total_income'],
            'total_income_pkp' => $arr_calculate_tax['total_income_pkp'],
            'total_tax_npwp_value' => $arr_calculate_tax['total_tax_npwp_value'],
            'total_tax_nonnpwp_value' => $arr_calculate_tax['total_tax_nonnpwp_value'],
            'total_tax_real_value' => $arr_calculate_tax['total_tax_real_value'],
            'arr_tax_data' => $arr_calculate_tax['arr_tax_data'],
        ];
    }
}
