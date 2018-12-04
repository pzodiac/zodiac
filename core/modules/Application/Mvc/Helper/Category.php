<?php

/**
 * Category
 * @copyright Copyright (c) 2017 PhalartCMS
 * @author Nhan Phong <nhanphong@vinadesign.vn>
 */

namespace Application\Mvc\Helper;
use Lib\Category\Model\Category as CategoryBaseModel;

class Category extends \Phalcon\Mvc\User\Component
{
    public static function getInstance()
    {
    }

    public function categorySelect($category, $valueActive, $valueDefault) 
    {
        $category_layout = '<select style="width: 100%" id="parent_id" name="parent_id" class="form-control">
                                <option value="' . $valueDefault['value'] . '">' . $valueDefault['name'] . '</option>';
        $level = '';
        $category_layout .= $this->subOption($category, (int) $valueActive, $level);
        $category_layout .= '</select>';
        return $category_layout;
    }

    public function subOption($category, $valueActive , $level) 
    {
        $categoryLayout = '';
        foreach ($category as $key => $value) {
            if ($level != '') {
                $prefix =  $level . ' ';
            } else {
                $prefix = $level;
            }
            if (isset($value['sub_category'])) {
                $select = ($value['id'] == $valueActive) ? 'selected' : '';
                $categoryLayout .= '<option ' . $select . ' value="' . $value['id'] . '">' . $prefix . $value['name'] . '</option>';
                $level = '&nbsp;&nbsp;&nbsp;' . $level;
                $categoryLayout .= $this->subOption($value['sub_category'], (int) $valueActive, $level);
                $level = '';
            } else {
                $select = ($value['id'] == $valueActive) ? 'selected' : '';
                $categoryLayout .= '<option ' . $select . ' value="' . $value['id'] . '">' . $prefix . $value['name'] . '</option>';
            }
        }
        return $categoryLayout;
    }

    public function mutilMenu($mainCategories) 
    {
        $category_layout = '<ol class="dd-list">';
        $category_layout .= $this->subMenu($mainCategories);
        $category_layout .= '</ol>';
        return $category_layout;
    }

    public function subMenu($mainCategories) 
    {
        $categoryLayout = '';
        foreach ($mainCategories as $key => $value) {
            switch ($value['status']) {
                case 'Y':
                    $status = '<span class="pull-right label label-success"> Active <span>';
                    break;
                case 'N':
                    $status = '<span class="pull-right label label-inverse"> Inactive <span>';
                    break;
                case 'A':
                    $status = '<span class="pull-right label label-danger"> Remove <span>';
                    break;
                default:
                    # code...
                    break;
            }
            
            if (isset($value['sub_category'])) {
                $categoryLayout .= '<li class="dd-item dd3-item" data-id="' . $value['id'] . '">
                                <div class="dd-handle dd3-handle"></div>
                                <div class="dd3-content"><a href="' . $this->url->get() . 'category/edit?id=' . $value['id'] . '">' . $value['name'] . $status . '</a></div>';
                    $categoryLayout .= '<ol class="dd-list">';
                    $categoryLayout .= $this->subMenu($value['sub_category']);
                    $categoryLayout .= '</ol>';
            } else {
                $categoryLayout .= '<li class="dd-item dd3-item" data-id="' . $value['id'] . '">
                                <div class="dd-handle dd3-handle"></div>
                                <div class="dd3-content"><a href="' . $this->url->get() . 'category/edit?id=' . $value['id'] . '">' . $value['name'] . $status . '</a></div>';
                $categoryLayout .= '</li>';
            }
        }
        return $categoryLayout;
    }

