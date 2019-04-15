<?php

namespace App\Service;

use App\Entity\DataSet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Column;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class DataReader
{
    /**
     * @var DataSet
     */
    protected $dataset;

    /**
     * @var \PhpOffice\PhpSpreadsheet\Reader\IReader
     */
    protected $reader;

    /**
     * @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     */
    protected $worksheet;

    /**
     * DataReader constructor.
     *
     * @param DataSet $dataset
     * @param string  $spreadsheetDirectory
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct(DataSet $dataset, $spreadsheetDirectory)
    {
        $this->dataset = $dataset;
        $this->reader = IOFactory::load($spreadsheetDirectory . $dataset->getFilename());
        $this->worksheet = $this->reader->getActiveSheet();
    }

    /**
     * Returns the total number of worksheets in the spreadsheet.
     *
     * @return int
     */
    public function getNumWorksheets()
    {
        return $this->reader->getSheetCount();
    }

    /**
     * Returns an array with the column name as the index, and the column type as the value.
     *
     * @return string[]
     */
    public function getColumnTypes()
    {
        $columns = array();
        $highestColumn  = $this->worksheet->getHighestColumn();

        foreach ($this->worksheet->getColumnIterator('A', $highestColumn) as $column) {
            foreach ($column->getCellIterator(1, 1) as $cell) {
                if (is_null($cell->getValue()) && $this->columnHasNoValues($column)) {
                    continue;
                }

                if ($this->dataset->isHasColumnLabels()) {
                    if (is_null($cell->getValue())) {
                        // If the column doesn't have a label on the first row
                        // then we can use the column index instead.
                        $columns[$column->getColumnIndex()] = $this->getColumnType($column);

                    } else {
                        // When the column does have a label on the first row
                        // then we'll want to use that.
                        $columns[$cell->getValue()] = $this->getColumnType($column);
                    }

                } else {
                    $columns[$column->getColumnIndex()] = $this->getColumnType($column);
                }
            }
        }

        return $columns;
    }

    /**
     * Check to see if the column has any values.
     *
     * @param Column $column
     *
     * @return boolean
     */
    public function columnHasNoValues($column)
    {
        // When our dataset has labels on the first column, we need to start at row 2.
        $firstRow = $this->dataset->isHasColumnLabels() ? 2 : 1;
        $lastRow  = $this->getNumRows();
        $colIndex = $column->getColumnIndex();

        // We are limiting our check to the first thousand values in the column.
        // Otherwise large data-sets will take forever to process.
        if ($lastRow > 1000) {
            $lastRow = 1000;
        }

        // Grab all of the values for the column and filter out the null values.
        $values = $this->worksheet->rangeToArray("{$colIndex}{$firstRow}:{$colIndex}{$lastRow}");
        $values = array_filter($values, function($value) { return !is_null($value[0]); });

        return count($values) == 0;
    }

    /**
     * Returns a columns data type.
     *
     * @param Column $column
     *
     * @return string
     */
    public function getColumnType($column)
    {
        // We want to sample twenty rows, and get the data type based
        // on the average data type of those twenty rows.
        $numRows = $this->getNumRows();
        if ($numRows > 21) {
            $numRows = 21;
        }

        $types = [];
        foreach ($column->getCellIterator(1, $numRows) as $cell) {
            $types[] = $cell->getDataType();
        }

        // Here we are counting the number of times a data type showed up.
        // Then we are getting the most popular data-type.
        $types = array_count_values($types);
        $types = array_keys($types);
        $type  = array_pop($types);

        switch ($type) {
            case DataType::TYPE_NUMERIC:
                $type = 'integer';
                break;

            case DataType::TYPE_BOOL:
                $type = 'boolean';
                break;

            case DataType::TYPE_STRING:
            case DataType::TYPE_STRING2:
            default:
                $type = 'string';
                break;
        }

        return $type;
    }

    /**
     * Returns the total number of columns.
     *
     * @return int
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function getNumColumns()
    {
        $highestColumn = $this->worksheet->getHighestDataColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        return $highestColumnIndex;
    }

    /**
     * Returns the total number of rows in the worksheet.
     *
     * @return int
     */
    public function getNumRows()
    {
        return $this->worksheet->getHighestDataRow();
    }

    /**
     * Returns a range of rows from the spreadsheet.
     *
     * When the finish parameter is less than the start, then it returns all the rows.
     *
     * @param int $start   The row that we will start at.
     * @param int $finish  The row that we will finish at.
     *
     * @return array[]
     */
    public function getRows($start = 1, $finish = -1)
    {
        if ($start < 1) {
            $start = 1;
        }

        // When the number of rows we want, is less then the starting number
        // that means we want to grab all our rows.
        if ($finish < $start) {
            $finish = $this->getNumRows();
        }

        // If the dataset has column labels, and we are starting on row one,
        // then we need to skip row one, as to avoid our column labels becoming values.
        if ($this->dataset->isHasColumnLabels() && $start === 1) {
            $start++;
        }

        // We are getting the highest column that contains data.
        $highestColumn = $this->worksheet->getHighestDataColumn();

        $rows   = [];
        $labels = array_keys($this->getColumnTypes());
        $values = $this->worksheet->rangeToArray("A{$start}:{$highestColumn}{$finish}");

        foreach ($values as $rowValues) {
            $rows[] = array_combine($labels, $rowValues);
        }

        return $rows;
    }
}
