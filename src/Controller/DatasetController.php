<?php

namespace App\Controller;

use App\Entity\DataSet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DatasetController extends AbstractController
{
    /**
     * Page for describing the dataset, including all of its columns.
     *
     * @Route("/dataset/describe/{dataset}", name="describe_dataset")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param DataSet $dataset
     *
     * @return Response
     */
    public function describeAction(DataSet $dataset)
    {
        return $this->render('dataset/describe.html.twig', [
            'dataset' => $dataset
        ]);
    }
}
