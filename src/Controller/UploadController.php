<?php

namespace App\Controller;

use App\Entity\DataSet;
use App\Form\DataSetUploadType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UploadController extends AbstractController
{
    /**
     * Renders the spreadsheet upload page.
     *
     * @Route("/upload/spreadsheet", name="upload_spreadsheet")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param Session $session
     *
     * @return Response
     */
    public function uploadAction(Request $request, Session $session)
    {
        $dataset = new DataSet();
        $form = $this->createForm(DataSetUploadType::class, $dataset)
                     ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $dataset->getUploadedFile();
            if ($this->saveSpreadsheet($uploadedFile)) {

                $dataset->setFilename($uploadedFile->getClientOriginalName());

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($dataset);
                $manager->flush();

                return $this->redirectToRoute('describe_dataset', [
                    'dataset' => $dataset->getId(),
                ]);

            } else {
                $session->getFlashBag()->add( 'error', 'Unable to upload the spreadsheet.');
            }
        }

        return $this->render('upload/spreadsheet.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    protected function saveSpreadsheet(UploadedFile $file)
    {
        $uploadDir = $this->getParameter('spreadsheets_directory');
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0775, true)) {
                return false;
            }
        }

        return $file->move($uploadDir, $file->getClientOriginalName())->isFile();
    }
}
