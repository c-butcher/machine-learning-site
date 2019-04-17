<?php

namespace App\Controller;

use App\Entity\DataSet;
use App\Entity\DataColumn;
use App\Form\DataSetUploadType;
use App\Service\DataReader;
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
     *
     * @throws \Exception
     */
    public function uploadAction(Request $request, Session $session)
    {
        $dataset = new DataSet();
        $form = $this->createForm(DataSetUploadType::class, $dataset)
                     ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Move the spreadsheet to the uploads directory,
            // or return to the uploads page when it fails.
            $uploadedFile = $dataset->getUploadedFile();
            if (!$this->saveSpreadsheet($uploadedFile)) {
                $session->getFlashBag()->add( 'error', 'Unable to upload the spreadsheet.');

                return $this->render('upload/spreadsheet.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            // Set the filename of the spreadsheet.
            $dataset->setFilename($uploadedFile->getClientOriginalName());

            // Read the spreadsheet and get all of the column types.
            $reader  = new DataReader($dataset, $this->getParameter('spreadsheets_directory'));

            // We are fetching all the columns from the spreadsheet,
            // and populating our DataColumn objects.
            $columns = $reader->getColumnTypes();
            foreach ($columns as $name => $type) {
                $column = new DataColumn();
                $column->setName($name);
                $column->setType($type);
                $column->setRequired(true);

                $dataset->addColumn($column);
            }

            // Set the number of columns and rows in the spreadsheet.
            $dataset->setNumColumns(count($columns));
            $dataset->setNumRows($reader->getNumRows());

            // Save the dataset to the database.
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dataset);
            $manager->flush();

            return $this->redirectToRoute('describe_dataset', [
                'dataset' => $dataset->getId(),
            ]);
        }

        return $this->render('upload/spreadsheet.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Moves are spreadsheet from the temporary upload directory to our final
     * spreadsheets directory.
     *
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
