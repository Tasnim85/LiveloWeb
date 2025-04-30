<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

final class ListUsersController extends AbstractController
{
    #[Route('/AllUsers', name: 'app_list_users')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('list_users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/export-users', name: 'app_export_users')]
    public function exportUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'First Name');
        $sheet->setCellValue('B1', 'Last Name');
        $sheet->setCellValue('C1', 'CIN');
        $sheet->setCellValue('D1', 'Role');
        $sheet->setCellValue('E1', 'Address');
        $sheet->setCellValue('F1', 'Vehicle Type');
        $sheet->setCellValue('G1', 'Email');
        $sheet->setCellValue('H1', 'Phone Number');
        $sheet->setCellValue('I1', 'Verified');

        // Populate data
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A'.$row, $user->getNom());
            $sheet->setCellValue('B'.$row, $user->getPrenom());
            $sheet->setCellValue('C'.$row, $user->getCin());
            $sheet->setCellValue('D'.$row, $user->getRole());
            $sheet->setCellValue('E'.$row, $user->getAdresse());
            $sheet->setCellValue('F'.$row, $user->getType_vehicule());
            $sheet->setCellValue('G'.$row, $user->getEmail());
            $sheet->setCellValue('H'.$row, $user->getNumTel());
            $sheet->setCellValue('I'.$row, $user->getVerified() ? 'Yes' : 'No');
            $row++;
        }

        // Create Excel file
        $writer = new Xlsx($spreadsheet);

        // Create a temporary file
        $fileName = 'users_export.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Save the spreadsheet
        $writer->save($temp_file);

        // Return the Excel file as response
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}