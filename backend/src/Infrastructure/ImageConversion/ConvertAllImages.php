<?php


namespace App\Infrastructure\ImageConversion;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ConvertAllImages extends Command
{
    private MessageBusInterface $messageBus;

    private FilesystemInterface $defaultStorage;

    public function __construct(MessageBusInterface $messageBus, FilesystemInterface $defaultStorage)
    {
        parent::__construct();
        $this->messageBus = $messageBus;
        $this->defaultStorage = $defaultStorage;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '512M');
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('/var/www/test/dosapi/storage'));

        foreach ($iterator as $value) {
            if (!$value->isFile()) {
                continue;
            }
            $path = str_replace('/var/www/test/dosapi/storage/', '', $value->getPathname());
            $mime = $this->defaultStorage->getMimetype($path);
            if (!str_starts_with($mime, 'image')) {
                continue;
            }
            $this->messageBus->dispatch(new ImageConversion($path));
        }
    }
}
