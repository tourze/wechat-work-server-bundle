<?php

namespace WechatWorkServerBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkServerBundle\Entity\ServerMessage;

#[When(env: 'test')]
#[When(env: 'dev')]
class ServerMessageFixtures extends Fixture
{
    public const SERVER_MESSAGE_REFERENCE_PREFIX = 'server_message_';
    public const SERVER_MESSAGE_COUNT = 20;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::SERVER_MESSAGE_COUNT; ++$i) {
            $message = $this->createServerMessage();
            $manager->persist($message);
            $this->addReference(self::SERVER_MESSAGE_REFERENCE_PREFIX . $i, $message);
        }

        $manager->flush();
    }

    private function createServerMessage(): ServerMessage
    {
        $message = new ServerMessage();

        $this->setBasicFields($message);
        $this->setOptionalEventFields($message);
        $this->setOptionalIdentifierFields($message);
        $this->setOptionalNumericFields($message);
        $this->setOptionalStringFields($message);
        $this->setResponseField($message);

        return $message;
    }

    private function setBasicFields(ServerMessage $message): void
    {
        $message->setToUserName($this->generateCorpId());
        $message->setFromUserName($this->faker->optional(0.8)->userName());
        $message->setCreateTime($this->faker->unixTime());
        $message->setRawData($this->generateRawData());
        $message->setDecryptData($this->generateDecryptData());
        $message->setMsgType($this->generateMsgType());
    }

    private function setOptionalEventFields(ServerMessage $message): void
    {
        $event = $this->faker->optional(0.6)->randomElement(['add_external_contact', 'del_external_contact', 'change_type', 'add_half_external_contact']);
        if (is_string($event)) {
            $message->setEvent($event);
        }

        $changeType = $this->faker->optional(0.5)->randomElement(['create_user', 'update_user', 'delete_user', 'create_party', 'update_party', 'delete_party']);
        if (is_string($changeType)) {
            $message->setChangeType($changeType);
        }
    }

    private function setOptionalIdentifierFields(ServerMessage $message): void
    {
        $message->setChatId($this->faker->optional(0.4)->regexify('wr[a-zA-Z0-9]{20}'));
        $message->setExternalUserId($this->faker->optional(0.3)->regexify('wo[a-zA-Z0-9]{18}'));
        $message->setUserId($this->faker->optional(0.7)->userName());
        $message->setWelcomeCode($this->faker->optional(0.2)->regexify('[a-zA-Z0-9]{32}'));
    }

    private function setOptionalNumericFields(ServerMessage $message): void
    {
        $message->setJoinScene($this->faker->optional(0.5)->numberBetween(1, 5));
        $message->setMemChangeCnt($this->faker->optional(0.3)->numberBetween(1, 10));
        $message->setQuitScene($this->faker->optional(0.2)->numberBetween(1, 3));
    }

    private function setOptionalStringFields(ServerMessage $message): void
    {
        $message->setState($this->faker->optional(0.4)->word());
        $message->setUpdateDetail($this->faker->optional(0.3)->sentence());
    }

    private function setResponseField(ServerMessage $message): void
    {
        $responseElements = $this->faker->optional(0.3)->randomElements(['success', 'failed', 'pending'], $this->faker->numberBetween(1, 3));
        if (!is_array($responseElements)) {
            return;
        }

        $typedResponse = $this->extractStringElements($responseElements);
        if ([] === $typedResponse) {
            return;
        }

        // Build response data as array<string, mixed>
        $response = [];
        foreach ($typedResponse as $index => $value) {
            $response[(string) $index] = $value;
        }

        $message->setResponse($response);
    }

    /**
     * @param array<mixed> $elements
     *
     * @return array<string>
     */
    private function extractStringElements(array $elements): array
    {
        $result = [];
        foreach ($elements as $element) {
            if (is_string($element)) {
                $result[] = $element;
            }
        }

        return $result;
    }

    private function generateCorpId(): string
    {
        return $this->faker->regexify('ww[a-zA-Z0-9]{16}');
    }

    /**
     * @return array<string, mixed>
     */
    private function generateRawData(): array
    {
        return [
            'ToUserName' => $this->generateCorpId(),
            'FromUserName' => $this->faker->userName(),
            'CreateTime' => $this->faker->unixTime(),
            'MsgType' => $this->generateMsgType(),
            'Content' => $this->faker->sentence(),
            'MsgId' => $this->faker->numerify('####################'),
            'AgentID' => $this->faker->numberBetween(1000000, 9999999),
        ];
    }

    private function generateMsgType(): string
    {
        /** @var array<int, string> $msgTypes */
        $msgTypes = ['text', 'image', 'voice', 'video', 'file', 'textcard', 'news', 'mpnews', 'taskcard', 'interactive', 'action_card', 'button_interaction', 'event'];

        return $this->faker->randomElement($msgTypes);
    }

    /**
     * @return array<string, mixed>
     */
    private function generateDecryptData(): array
    {
        $data = [
            'ToUserName' => $this->generateCorpId(),
            'FromUserName' => $this->faker->userName(),
            'CreateTime' => $this->faker->unixTime(),
            'MsgType' => $this->generateMsgType(),
        ];

        if ($this->faker->boolean(60)) {
            $data['Content'] = $this->faker->sentence();
        }

        if ($this->faker->boolean(40)) {
            $data['Event'] = $this->faker->randomElement(['add_external_contact', 'del_external_contact', 'change_type']);
        }

        if ($this->faker->boolean(30)) {
            $data['ChangeType'] = $this->faker->randomElement(['create_user', 'update_user', 'delete_user']);
        }

        return $data;
    }
}
