<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ContentModel extends Model
{
    public function getContentCategory()
    {
        $sql = "
        SELECT
            content_category_id,
            content_category_name,
            content_category_slug,
            content_category_is_active,
            content_category_is_one
        FROM site_content_category
        ";
        return $this->db->query($sql)->getResult();
    }

    public function insertPage($data)
    {
        $sql = 'INSERT INTO cms_pages (page_title,page_slug,page_content,page_author_id,page_author_name,page_author_image,page_input_datetime,page_update_datetime) VALUES(?,?,?,?,?,?,?,?)';
        $this->query($sql, $data);
    }

    public function updatePage($data)
    {
        $sql = 'UPDATE cms_pages SET page_title = ?, page_slug = ?, page_content = ?, page_author_id = ?, page_author_name = ?, page_author_image = ?, page_update_datetime = ? WHERE page_id = ?';
        $this->query($sql, $data);
    }

    public function getPage($pageID)
    {
        $sql = 'SELECT page_id,page_title,page_content,page_slug FROM cms_pages WHERE page_id=?';
        $query = $this->query($sql, [$pageID])->getRow();
        return $query;
    }

    public function insertContent($data)
    {
        $sql = 'INSERT INTO cms_article (article_title,article_slug,article_content,article_image,article_author_id,article_author_name,article_author_image,article_type_id,article_category_id,article_category_title,article_input_datetime,article_update_datetime,article_last_comment) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $this->query($sql, $data);
    }

    public function getContent($contentID)
    {
        $sql = 'SELECT * FROM cms_article WHERE article_id=?';
        $query = $this->query($sql, [$contentID])->getRow();
        return $query;
    }

    public function addContent($data, $data_menu)
    {
        $res = [
            'status' => false,
            'message' => ''
        ];
        $this->db->transBegin();
        try {
            if (!$this->db->table('site_content')->insert($data))
                throw new \Exception("Gagal menambah data konten", 1);

            $insert_id = $this->db->insertID();
            if ($data_menu['is_menu'] === 'true') {
                $insert_menu = [
                    'menu_par_id' => 0,
                    'menu_content_id' => $insert_id,
                    'menu_title' => $data['content_title'],
                    'menu_location' => 'public',
                    'menu_link' => $data_menu['content_menu_link']
                ];
                if ($data_menu['content_menu_type'] == 'sub-parent') {
                    $insert_menu['menu_par_id'] = $data_menu['content_menu_parent'];
                }
                if (!$this->db->table('site_menu')->insert($insert_menu)) {
                    throw new \Exception("Gagal menambah data menu", 1);
                }
            }

            $res['status'] = true;
            $res['message'] = 'Berhasil menyimpan data';
        } catch (\Throwable $th) {
            $res['status'] = false;
            $res['message'] = $th->getMessage();
        }

        if (!$res['status']) {
            $this->db->transRollback();
        } else {
            if ($this->db->transStatus() === FALSE) {
                $res['status'] = false;
                $res['message'] = 'Ada kesalahan pada database';
                $this->db->transRollback();
            } else {
                $this->db->transCommit();
            }
        }
        return $res;
    }

    public function activeNonactiveContent($update, $where)
    {
        $this->db->table('site_content')->update($update, $where);
        if ($this->db->affectedRows() > 0)
            return true;
        else
            return false;
    }

    public function updateContent($update, $where, $data_menu)
    {
        $success = true;
        $message = 'Berhasil update data konten';
        $this->db->transBegin();

        try {
            if (!$this->db->table('site_content')->update($update, $where)) {
                throw new \Exception("Gagal menambah data menu", 1);
            }

            $content_id = $where['content_id'];
            $hasMenu = $this->hasMenu($content_id);

            if (isset($hasMenu)) {
                if ($data_menu['is_menu'] === 'true') {
                    $this->db->table('site_menu')->delete([
                        'menu_content_id' => $content_id
                    ]);
                    $insert_menu = [
                        'menu_par_id' => 0,
                        'menu_content_id' => $content_id,
                        'menu_title' => $update['content_title'],
                        'menu_location' => 'public',
                        'menu_link' => $data_menu['content_menu_link']
                    ];
                    if ($data_menu['content_menu_type'] == 'sub-parent') {
                        $insert_menu['menu_par_id'] = $data_menu['content_menu_parent'];
                    }
                    if (!$this->db->table('site_menu')->insert($insert_menu)) {
                        throw new \Exception("Gagal menambah data menu", 1);
                    }
                } else {
                    if (!$this->db->table('site_menu')->delete(['menu_content_id' => $content_id])) {
                        throw new \Exception("Gagal menghapus data menu", 1);
                    }
                }
            } else {
                if ($data_menu['is_menu'] === 'true') {
                    $insert_menu = [
                        'menu_par_id' => 0,
                        'menu_content_id' => $content_id,
                        'menu_title' => $update['content_title'],
                        'menu_location' => 'public',
                        'menu_link' => $data_menu['content_menu_link']
                    ];
                    if ($data_menu['content_menu_type'] == 'sub-parent') {
                        $insert_menu['menu_par_id'] = $data_menu['content_menu_parent'];
                    }
                    if (!$this->db->table('site_menu')->insert($insert_menu)) {
                        throw new \Exception("Gagal menambah data menu", 1);
                    }
                }
            }
        } catch (\Throwable $th) {
            $success = false;
            $message = $th->getMessage();
        }
        if (!$success) {
            $this->db->transRollback();
        } else {
            if ($this->db->transStatus() === FALSE) {
                $success = false;
                $this->db->transRollback();
            } else {
                $this->db->transCommit();
            }
        }

        return [
            'status' => $success,
            'message' => $message
        ];
    }

    public function deleteContent($where)
    {
        $this->db->table('site_content')->delete($where);

        if ($this->db->affectedRows() > 0)
            return true;
        else
            return false;
    }

    public function saveConfig($data)
    {
        $articleType = [$data['type'], $data['slug'], $data['config']];
        $sqlType = 'INSERT INTO cms_article_type (article_type_title,article_type_slug,article_type_configuration) VALUES(?,?,?)';
        $this->db->transStart();
        $this->query($sqlType, $articleType);
        $insertID = $this->query('SELECT LAST_INSERT_ID() as id FROM cms_article_type')->getRow();
        foreach ($data['category'] as $category) {
            $articleCategory = [$category['category'], $category['slug'], $insertID->id];
            $sqlCategory = 'INSERT INTO cms_article_category (article_category_name,article_category_slug,article_category_type_id) VALUES(?,?,?)';
            $this->query($sqlCategory, $articleCategory);
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function getType($typeID)
    {
        $sql = "SELECT * FROM cms_article_type WHERE article_type_id=?";
        $query = $this->query($sql, [$typeID])->getRow();
        return $query;
    }

    public function updateConfig($data)
    {
        $articleType = [$data['type'], $data['slug'], $data['config'], $data['typeID']];
        $sqlType = 'UPDATE cms_article_type SET article_type_title=?, article_type_slug=?, article_type_configuration=? WHERE article_type_id=?';
        $this->db->transStart();
        $this->query($sqlType, $articleType);
        foreach ($data['category'] as $category) {
            if (isset($category['id']) && $category['id'] !== '') {
                $articleCategory = [$category['category'], $category['slug'], $category['id']];
                $sqlCategory = 'UPDATE cms_article_category SET article_category_name=?,article_category_slug=? WHERE article_category_id=?';
            } else {
                $articleCategory = [$category['category'], $category['slug'], $data['typeID']];
                $sqlCategory = 'INSERT INTO cms_article_category (article_category_name,article_category_slug,article_category_type_id) VALUES(?,?,?)';
            }
            $this->query($sqlCategory, $articleCategory);
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function getListPage()
    {
        $sql = 'SELECT * FROM cms_pages';
        $query = $this->query($sql)->getResult();
        return $query;
    }

    public function savePublicMenu($data)
    {
        $sql = "DELETE FROM cms_public_menu";
        $this->query($sql);
        $sql = "INSERT INTO cms_public_menu (public_menu_name,public_menu_config) VALUES(?,?)";
        $query = $this->query($sql, $data);
        return $query;
    }

    public function getPublicMenu()
    {
        $sql = "SELECT public_menu_name,public_menu_config FROM cms_public_menu";
        $query = $this->query($sql)->getRow();
        return $query;
    }

    public function getSiteMenu($where)
    {
        return $this->db->table('site_menu')->getWhere($where)->getResult();
    }

    public function hasMenu($content_id)
    {
        $content = $this->db->table('site_content')->join('site_menu', 'menu_content_id = content_id')->getWhere(['content_id' => $content_id])->getRow();
        return $content;
    }

    public function content_category_option()
    {
        $options = array();
        $sql = "SELECT content_category_id, content_category_name FROM site_content_category ORDER BY content_category_name ASC";
        $query = $this->db->query($sql)->getResult();
        if (!empty($query)) {
            $i = 0;
            foreach ($query as $row) {
                $options[$i]['title'] = $row->content_category_name;
                $options[$i]['value'] = $row->content_category_id;
                $i++;
            }
        }
        return $options;
    }

    public function getDetailContent($content_id)
    {
        $sql = "
            SELECT
                content_id,
                content_content_category_id,
                content_title,
                content_slug,
                content_body,
                ifnull(content_image, '') as content_image,
                content_is_active,
                menu_content_id,
                menu_par_id,
                menu_link
            FROM site_content
            JOIN site_content_category on content_category_id = content_content_category_id
            LEFT JOIN site_menu on content_id = menu_content_id
            WHERE content_id = '$content_id'
        ";

        return $this->db->query($sql)->getRow();
    }
}
