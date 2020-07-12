<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Dto\PageDto;
use App\Dto\TaskDto;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class TaskControllerTest extends WebTestCase
{
    private EntityManager $em;
    private TaskRepository $taskRepository;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getEntityManager();
        $this->taskRepository = $this->em->getRepository(Task::class);
        $this->regenerateSchema();
    }

    public function testItReturnsNrOfTasks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(0, count(json_decode($client->getResponse()->getContent())));

        $this->createTask();

        $this->assertCount(1, $this->taskRepository->getTasks(new PageDto([])));
    }

    public function testItReturnsNotFoundTask() : void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/1');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('Task not found', json_decode($client->getResponse()->getContent())['message']);
    }

    public function testItReturnsSpecificTask() : void
    {
        $client = static::createClient();
        $task = $this->createTask();
        $client->request('GET', '/tasks/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($task->getId(), json_decode($client->getResponse()->getContent())['id']);
    }

    public function testItFailsCreationOfTask() : void
    {
        $client = static::createClient();
        $client->request('POST', '/tasks');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testItIsCreatedTask() : void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/tasks',
            [
                'name'        => 'test',
                'status'      => 'test',
                'description' => 'test',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $this->taskRepository->getTasks(new PageDto([])));
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown() : void
    {
        parent::tearDown();
        $this->em->close();
    }

    /**
     * Drops current schema and creates a brand new one
     */
    private function regenerateSchema() : void
    {
        $metadatas = $this->em->getMetadataFactory()->getAllMetadata();

        if (count($metadatas) > 0) {
            return;
        }

        $tool = new SchemaTool($this->em);
        $tool->dropSchema($metadatas);
        $tool->createSchema($metadatas);
    }

    private function createTask() : Task
    {
        return $this->taskRepository->createTask(
            new TaskDto(
                [
                    'name'        => 'test',
                    'status'      => 'test',
                    'description' => 'test',
                ]
            )
        );
    }
}
