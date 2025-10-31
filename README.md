# WechatWork Server Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-blue.svg)](https://symfony.com/)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-90%25-brightgreen.svg)](#)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle for handling WeChat Work (企业微信) server-side messages and callback events.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
  - [Bundle Registration](#bundle-registration)
  - [Route Configuration](#route-configuration)
- [Database Migration](#database-migration)
- [Quick Start](#quick-start)
  - [Handling Server Messages](#handling-server-messages)
- [Usage](#usage)
  - [Message Entity](#message-entity)
  - [Console Commands](#console-commands)
- [Advanced Usage](#advanced-usage)
  - [Custom Message Processing](#custom-message-processing)
  - [Message Filtering](#message-filtering)
- [Dependencies](#dependencies)
- [Contributing](#contributing)
  - [Development Setup](#development-setup)
- [License](#license)

## Features

- 🚀 **Server Message Processing**: Handle WeChat Work callback messages
- 🔒 **Message Encryption/Decryption**: Support for encrypted message processing
- 📊 **Message Storage**: Store and query server messages with Doctrine ORM
- 🎯 **Event System**: Dispatch events for message processing
- 📝 **Console Commands**: Import and manage server messages
- 🔄 **Callback Controllers**: Handle direct and server callbacks

## Installation

```bash
composer require tourze/wechat-work-server-bundle
```

## Configuration

### Bundle Registration

Enable the bundle in your Symfony application:

```php
// config/bundles.php
return [
    // ...
    WechatWorkServerBundle\WechatWorkServerBundle::class => ['all' => true],
];
```

### Route Configuration

Configure the routes for server callbacks:

```yaml
# config/routes.yaml
wechat_work_server:
    resource: '@WechatWorkServerBundle/config/routes.yaml'
    prefix: /wechat-work/server
```

## Database Migration

Run the following command to create the necessary database tables:

```bash
php bin/console doctrine:migrations:migrate
```

## Quick Start

### Handling Server Messages

```php
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Event\WechatWorkServerMessageRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            WechatWorkServerMessageRequestEvent::class => 'onServerMessage',
        ];
    }

    public function onServerMessage(WechatWorkServerMessageRequestEvent $event)
    {
        $message = $event->getMessage();
        
        // Process the message based on type
        switch ($message->getMsgType()) {
            case 'text':
                $this->handleTextMessage($message);
                break;
            case 'event':
                $this->handleEventMessage($message);
                break;
        }
    }
}
```

## Usage

### Message Entity

The `ServerMessage` entity stores WeChat Work server messages:

```php
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

// Get message repository
$repository = $entityManager->getRepository(ServerMessage::class);

// Create message from XML
$message = $repository->createFromXML($xmlContent);
if ($message) {
    $entityManager->persist($message);
    $entityManager->flush();
}

// Find messages by type
$textMessages = $repository->findBy(['msgType' => 'text']);

// Find messages by user
$userMessages = $repository->findBy(['fromUserName' => 'user123']);
```

### Console Commands

The bundle provides console commands for managing server messages:

```bash
# Import server messages from log file
php bin/console wechat-work:import-server-message /path/to/message.log
```

**Command Details:**
- **wechat-work:import-server-message** - Import WeChat Work server messages from a log file

## Advanced Usage

### Custom Message Processing

```php
use WechatWorkServerBundle\Controller\ServerCallbackController;
use Symfony\Component\HttpFoundation\Request;

// Custom callback handling
class CustomCallbackController extends ServerCallbackController
{
    protected function processMessage(array $messageData): void
    {
        // Custom processing logic
        parent::processMessage($messageData);
    }
}
```

### Message Filtering

```php
use WechatWorkServerBundle\Repository\ServerMessageRepository;

class CustomMessageRepository extends ServerMessageRepository
{
    public function findRecentMessages(int $limit = 50): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createTime', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
```

## Dependencies

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+
- tourze/wechat-work-bundle
- tourze/wechat-helper
- tourze/xml-helper

## Contributing

We welcome contributions! Please follow these guidelines:

1. **Issues**: Please use GitHub Issues to report bugs or request features
2. **Pull Requests**: 
    - Fork the repository
    - Create a feature branch
    - Make your changes with proper tests
    - Ensure PHPStan and PHPUnit tests pass
    - Submit a pull request

### Development Setup

```bash
# Clone the repository
git clone https://github.com/your-org/php-monorepo.git
cd php-monorepo

# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit packages/wechat-work-server-bundle/tests

# Run static analysis
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/wechat-work-server-bundle
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
