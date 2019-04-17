<?php

namespace App\Controller;

use App\Entity\DataSet;
use App\Form\DataSetType;
use App\Service\DataReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param DataSet $dataset
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function describeAction(Request $request, DataSet $dataset)
    {
        $form = $this->createForm(DataSetType::class, $dataset)
                     ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('dataset/describe.html.twig', [
            'dataset' => $dataset,
            'form' => $form->createView()
        ]);
    }
}
