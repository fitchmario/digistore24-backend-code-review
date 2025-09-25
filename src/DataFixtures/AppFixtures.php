<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\MessageStatus;
use App\Enum\MessageStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;
use function Psl\Iter\random;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $messageStatus = [
            MessageStatusEnum::SENT->value,
            MessageStatusEnum::READ->value,
        ];
        
        foreach (range(1, 10) as $i) {
            $message = new Message();
            $message->setUuid(Uuid::v6()->toRfc4122());
            $message->setText($faker->sentence);
            $message->setStatus(random($messageStatus));
            
            $manager->persist($message);
        }

        $manager->flush();
    }
}
