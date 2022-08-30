<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Entity\Events;
use App\Repository\EventsRepository;
use DateTimeImmutable;


#[AsCommand(
    name: 'app:mtest',
    description: 'Тестовая команда',
    hidden: false
)]

class MTestCommand extends Command
{
    private $eventsRepository;

    public function __construct(EventsRepository $eventsRepository)
    {
        $this->eventsRepository = $eventsRepository;

        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            // сообщение помощи команды, отображаемое при запуске команды с опцией "--help"
            ->setHelp('Тестовая команда')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $output->writeln('Тестовая команда, привет.');
        $event = new Events();
        $event->setMessage('Я тут');
        $event->setCreatedAt(new DateTimeImmutable());
        $this->eventsRepository->add($event, true);




        // этот метод должен вернуть целое число с "кодом завершения"
        // команды. Вы также можете использовать это константы, чтобы сделать код более читаемым

        // вернуть это, если при выполнении команды не было проблем
        // (равноценно возвращению int(0))
        return Command::SUCCESS;

        // или вернуть это, если во время выполнения возникла ошибка
        // (равноценно возвращению int(1))
        // return Command::FAILURE;

        // или вернуть это, чтобы указать на неправильное использование команды, например, невалидные опции
        // или отсутствующие аргументы (равноценно возвращению int(2))
        // return Command::INVALID
    }
}
