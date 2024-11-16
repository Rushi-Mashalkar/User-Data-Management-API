<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserController extends AbstractController
{
    #[Route('/api/upload', name: 'upload', methods: ['POST'])]
    public function uploadData(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $csv = Reader::createFromPath('data.csv', 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $record) {
            $user = new User();
            $user->setName($record['name']);
            $user->setEmail($record['email']);
            $user->setUsername($record['username']);
            $user->setAddress($record['address']);
            $user->setRole($record['role']);
            
            $em->persist($user);

            // Send Email (asynchronously)
            $email = (new Email())
                ->from('admin@example.com')
                ->to($record['email'])
                ->subject('Welcome to Our Service')
                ->text("Hi {$record['name']}, your data has been successfully uploaded.");
            
            $mailer->send($email);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Data uploaded successfully'], 201);
    }
}

#[Route('/api/users', name: 'view_users', methods: ['GET'])]
function viewUsers(EntityManagerInterface $em)
{
    $users = $em->getRepository(User::class)->findAll();
    $data = [];

    foreach ($users as $user) {
        $data[] = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'address' => $user->getAddress(),
            'role' => $user->getRole()
        ];
    }
    return new JsonResponse($data);
}

#[Route('/api/backup', name: 'backup_db', methods: ['GET'])]
function backupDatabase()
{
    $backupFile = 'backup.sql';
    exec("mysqldump -u db_user -p'db_password' user_data_db > $backupFile");

    return new JsonResponse(['message' => 'Database backup successful', 'file' => $backupFile]);
}

#[Route('/api/restore', name: 'restore_db', methods: ['POST'])]
function restoreDatabase()
{
    $backupFile = 'backup.sql';
    exec("mysql -u db_user -p'db_password' user_data_db < $backupFile");

    return new JsonResponse(['message' => 'Database restored successfully']);
}