    public function tableCategory($mainCategories) 
    {
        $level = '';
        $category_layout = '<table class="table table-condensed table-hover">';
        $category_layout .= '<thead>';
        $category_layout .= '<tr>';
        $category_layout .= '<th style="width: 5%"></th>';
        $category_layout .= '<th>Name</th>';
        $category_layout .= '<th>Slug</th>';
        $category_layout .= '<th>Description</th>';
        $category_layout .= '</tr>';
        $category_layout .= '</thead>';
        $category_layout .= '<tbody>';
        $category_layout .= $this->trCategory($mainCategories, $level);
        $category_layout .= '</tbody>';
        $category_layout .= '<thead>';
        $category_layout .= '<tr>';
        $category_layout .= '<th style="width: 5%"></th>';
        $category_layout .= '<th>Name</th>';
        $category_layout .= '<th>Slug</th>';
        $category_layout .= '<th>Description</th>';
        $category_layout .= '</tr>';
        $category_layout .= '</thead>';
        $category_layout .= '</table>';
        return $category_layout;
    }

    public function trCategory($mainCategories, $level) 
    {
        $categoryLayout = '';
        foreach ($mainCategories as $key => $value) {
            if ($level != '') {
                $prefix =  $level . ' ';
            } else {
                $prefix = $level;
            }
            if (isset($value['sub_category'])) {
                     $categoryLayout .= '<tr>
                                <td><div class="checkbox-table"><label><input type="checkbox" name="id[]" value="' . $value['id'] .'" class="flat-grey chk"></label></div></td>
                                <td><a href="' . $this->url->get(["for" => "category_admin_edit", "id" => $value['id'] ]) . '">
                                    <span style="font-weight:bold; color: black;">' . $prefix . $value['name'] . '</span></a><br />
                                <div class="extra-cate">
                                    <a href="' . $this->url->get(["for" => "category_admin_edit", "id" => $value['id'] ]) . '">Edit</a> | 
                                    <a class="quick-edit" data-href="' . $this->url->get(["for" => "category_admin_quick_edit", "id" => $value["id"]]) . '">Quick Edit</a> |
                                    <a class="del-cate" data-href="' . $this->url->get(["for" => "category_admin_delete_ajax", "id" => $value['id'] ]) . '">Delete</a> |
                                    <a href="' . $this->url->get() . $value['slug'] . '.html" target="_blank">View</a>
                                </div>
                                </td>
                                <td>' . $value['slug'] . '</td>
                                <td width="40%">' . $value['meta_description'] . '</td>';
                    $level = '&ndash;&ndash;' . $level;
                    $categoryLayout .= $this->trCategory($value['sub_category'], $level);
                    $level = '';
            } else {
                $categoryLayout .= '<tr>
                                <td><div class="checkbox-table"><label><input type="checkbox" name="id[]" value="' . $value['id'] .'" class="flat-grey chk"></label></div></td>
                                <td><a href="' . $this->url->get(["for" => "category_admin_edit", "id" => $value['id'] ]) . '"><span style="font-weight:bold; color: black;">' . $prefix . $value['name'] . '</span></a><br />
                                <div class="extra-cate">
                                    <a href="' . $this->url->get(["for" => "category_admin_edit", "id" => $value['id'] ]) . '">Edit</a> | 
                                    <a class="quick-edit" data-href="' . $this->url->get([ "for" => "category_admin_quick_edit", "id" => $value["id"] ]) . '">Quick Edit</a> |
                                    <a class="del-cate" data-href="' . $this->url->get(["for" => "category_admin_delete_ajax", "id" => $value['id'] ]) . '">Delete</a> |
                                    <a href="' . $this->url->get() . $value['slug'] . '.html" target="_blank">View</a>
                                </div>
                                </td>
                                <td>' . $value['slug'] . '</td>
                                <td width="40%">' . $value['meta_description'] . '</td>';
                $categoryLayout .= '</tr>';
            }
        }
        return $categoryLayout;
    }

