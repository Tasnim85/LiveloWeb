<?php

namespace App\Tests\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CommandeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $commandeRepository;
    private string $path = '/commande/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->commandeRepository = $this->manager->getRepository(Commande::class);

        foreach ($this->commandeRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commande index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'commande[id_commande]' => 'Testing',
            'commande[adresse_dep]' => 'Testing',
            'commande[adresse_arr]' => 'Testing',
            'commande[type_livraison]' => 'Testing',
            'commande[horaire]' => 'Testing',
            'commande[statut]' => 'Testing',
            'commande[created_by]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->commandeRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commande();
        $fixture->setId_commande('My Title');
        $fixture->setAdresse_dep('My Title');
        $fixture->setAdresse_arr('My Title');
        $fixture->setType_livraison('My Title');
        $fixture->setHoraire('My Title');
        $fixture->setStatut('My Title');
        $fixture->setCreated_by('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commande');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commande();
        $fixture->setId_commande('Value');
        $fixture->setAdresse_dep('Value');
        $fixture->setAdresse_arr('Value');
        $fixture->setType_livraison('Value');
        $fixture->setHoraire('Value');
        $fixture->setStatut('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'commande[id_commande]' => 'Something New',
            'commande[adresse_dep]' => 'Something New',
            'commande[adresse_arr]' => 'Something New',
            'commande[type_livraison]' => 'Something New',
            'commande[horaire]' => 'Something New',
            'commande[statut]' => 'Something New',
            'commande[created_by]' => 'Something New',
        ]);

        self::assertResponseRedirects('/commande/');

        $fixture = $this->commandeRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getId_commande());
        self::assertSame('Something New', $fixture[0]->getAdresse_dep());
        self::assertSame('Something New', $fixture[0]->getAdresse_arr());
        self::assertSame('Something New', $fixture[0]->getType_livraison());
        self::assertSame('Something New', $fixture[0]->getHoraire());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getCreated_by());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commande();
        $fixture->setId_commande('Value');
        $fixture->setAdresse_dep('Value');
        $fixture->setAdresse_arr('Value');
        $fixture->setType_livraison('Value');
        $fixture->setHoraire('Value');
        $fixture->setStatut('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/commande/');
        self::assertSame(0, $this->commandeRepository->count([]));
    }
}
