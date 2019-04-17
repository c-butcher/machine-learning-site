<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @ORM\OneToMany(targetEntity="App\Entity\DataColumn", mappedBy="dataSet", cascade={"persist"})
     *
     * @var Collection
     */
    protected $columns;

    /**
     * @ORM\Column(type="string", length=6)
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=120)
     *
     * @var string
     */
    protected $filename;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    protected $numRows;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    protected $numColumns;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $hasColumnLabels = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $visible = true;

    public function __construct()
    {
        $this->columns = new ArrayCollection();
    }

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
    public function getName(): ?string
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
     * @return Collection
     */
    public function getColumns(): ? Collection
    {
        return $this->columns;
    }

    /**
     * @param DataColumn $column
     */
    public function addColumn(DataColumn $column)
    {
        $this->columns->add($column);
    }

    /**
     * @param DataColumn $column
     */
    public function removeColumn(DataColumn $column)
    {
        $this->columns->removeElement($column);
    }

    /**
     * @param Collection $columns
     */
    public function setColumns(Collection $columns): void
    {
        $this->columns = $columns;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFilename(): ?string
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
     * @return UploadedFile
     */
    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile $uploadedFile
     */
    public function setUploadedFile(UploadedFile $uploadedFile): void
    {
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * @return int
     */
    public function getNumRows(): ?int
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
    public function getNumColumns(): ?int
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
    public function isHasColumnLabels(): bool
    {
        return $this->hasColumnLabels;
    }

    /**
     * @param bool $hasColumnLabels
     */
    public function setHasColumnLabels(bool $hasColumnLabels): void
    {
        $this->hasColumnLabels = $hasColumnLabels;
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
