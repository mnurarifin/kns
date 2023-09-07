<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Content extends BaseController
{
	public function show()
	{
		$data['arrBreadcrumbs'] = array(
			'Pengelolaan Web' => '#',
			'Artikel' => 'admin/content/show'
		);
		$this->template->title('Artikel');
		$this->template->content('Admin/contentView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function category()
	{
		$data['arrBreadcrumbs'] = array(
			'Pengelolaan Web' => '#',
			'Kategori Artikel' => 'content/category'
		);
		$this->template->title('Kategori Artikel');
		$this->template->content('Admin/contentCategoryView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function add()
	{
		$data['arrBreadcrumbs'] = array(
			'Pengelolaan Web' => '#',
			'Tambah Artikel' => 'content/add'
		);

		$this->template->title('Tambah Artikel');
		$this->template->content('Admin/contentAddView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function edit($content_id)
	{
		$data['content_id'] = $content_id;
		$data['arrBreadcrumbs'] = array(
			'Pengelolaan Web' => '#',
			'Edit Artikel' => 'content/edit'
		);

		$data['imagePath'] = UPLOAD_URL . URL_IMG_CONTENT;
		$this->template->title('Edit Artikel');
		$this->template->content('Admin/contentEditView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function business_plan()
	{
		$data['arrBreadcrumbs'] = array(
			'Pengelolaan Web' => '#',
			'Business Plan' => 'content/business_plan'
		);
		$this->template->title('Business Plan');
		$this->template->content('Admin/businessPlanView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function add_business()
	{
		$data['arrBreadcrumbs'] = array(
			'Pengelolaan Web' => '#',
			'Tambah Business Plan' => 'content/add'
		);

		$this->template->title('Tambah Business Plan');
		$this->template->content('Admin/businessPlanAddView', $data);
		$this->template->show('Template/Admin/main');
	}

	public function edit_business($content_id)
	{
		$data['content_id'] = $content_id;
		$data['arrBreadcrumbs'] = array(
			'Pengelolaan Web' => '#',
			'Edit Business Plan' => 'content/edit'
		);

		$this->template->title('Edit Business Plan');
		$this->template->content('Admin/businessPlanEditView', $data);
		$this->template->show('Template/Admin/main');
	}
}
