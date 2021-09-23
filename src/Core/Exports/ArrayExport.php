<?php

namespace Vnnit\Core\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ArrayExport extends DefaultValueBinder implements FromArray, WithEvents, WithTitle, ShouldAutoSize, WithCustomValueBinder, WithPreCalculateFormulas, WithHeadings, WithColumnFormatting, WithCustomStartCell
{
    protected $data = [];
    protected $header = null;
    protected $headingRow;
    protected $title = 'Worksheet';
    protected $options = [];
    protected $startRow = 1;

    public function __construct($data = [], $options = [])
    {
        $this->data = $data;
        $this->options = $options;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->beforeSheet($event);
            },
            AfterSheet::class => function (AfterSheet $event) {
                static::afterSheet($event, $this);
            },
        ];
    }

    public function setStartRow($numRow)
    {
        $this->startRow = $numRow;
    }

    /**
     * @param AfterSheet $event
     * @param ArrayExport $instance
     */
    public static function afterSheet(AfterSheet $event, $instance)
    {
        static::freezeHeaderRow($event, $instance);
    }

    protected static function freezeHeaderRow($event, $instance)
    {
        $event->sheet->freezePane('A' . ($instance->startRow + $instance->headingRow));
    }

    protected function beforeSheet(BeforeSheet $event)
    {
    }

    public function setHeader(array $header)
    {
        if (!is_array($header[0])) {
            $this->header = [$header];
        } else {
            $this->header = $header;
        }

        $this->headingRow = count($this->header);

        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return sprintf('A%d', $this->startRow);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->header;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $data = $this->data;

        // if (!empty($this->header)) {
        //     $data = array_merge($this->header, $this->data);
        // }
        //print_r($data);exit;
        return $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [];
    }

	/**
     * DataType for value.
     *
     * @param mixed $pValue
     * @return string
     */
    public static function dataTypeForValue($pValue)
    {
        if ((in_array(WithPreCalculateFormulas::class, class_implements(self::class))) && (!is_empty_or_null($pValue) && is_string($pValue) && $pValue[0] === '=' && strlen($pValue) > 1)) {
            return DataType::TYPE_STRING;
        }
        return parent::dataTypeForValue($pValue);
    }
}
