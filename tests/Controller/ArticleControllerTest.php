<?php

namespace App\Tests\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ArticleControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $articleRepository;
    private string $path = '/article/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->articleRepository = $this->manager->getRepository(Article::class);

        foreach ($this->articleRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'article[id_article]' => 'Testing',
            'article[url_image]' => 'Testing',
            'article[nom]' => 'Testing',
            'article[prix]' => 'Testing',
            'article[description]' => 'Testing',
            'article[quantite]' => 'Testing',
            'article[statut]' => 'Testing',
            'article[createdAt]' => 'Testing',
            'article[nbViews]' => 'Testing',
            'article[id_categorie]' => 'Testing',
            'article[created_by]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->articleRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Article();
        $fixture->setId_article('My Title');
        $fixture->setUrl_image('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrix('My Title');
        $fixture->setDescription('My Title');
        $fixture->setQuantite('My Title');
        $fixture->setStatut('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setNbViews('My Title');
        $fixture->setId_categorie('My Title');
        $fixture->setCreated_by('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Article();
        $fixture->setId_article('Value');
        $fixture->setUrl_image('Value');
        $fixture->setNom('Value');
        $fixture->setPrix('Value');
        $fixture->setDescription('Value');
        $fixture->setQuantite('Value');
        $fixture->setStatut('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setNbViews('Value');
        $fixture->setId_categorie('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'article[id_article]' => 'Something New',
            'article[url_image]' => 'Something New',
            'article[nom]' => 'Something New',
            'article[prix]' => 'Something New',
            'article[description]' => 'Something New',
            'article[quantite]' => 'Something New',
            'article[statut]' => 'Something New',
            'article[createdAt]' => 'Something New',
            'article[nbViews]' => 'Something New',
            'article[id_categorie]' => 'Something New',
            'article[created_by]' => 'Something New',
        ]);

        self::assertResponseRedirects('/article/');

        $fixture = $this->articleRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getId_article());
        self::assertSame('Something New', $fixture[0]->getUrl_image());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getQuantite());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getNbViews());
        self::assertSame('Something New', $fixture[0]->getId_categorie());
        self::assertSame('Something New', $fixture[0]->getCreated_by());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Article();
        $fixture->setId_article('Value');
        $fixture->setUrl_image('Value');
        $fixture->setNom('Value');
        $fixture->setPrix('Value');
        $fixture->setDescription('Value');
        $fixture->setQuantite('Value');
        $fixture->setStatut('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setNbViews('Value');
        $fixture->setId_categorie('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/article/');
        self::assertSame(0, $this->articleRepository->count([]));
    }
}