    public function tableCategoryProduct($mainCategories)
    {
        $level = '';
        $category_layout = '<table class="table table-condensed table-hover">';
        $category_layout .= '<thead>';
        $category_layout .= '<tr>';
        $category_layout .= '<th style="width: 5%"></th>';
        $category_layout .= '<th>Name</th>';
        $category_layout .= '<th>Slug</th>';
        $category_layout .= '<th>Description</th>';
        $category_layout .= '</tr>';
        $category_layout .= '</thead>';
        $category_layout .= '<tbody>';
        $category_layout .= $this->trCategoryProduct($mainCategories, $level);
        $category_layout .= '</tbody>';
        $category_layout .= '<thead>';
        $category_layout .= '<tr>';
        $category_layout .= '<th style="width: 5%"></th>';
        $category_layout .= '<th>Name</th>';
        $category_layout .= '<th>Slug</th>';
        $category_layout .= '<th>Description</th>';
        $category_layout .= '</tr>';
        $category_layout .= '</thead>';
        $category_layout .= '</table>';
        return $category_layout;
    }

    public function trCategoryProduct($mainCategories, $level)
    {
        $categoryLayout = '';
        foreach ($mainCategories as $key => $value) {
            if ($level != '') {
                $prefix =  $level . ' ';
            } else {
                $prefix = $level;
            }
            if (isset($value['sub_category'])) {
                $categoryLayout .= '<tr>
                                <td><div class="checkbox-table"><label><input type="checkbox" name="id[]" value="' . $value['id'] .'" class="flat-grey chk"></label></div></td>
                                <td><a href="' . $this->url->get(["for" => "product_category_edit", "id" => $value['id'] ]) . '">
                                    <span style="font-weight:bold; color: black;">' . $prefix . $value['name'] . '</span></a><br />
                                <div class="extra-cate">
                                    <a href="' . $this->url->get(["for" => "product_category_edit", "id" => $value['id'] ]) . '">Edit</a> | 
                                    <a class="quick-edit" data-href="' . $this->url->get(["for" => "product_category_quick_edit", "id" => $value["id"]]) . '">Quick Edit</a> |
                                    <a class="del-cate" data-href="' . $this->url->get(["for" => "category_admin_delete_ajax", "id" => $value['id'] ]) . '">Delete</a> |
                                    <a href="' . $this->url->get() . $value['slug'] . '.html" target="_blank">View</a>
                                </div>
                                </td>
                                <td>' . $value['slug'] . '</td>
                                <td width="40%">' . $value['meta_description'] . '</td>';
                $level = '&ndash;&ndash;' . $level;
                $categoryLayout .= $this->trCategory($value['sub_category'], $level);
                $level = '';
            } else {
                $categoryLayout .= '<tr>
                                <td><div class="checkbox-table"><label><input type="checkbox" name="id[]" value="' . $value['id'] .'" class="flat-grey chk"></label></div></td>
                                <td><a href="' . $this->url->get(["for" => "product_category_edit", "id" => $value['id'] ]) . '"><span style="font-weight:bold; color: black;">' . $prefix . $value['name'] . '</span></a><br />
                                <div class="extra-cate">
                                    <a href="' . $this->url->get(["for" => "product_category_edit", "id" => $value['id'] ]) . '">Edit</a> | 
                                    <a class="quick-edit" data-href="' . $this->url->get([ "for" => "product_category_quick_edit", "id" => $value["id"] ]) . '">Quick Edit</a> |
                                    <a class="del-cate" data-href="' . $this->url->get(["for" => "category_admin_delete_ajax", "id" => $value['id'] ]) . '">Delete</a> |
                                    <a href="' . $this->url->get() . $value['slug'] . '.html" target="_blank">View</a>
                                </div>
                                </td>
                                <td>' . $value['slug'] . '</td>
                                <td width="40%">' . $value['meta_description'] . '</td>';
                $categoryLayout .= '</tr>';
            }
        }
        return $categoryLayout;
    }

    public function getName($id)
    {
        $cate = CategoryBaseModel::findFirstById($id);
        return $cate->name;
    }
}
