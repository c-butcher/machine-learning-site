<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DataSetColumn
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\DataSetColumnRepository")
 */
class DataSetColumn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataSet", inversedBy="columns")
     * @ORM\JoinColumn(name="dataset_id", referencedColumnName="id")
     *
     * @var DataSet
     */
    protected $dataSet;

    /**
     * @ORM\Column(type="string", length=120)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=36)
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var boolean
     */
    protected $required;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DataSet
     */
    public function getDataSet(): DataSet
    {
        return $this->dataSet;
    }

    /**
     * @param DataSet $dataSet
     */
    public function setDataSet(DataSet $dataSet): void
    {
        $this->dataSet = $dataSet;
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
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }
}
