<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:hi',
    description: 'Приветствие2_2.',
    hidden: false
)]

class Hello2Command extends Command
{
    /*
        // название команды (часть после "bin/console")
        protected static $defaultName = 'app:my-hello';
        // описание команды, отображаемое при запуске "php bin/console list"
        protected static $defaultDescription = 'Приветствие.';
    */
    private const DEFAULT_USERNAME = null;

    protected function configure(): void
    {
        $this
            // Если вам не нравится использовать статическое свойство $defaultDescription,
            // вы также можете определить краткое описание, используя этот метод:
            ->setDescription('Команда-приветствие 2...')

            // сообщение помощи команды, отображаемое при запуске команды с опцией "--help"
            ->setHelp('Команда выводит приветствие 2...')
//            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the user.', static::DEFAULT_USERNAME)
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $b = $input->hasArgument('username');
        if ( $b) {
            $output->writeln('Аргумент username есть');
        } else {
            $output->writeln('Аргумента username нет');
        }

        $username = $input->getArgument('username');
        if ( $username === static::DEFAULT_USERNAME) {
            $questionHelper = $this->getHelper('question');
            $username = $questionHelper->ask($input, $output, new Question('<info>Ваше имя: </info>'));
            $input->setArgument('username', $username);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        if ( $username === static::DEFAULT_USERNAME) {
            $output->writeln('Не указано имя!');
        } elseif ( $username === '' ) {
            $output->writeln('Вы не указали имя!');
        } else {
            $output->writeln('Привет, привет 2 ' . $username . '...!');
        }

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
