<?php

/**
 * Tag
 * @copyright Copyright (c) 2017 MuaBanNhanh Flatform
 * @author Nhan Phong <nhanphong@vinadesign.vn>
 */

namespace Application\Mvc\Helper;
use Lib\Tag\Model\Tag as TagModel;

class Tag extends \Phalcon\Mvc\User\Component
{
    public function tableTag($tag) 
    {
        $tag_layout = '<table class="table table-condensed table-hover">';
        $tag_layout .= '<thead>';
        $tag_layout .= '<tr>';
        $tag_layout .= '<th style="width: 5%"></th>';
        $tag_layout .= '<th>Name</th>';
        $tag_layout .= '<th>Slug</th>';
        $tag_layout .= '<th>Description</th>';
        $tag_layout .= '</tr>';
        $tag_layout .= '</thead>';
        $tag_layout .= '<tbody>';
        if (!empty($tag)) {
            foreach ($tag as $key => $value) {
                $tag_layout .= '<tr>';
                $tag_layout .= '<td>';
                $tag_layout .= '<div class="checkbox-table"><label><input type="checkbox" name="id[]" value="' . $value->id .'" class="flat-grey chk"></label></div>';
                $tag_layout .= '</td>';
                $tag_layout .= '<td>';
                $tag_layout .= '<a href="' . $this->url->get([ 'for' => 'tag_admin_edit', 'id' => $value->id ]) . '">';
                $tag_layout .= '<span style="font-weight:bold; color: black;">' . $value->name . '</span></a><br />';
                $tag_layout .= '<div class="extra-tag">';
                $tag_layout .= '<a href="' . $this->url->get([ 'for' => 'tag_admin_edit', 'id' => $value->id ]) . '">Edit</a> | ';
                $tag_layout .= '<a class="quick-edit" data-href="' . $this->url->get([ 'for' => 'tag_admin_quick_edit', 'id' => $value->id]) . '">Quick Edit</a> |';
                $tag_layout .= '<a class="del-tag" data-href="' . $this->url->get([ 'for' => 'tag_admin_delete_ajax', 'id' => $value->id]) . '">Delete</a> |';
                $tag_layout .= '<a href="' . $this->url->get() . 'tags/' . $value->slug . '.html" target="_blank">View</a>';
                $tag_layout .= '</td>';
                $tag_layout .= '<td>';
                $tag_layout .= $value->slug;
                $tag_layout .= '</td>';
                $tag_layout .= '<td>';
                $tag_layout .= $value->meta_description;
                $tag_layout .= '</td>';
                $tag_layout .= '</tr>';
            }
        } else {
            $tag_layout .= '<tr><td colspan="4">Entries not found</td></tr>';
        }
        
        $tag_layout .= '</tbody>';
        $tag_layout .= '<thead>';
        $tag_layout .= '<tr>';
        $tag_layout .= '<th style="width: 5%"></th>';
        $tag_layout .= '<th>Name</th>';
        $tag_layout .= '<th>Slug</th>';
        $tag_layout .= '<th>Description</th>';
        $tag_layout .= '</tr>';
        $tag_layout .= '</thead>';
        $tag_layout .= '</table>';
        return $tag_layout;
    }

    public function getName($id)
    {
        $tag = TagModel::findFirstById($id);
        return $tag->name;
    }

    public function getSlug($id)
    {
        $tag = TagModel::findFirstById($id);
        return $tag->slug;
    }

    public function getId($name)
    {
        $checkName = TagModel::findFirst([
            'conditions' => "name = :name:",
            'bind' => ['name' => $name]
        ]);
        if ($checkName) {
            return $checkName->id;
        }
    }
}
