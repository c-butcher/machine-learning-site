<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DataSet
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\DataSetRepository")
 */
class DataSet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=120)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=120)
     *
     * @var string
     */
    protected $filename;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $numRows;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $numColumns;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $visible;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return int
     */
    public function getNumRows(): int
    {
        return $this->numRows;
    }

    /**
     * @param int $numRows
     */
    public function setNumRows(int $numRows): void
    {
        $this->numRows = $numRows;
    }

    /**
     * @return int
     */
    public function getNumColumns(): int
    {
        return $this->numColumns;
    }

    /**
     * @param int $numColumns
     */
    public function setNumColumns(int $numColumns): void
    {
        $this->numColumns = $numColumns;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }
}
