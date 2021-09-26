<?php

namespace Vnnit\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Vnnit\Core\Helpers\DataColumn;
use Vnnit\Core\Helpers\LookupData;

trait HasDataColumn
{
    private function getTemplateFields()
    {
        return [
            'key' => '', // tên column (tên cột chứa giá trị trong mảng data)
            'label' => '', // caption của column
            'headerTitle' => '', // Chưa sử dụng
            'class' => '', // khai báo class name cho column
            'dataType' => 'string', // kiểu dữ liệu của column
            'sortable' => true, // cho phép column được phép sắp xếp hay ko?
            'filtering' => false, // column được phép filter hay ko>
            'tdClass' => '', // Chưa sử dụng
            'thClass' => '', // Chưa sử dụng
            'thStyle' => '', // Chưa sử dụng
            'variant' => '', // Chưa sử dụng
            'tdAttr' => [], // Chưa sử dụng
            'isRowHeader' => false, // Chưa sử dụng
            'cell'  => '', // Định dạng lại nội dung hiển thị cho 1 column
            'visible' => true, // Ản hiện column
            'stickyColumn' => false, // Chưa sử dụng
            'lookup' => [ // Loại của column là dạng 1 khóa ngoại liên kết đến 1 table trong db khác để hiển thị dữ liệu
                'dataSource' => null, // dữ liệu từ table liên kết
                'displayExpr' => '', // cột hiển thị giá trị lên select
                'valueExpr' => '' // Giá trị của từng option select
            ]
        ];
    }

    private function getTemplateFieldButton()
    {
        return [
            'key' => '',
            'label' => '',
            'class' => '',
            'title'  => '',
            'icon'  => '',
            'visible' => true
        ];
    }

    private function getTemplatePagination()
    {
        return [
            'total' => 0,
            'pages' => 0,
            'currentPage' => 0,
            'from' => 0,
            'to' => 0
        ];
    }

    private function getLabel($text)
    {
        return title_case(snake_case(studly_case($text), ' '));
    }

    public function getFields($fields, $items)
    {
        if (is_null($fields)) {
            $object = head($items);
            if ($object instanceof Model)
                $object = $object->getAttributes();
            $fields = array_keys($object);
        }

        return array_map(function($field) {
            $template = $this->getTemplateFields();
            if (is_string($field)) {
                data_set($template, 'key', $field);
                data_set($template, 'label', $this->getLabel($field));
            } else {
                $template = array_merge($template, $field);
                if (blank($template['label'])) {
                    data_set($template, 'label', $this->getLabel($template['key']));
                }
                if (!is_null(data_get($template, 'lookup.dataSource'))) {
                    data_set($template, 'lookup', LookupData::make($template['lookup']));
                }
            }
            $dataFields = DataColumn::make($template);
            return $dataFields;
        }, $fields);
    }

    public function getPagination($pagination)
    {
        if (is_array($pagination))
            return array_merge($this->getTemplatePagination(), $pagination);
        return $pagination;
    }

    public function getField($fieldName, $caption, $options = [])
    {
        $fields = array_merge($this->getTemplateFields(), ['key' => $fieldName, 'label' => $caption]);
        return array_merge($fields, $options);
    }

    public function getFieldButton($fieldName, $caption, $options = [])
    {
        $fields = array_merge($this->getTemplateFieldButton(), ['key' => $fieldName, 'label' => $caption]);
        return array_merge($fields, $options);
    }
}
