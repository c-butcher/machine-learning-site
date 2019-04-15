<?php

namespace App\Tests\Controller;

use App\Entity\DataSet;
use App\Service\DataReader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataReaderTest extends WebTestCase
{
    protected $dataDir;

    protected function setUp()
    {
        self::bootKernel();
        $this->dataDir = self::$container->getParameter('kernel.project_dir') . '/tests/Data/';
    }

    /**
     * Test to make sure that it can open CSV files.
     *
     * @throws \Exception
     */
    public function testCanOpenCSV()
    {
        $dataset = new DataSet();
        $dataset->setType('csv');
        $dataset->setFilename('sample.csv');

        try {
            $reader = new DataReader($dataset, $this->dataDir);
            $this->assertTrue(true);

        } catch(\Exception $ex) {
            $this->assertTrue(false);
        }
    }

    /**
     * Test to make sure that it can open XLSX files.
     *
     * @throws \Exception
     */
    public function testCanOpenXSLX()
    {
        $dataset = new DataSet();
        $dataset->setType('xslx');
        $dataset->setFilename('sample.xlsx');

        try {
            $reader = new DataReader($dataset, $this->dataDir);
            $this->assertTrue(true);

        } catch(\Exception $ex) {
            $this->assertTrue(false);
        }
    }

    /**
     * Test to make sure that we can get the correct number of rows.
     */
    public function testGetNumRows()
    {
        $dataset = new DataSet();
        $dataset->setType('xslx');
        $dataset->setFilename('sample.xlsx');

        try {
            $reader = new DataReader($dataset, $this->dataDir);
            $this->assertEquals(5, $reader->getNumRows());

        } catch(\Exception $ex) {
            $this->assertTrue(false);
        }
    }

    /**
     * Test to make sure we can get the correct column count.
     */
    public function testGetNumColumns()
    {
        $dataset = new DataSet();
        $dataset->setType('xslx');
        $dataset->setFilename('sample.xlsx');

        try {
            $reader = new DataReader($dataset, $this->dataDir);
            $this->assertEquals(5, $reader->getNumColumns());

        } catch(\Exception $ex) {
            $this->assertTrue(false);
        }
    }

    /**
     * Test to make sure we can get the correct column count.
     */
    public function testGetColumnTypesWithoutColumnLabels()
    {
        /**
         * NOTICE: We are using the XLSX file.
         * There are subtle differences in the data types.
         */
        $dataset = new DataSet();
        $dataset->setType('xlsx');
        $dataset->setFilename('sample.xlsx');
        $dataset->setHasColumnLabels(false);

        try {
            $reader = new DataReader($dataset, $this->dataDir);
            $labels = $reader->getColumnTypes();
            $this->assertCount(5, $labels);

            $types = array_values($labels);
            $names = array_keys($labels);

            $this->assertEquals('A', $names[0]);
            $this->assertEquals('B', $names[1]);
            $this->assertEquals('C', $names[2]);
            $this->assertEquals('D', $names[3]);
            $this->assertEquals('E', $names[4]);

            $this->assertEquals('integer', $types[0]);
            $this->assertEquals('integer', $types[1]);
            $this->assertEquals('integer', $types[2]);
            $this->assertEquals('string', $types[3]);
            $this->assertEquals('integer', $types[4]);

        } catch(\Exception $ex) {
            $this->assertTrue(false);
        }
    }

    /**
     * Test to make sure we can get the correct column count.
     */
    public function testGetColumnTypesWithColumnLabels()
    {
        /**
         * NOTICE: We are using the CSV file.
         * There are subtle differences in the data types.
         */
        $dataset = new DataSet();
        $dataset->setType('csv');
        $dataset->setFilename('sample.csv');
        $dataset->setHasColumnLabels(true);

        try {
            $reader = new DataReader($dataset, $this->dataDir);
            $labels = $reader->getColumnTypes();
            $this->assertCount(5, $labels);

            $types = array_values($labels);
            $names = array_keys($labels);

            $this->assertEquals('A', $names[0]);
            $this->assertEquals('one', $names[1]);
            $this->assertEquals('two', $names[2]);
            $this->assertEquals('three', $names[3]);
            $this->assertEquals('four', $names[4]);

            $this->assertEquals('integer', $types[0]);
            $this->assertEquals('string', $types[1]);
            $this->assertEquals('integer', $types[2]);
            $this->assertEquals('integer', $types[3]);
            $this->assertEquals('integer', $types[4]);

        } catch(\Exception $ex) {
            $this->assertTrue(false);
        }
    }

    /**
     * Test to make sure that we can get a specific set of rows.
     */
    public function testGetRowsWithColumnLabels()
    {
        /**
         * NOTICE: We are using the CSV file.
         * There are subtle differences in the data types.
         */
        $dataset = new DataSet();
        $dataset->setType('csv');
        $dataset->setFilename('sample.csv');
        $dataset->setHasColumnLabels(true);

        try {
            $reader = new DataReader($dataset, $this->dataDir);

            // Make sure that we get all the rows.
            $rows = $reader->getRows();
            $this->assertEquals(4, count($rows));

            // Check to see if we can get only three rows (3, 4 and 5)
            $rows = $reader->getRows(3, 5);
            $this->assertEquals(3, count($rows));

            // Check to make sure that our rows are indexed by their column labels.
            $labels = array_keys($reader->getColumnTypes());
            $this->assertEquals($labels, array_keys($rows[0]));

        } catch(\Exception $ex) {
            $this->assertTrue(false);
        }
    }
}
