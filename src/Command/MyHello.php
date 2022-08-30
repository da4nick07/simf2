<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


#[AsCommand(
    name: 'app:my-hello',
    description: 'Приветствие2.',
    hidden: false
)]

class MyHello extends Command
{
/*
    // название команды (часть после "bin/console")
    protected static $defaultName = 'app:my-hello';
    // описание команды, отображаемое при запуске "php bin/console list"
    protected static $defaultDescription = 'Приветствие.';
*/
    protected function configure(): void
    {
        $this
            // Если вам не нравится использовать статическое свойство $defaultDescription,
            // вы также можете определить краткое описание, используя этот метод:
            ->setDescription('Команда-приветствие...')

            // сообщение помощи команды, отображаемое при запуске команды с опцией "--help"
            ->setHelp('Команда выводит приветствие...')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Привет, привет 2 ' . $input->getArgument('username') . '...!');


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
