<?php

namespace App\Tests\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CategorieControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $categorieRepository;
    private string $path = '/categorie/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->categorieRepository = $this->manager->getRepository(Categorie::class);

        foreach ($this->categorieRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Categorie index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'categorie[id_categorie]' => 'Testing',
            'categorie[description]' => 'Testing',
            'categorie[url_image]' => 'Testing',
            'categorie[nom]' => 'Testing',
            'categorie[created_by]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->categorieRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setId_categorie('My Title');
        $fixture->setDescription('My Title');
        $fixture->setUrl_image('My Title');
        $fixture->setNom('My Title');
        $fixture->setCreated_by('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Categorie');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setId_categorie('Value');
        $fixture->setDescription('Value');
        $fixture->setUrl_image('Value');
        $fixture->setNom('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'categorie[id_categorie]' => 'Something New',
            'categorie[description]' => 'Something New',
            'categorie[url_image]' => 'Something New',
            'categorie[nom]' => 'Something New',
            'categorie[created_by]' => 'Something New',
        ]);

        self::assertResponseRedirects('/categorie/');

        $fixture = $this->categorieRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getId_categorie());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getUrl_image());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getCreated_by());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setId_categorie('Value');
        $fixture->setDescription('Value');
        $fixture->setUrl_image('Value');
        $fixture->setNom('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/categorie/');
        self::assertSame(0, $this->categorieRepository->count([]));
    }
}
