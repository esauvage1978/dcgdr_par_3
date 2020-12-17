<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{
    protected static $defaultName = 'app:loadfixtures';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('load les fixtures.')
            ->setHelp('Chargement des données.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->calcul());

        return 0;
    }

    public function calcul(): string
    {
        $debut = microtime(true);

        $this->loadFixtures('1', false);
        $this->loadFixtures('2');
        $this->loadFixtures('3');
        $this->loadFixtures('4');
        $this->loadFixtures('5');
        $this->loadFixtures('6');
        $this->loadFixtures('7');
        $this->loadFixtures('8');
        $this->loadFixtures('9');
        $this->loadFixtures('10');
        $this->loadFixtures('11');
        $this->loadFixtures('12');
        $this->loadFixtures('13');
        $this->loadFixtures('14');
        $this->loadFixtures('15');
        $this->loadFixtures('16');
        $this->loadFixtures('17');
        $this->loadFixtures('18');
        $this->loadFixtures('19');
        $this->loadFixtures('20');
        $this->loadFixtures('21');
        $this->loadFixtures('22');
        $this->loadFixtures('23');
        $this->loadFixtures('24');
        $this->loadFixtures('25');
        $this->loadFixtures('26');

        $fin = microtime(true);

        return 'Traitement effectué en  '.$this->calculTime($fin, $debut).' millisecondes.';
    }

    private function loadFixtures(string $groups, bool $append = true)
    {
        $command = 'php '.dirname(__DIR__, 2).
        '/bin/console doctrine:fixtures:load --group=step'.
        $groups.' '. ($append ? ' --append ' : '') .' -n ';
        passthru($command);
    }

    private function calculTime($fin, $debut): int
    {
        return ($fin - $debut) * 1000;
    }
}
