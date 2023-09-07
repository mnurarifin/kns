<?php

namespace App\Controllers\Admin;

use Config\Services;


class Ref_bank extends BaseController
{

    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Master' => '#',
            'Bank' => 'Ref_bank/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_BANK;

        $this->template->title('Bank');
        $this->template->content('Admin/refBankView', $data);
        $this->template->show('Template/Admin/main');
    }

    public function excel()
    {

        $tableName = 'ref_bank';
        $joinTable = '';
        $whereCondition = '';
        $tableHead = json_decode($this->request->getPost('display'));

        $results = Services::DataTableLib()->getListDataExcel($this->request, $tableName, $joinTable, $whereCondition);

        foreach ($results as $key => $row) {
            if ($row['bank_is_active'] == 1) {
                $results[$key]['bank_is_active'] = 'Aktif';
            } else {
                $results[$key]['bank_is_active'] = 'Non Aktif';
            }
        }

        $fieldToReplace = '';
        $valueToReplace = '';

        $title = 'Daftar Data Bank';
        Services::DataTableLib()->exportToExcel($tableHead, $results, $title);
    }
}
