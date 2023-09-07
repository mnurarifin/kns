<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Product extends BaseController
{
    public function show()
    {
        $data['arrBreadcrumbs'] = array(
            'Produk' => '#',
            'Data Produk' => 'Produk/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_PRODUCT;

        $this->template->title('Data Produk');
        $this->template->content("Admin/productView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function package()
    {
        $data['arrBreadcrumbs'] = array(
            'Produk' => '#',
            'Paket Produk' => 'package/show'
        );

        $data['imagePath'] = UPLOAD_URL . URL_IMG_PRODUCT;

        $this->template->title('Paket Produk');
        $this->template->content("Admin/productPackageView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function warehouse()
    {
        $data['arrBreadcrumbs'] = array(
            'Produk' => '#',
            'Gudang' => 'warehouse/show'
        );

        $this->template->title('Gudang');
        $this->template->content("productWarehouseView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function summary_stock()
    {
        $data['arrBreadcrumbs'] = array(
            'Produk' => '#',
            'Stok' => 'stock/show'
        );

        $this->template->title('Stok');
        $this->template->content("Admin/productStockView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function stock_log()
    {
        $data['arrBreadcrumbs'] = array(
            'Produk' => '#',
            'Riwayat Stok' => 'stock-log/show'
        );

        $this->template->title('Riwayat stok');
        $this->template->content("Admin/productStockLogView", $data);
        $this->template->show('Template/Admin/main');
    }

    public function receivement()
    {
        $data['arrBreadcrumbs'] = array(
            'Produk' => '#',
            'Gudang' => 'receivement/show'
        );

        $this->template->title('Penerimaan');
        $this->template->content("productReceivementView", $data);
        $this->template->show('Template/Admin/main');
    }
}
